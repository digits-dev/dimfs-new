<?php

namespace App\Http\Controllers\WarehouseCategories;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\WarehouseCategories;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class WarehouseCategoriesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'warehouse_categories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = WarehouseCategories::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'warehouse_categories';
        $data['page_title'] = 'Warehouse Categories';
        $data['warehouse_categories'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("WarehouseCategories/WarehouseCategories", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'warehouse_category_code' => 'required|string|max:10|unique:warehouse_categories,warehouse_category_code',
            'warehouse_category_description' => 'required|string|max:255',
        ]);

        try {

            WarehouseCategories::create([
                'warehouse_category_code' => $validatedFields['warehouse_category_code'],
                'warehouse_category_description' => $validatedFields['warehouse_category_description'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Warehouse Category Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('WarehouseCategories', $e->getMessage());
            return back()->with(['message' => 'Warehouse Category Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'warehouse_category_code' => 'required|string|max:10',
            'warehouse_category_description' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $warehouse_categories = WarehouseCategories::find($request->id);

            if (!$warehouse_categories) {
                return back()->with(['message' => 'Warehouse Category not found!', 'type' => 'error']);
            }
    
            $WarehouseCategoryCodeExist = WarehouseCategories::where('warehouse_category_code', $request->warehouse_category_code)->exists();

            if ($request->warehouse_category_code !== $warehouse_categories->warehouse_category_code) {
                if (!$WarehouseCategoryCodeExist) {
                    $warehouse_categories->warehouse_category_code = $validatedFields['warehouse_category_code'];
                } else {
                    return back()->with(['message' => 'Warehouse Category Code already exists!', 'type' => 'error']);
                }
            }
    
            $warehouse_categories->warehouse_category_description = $validatedFields['warehouse_category_description'];
            $warehouse_categories->status = $validatedFields['status'];
            $warehouse_categories->updated_by = CommonHelpers::myId();
            $warehouse_categories->updated_at = now();
    
            $warehouse_categories->save();
    
            return back()->with(['message' => 'Warehouse Category Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('WarehouseCategories', $e->getMessage());
            return back()->with(['message' => 'Warehouse Category Updating Failed!', 'type' => 'error']);
        }
    }
}