<?php

namespace App\Http\Controllers\GashaponWarehouseCategories;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\GashaponWarehouseCategories;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class GashaponWarehouseCategoriesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'gashapon_warehouse_categories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = GashaponWarehouseCategories::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'gashapon_warehouse_categories';
        $data['page_title'] = 'Gashapon Warehouse Categories';
        $data['gashapon_warehouse_categories'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("GashaponWarehouseCategories/GashaponWarehouseCategories", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'warehouse_category_description' => 'required|string|max:50|unique:gashapon_warehouse_categories,warehouse_category_description',
        ]);

        try {

            GashaponWarehouseCategories::create([
                'warehouse_category_description' => $validatedFields['warehouse_category_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Gashapon Warehouse Category Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon Warehouse Categories', $e->getMessage());
            return back()->with(['message' => 'Gashapon Warehouse Category Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'warehouse_category_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $gashapon_warehouse_categories = GashaponWarehouseCategories::find($request->id);

            if (!$gashapon_warehouse_categories) {
                return back()->with(['message' => 'Gashapon Warehouse Category not found!', 'type' => 'error']);
            }
    
            $gashaponWarehouseCategoryDescriptionExist = GashaponWarehouseCategories::where('warehouse_category_description', $request->warehouse_category_description)->exists();


            if ($request->warehouse_category_description !== $gashapon_warehouse_categories->warehouse_category_description) {
                if (!$gashaponWarehouseCategoryDescriptionExist) {
                    $gashapon_warehouse_categories->warehouse_category_description = $validatedFields['warehouse_category_description'];
                } else {
                    return back()->with(['message' => 'Warehouse Category Description already exists!', 'type' => 'error']);
                }
            }
    
            $gashapon_warehouse_categories->status = $validatedFields['status'];
            $gashapon_warehouse_categories->updated_by = CommonHelpers::myId();
            $gashapon_warehouse_categories->updated_at = now();
    
            $gashapon_warehouse_categories->save();
    
            return back()->with(['message' => 'Gashapon Warehouse Category Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Gashapon Warehouse Categories', $e->getMessage());
            return back()->with(['message' => 'Gashapon Warehouse Category Updating Failed!', 'type' => 'error']);
        }
    }

    public function export(Request $request)
    {

        $headers = [
            'Warehouse Category Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'warehouse_category_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Gashapon Vendor Types - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}
