<?php

namespace App\Http\Controllers\GashaponVendorGroups;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\GashaponVendorGroups;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class GashaponVendorGroupsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'gashapon_vendor_groups.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = GashaponVendorGroups::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'gashapon_vendor_groups';
        $data['page_title'] = 'Gashapon Vendor Groups';
        $data['gashapon_vendor_groups'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("GashaponVendorGroups/GashaponVendorGroups", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'vendor_group_description' => 'required|string|max:50|unique:gashapon_vendor_groups,vendor_group_description',
        ]);

        try {

            GashaponVendorGroups::create([
                'vendor_group_description' => $validatedFields['vendor_group_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Gashapon Vendor Group Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon Vendor Groups', $e->getMessage());
            return back()->with(['message' => 'Gashapon Vendor Group Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'vendor_group_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $gashapon_vendor_groups = GashaponVendorGroups::find($request->id);

            if (!$gashapon_vendor_groups) {
                return back()->with(['message' => 'Gashapon Vendor Group not found!', 'type' => 'error']);
            }
    
            $gashaponVendorGroupDescriptionExist = GashaponVendorGroups::where('vendor_group_description', $request->vendor_group_description)->exists();


            if ($request->vendor_group_description !== $gashapon_vendor_groups->vendor_group_description) {
                if (!$gashaponVendorGroupDescriptionExist) {
                    $gashapon_vendor_groups->vendor_group_description = $validatedFields['vendor_group_description'];
                } else {
                    return back()->with(['message' => 'Vendor Group Description already exists!', 'type' => 'error']);
                }
            }
    
            $gashapon_vendor_groups->status = $validatedFields['status'];
            $gashapon_vendor_groups->updated_by = CommonHelpers::myId();
            $gashapon_vendor_groups->updated_at = now();
    
            $gashapon_vendor_groups->save();
    
            return back()->with(['message' => 'Gashapon Vendor Group Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Gashapon Vendor Groups', $e->getMessage());
            return back()->with(['message' => 'Gashapon Vendor Group Updating Failed!', 'type' => 'error']);
        }
    }
}
