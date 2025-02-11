<?php

namespace App\Http\Controllers\VendorGroups;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\VendorGroups;
use App\Models\Vendors;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubmasterExport;

class VendorGroupsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'vendor_groups.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = VendorGroups::query()->with(['getCreatedBy', 'getUpdatedBy', 'getVendor']);
        $filter = $query->searchAndFilter(request());
        $result = $filter->orderBy($this->sortBy, $this->sortDir);
        return $result;
    }
    
    public function getIndex()
    {
        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }

        $data = [];
        $data['tableName'] = 'vendor_groups';
        $data['page_title'] = 'Vendor Groups';
        $data['vendor_groups'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_vendors'] = Vendors::select('id', 'vendor_name as name', 'status')
            ->where('status', 'ACTIVE')
            ->get();
        $data['all_vendors'] = Vendors::select('id', 'vendor_name as name', 'status')     
            ->get();

        return Inertia::render("VendorGroups/VendorGroups", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'vendors_id' => 'required|integer',
            'vendor_group_name' => 'required|string|max:255',
        ]);

        try {

            VendorGroups::create([
                'vendors_id' => $validatedFields['vendors_id'],
                'vendor_group_name' => $validatedFields['vendor_group_name'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Vendor Group Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Vendor Groups', $e->getMessage());
            return back()->with(['message' => 'Vendor Group Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'vendors_id' => 'required|integer',
            'vendor_group_name' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $vendor_groups = VendorGroups::find($request->id);

            if (!$vendor_groups) {
                return back()->with(['message' => 'Vendor Group not found!', 'type' => 'error']);
            }
    
            $vendor_groups->vendors_id = $validatedFields['vendors_id'];
            $vendor_groups->vendor_group_name = $validatedFields['vendor_group_name'];
            $vendor_groups->status = $validatedFields['status'];
            $vendor_groups->updated_by = CommonHelpers::myId();
            $vendor_groups->updated_at = now();
    
            $vendor_groups->save();
    
            return back()->with(['message' => 'Vendor Group Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Vendor Groups', $e->getMessage());
            return back()->with(['message' => 'Vendor Group Updating Failed!', 'type' => 'error']);
        }
    }
    
    public function export(Request $request)
    {

        $headers = [
            'Vendor Group Name',
            'Vendor Name',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'vendor_group_name',
            'getVendor.vendor_name',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Vendor Groups - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}