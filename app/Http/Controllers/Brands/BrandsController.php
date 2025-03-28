<?php

namespace App\Http\Controllers\Brands;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\BrandGroups;
use App\Models\Brands;
use App\Models\ModuleActivityHistory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class BrandsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'brands.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = Brands::query()->with(['getCreatedBy', 'getUpdatedBy', 'getBrandGroup']);
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
        $data['tableName'] = 'brands';
        $data['page_title'] = 'Brands';
        $data['brands'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_brand_groups'] = BrandGroups::select('id', 'brand_group_description as name', 'status')
            ->where('status', 'ACTIVE')
                ->get();
        $data['all_brand_groups'] = BrandGroups::select('id', 'brand_group_description as name', 'status')     
            ->get();

        return Inertia::render("Brands/Brands", $data);
        
    }

    public function create(Request $request){


        $validatedFields = $request->validate([
            'brand_code' => 'required|string|max:3|unique:brands,brand_code',
            'brand_description' => 'required|string|max:30|unique:brands,brand_description',
            'brand_groups_id' => 'required|integer',
            'contact_email' => 'nullable|email',
        ]);

        try {

            Brands::create([
                'brand_code' => $validatedFields['brand_code'], 
                'brand_description' => $validatedFields['brand_description'],   
                'brand_groups_id' => $validatedFields['brand_groups_id'], 
                'contact_email' => $request->contact_email, 
                'contact_name' => $request->contact_name, 
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);

            // ModuleActivityHistory::create([
            //     'module_name' => 'Brands',
            //     'action_type' => 'CREATE',
            //     'created_by' => CommonHelpers::myId(),
            // ]);
    
            return back()->with(['message' => 'Brand Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Brands', $e->getMessage());
            return back()->with(['message' => 'Brand Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'brand_code' => 'required|string|max:3',
            'brand_description' => 'required|string|max:30',
            'brand_groups_id' => 'required|integer',
            'status' => 'required|string',
        ]);

        try {
    
            $brands = Brands::find($request->id);

            if (!$brands) {
                return back()->with(['message' => 'Brand not found!', 'type' => 'error']);
            }
    
            $brandCodeExist = Brands::where('brand_code', $request->brand_code)->exists();
            $brandDescriptionExist = Brands::where('brand_description', $request->brand_description)->exists();

            if ($request->brand_code !== $brands->brand_code) {
                if (!$brandCodeExist) {
                    $brands->brand_code = $validatedFields['brand_code'];
                } else {
                    return back()->with(['message' => 'Brand code already exists!', 'type' => 'error']);
                }
            }
            if ($request->brand_description !== $brands->brand_description) {
                if (!$brandDescriptionExist) {
                    $brands->brand_description = $validatedFields['brand_description'];
                } else {
                    return back()->with(['message' => 'Brand Description already exists!', 'type' => 'error']);
                }
            }
    
            $brands->brand_groups_id = $validatedFields['brand_groups_id'];
            $brands->contact_email = $request->contact_email;
            $brands->contact_name = $request->contact_name;
            $brands->status = $validatedFields['status'];
            $brands->updated_by = CommonHelpers::myId();
            $brands->updated_at = now();
    
            $brands->save();

            // ModuleActivityHistory::create([
            //     'module_name' => 'Brands',
            //     'action_type' => 'UPDATE',
            //     'created_by' => CommonHelpers::myId(),
            // ]);
    
    
            return back()->with(['message' => 'Brand Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Brands', $e->getMessage());
            return back()->with(['message' => 'Brand Updating Failed!', 'type' => 'error']);
        }
    }

    public function export(Request $request)
    {

        $headers = [
            'Brand Code',
            'Brand Description',
            'Brand Brand Group',
            'Contact Name',
            'Contact Email',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'brand_code',
            'brand_description',
            'getBrandGroup.brand_group_description',
            'contact_name',
            'contact_email',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Brands - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
    
}