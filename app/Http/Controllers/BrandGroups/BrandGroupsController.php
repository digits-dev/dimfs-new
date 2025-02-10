<?php

namespace App\Http\Controllers\BrandGroups;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\BrandGroups;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubmasterExport;

class BrandGroupsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'brand_groups.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = BrandGroups::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'brand_groups';
        $data['page_title'] = 'Brand Groups';
        $data['brand_groups'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("BrandGroups/BrandGroups", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'brand_group_description' => 'required|string|max:50',
        ]);

        try {

            BrandGroups::create([
                'brand_group_description' => $validatedFields['brand_group_description'], 
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
                'created_at' => now(),
            ]);
    
            return back()->with(['message' => 'Brand Group Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Brand Groups', $e->getMessage());
            return back()->with(['message' => 'Brand Group Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'brand_group_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $brandGroups = BrandGroups::find($request->id);

            if (!$brandGroups) {
                return back()->with(['message' => 'Brand Group not found!', 'type' => 'error']);
            }

            $brandGroups->brand_group_description = $validatedFields['brand_group_description'];
            $brandGroups->status = $validatedFields['status'];
            $brandGroups->updated_by = CommonHelpers::myId();
            $brandGroups->updated_at = now();
            $brandGroups->save();
    
            return back()->with(['message' => 'Brand Group Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Brand Groups', $e->getMessage());
            return back()->with(['message' => 'Brand Group Updating Failed!', 'type' => 'error']);
        }
    }
    public function export(Request $request)
    {

        $headers = [
            'Brand Group Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'brand_group_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Brands Groups - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}