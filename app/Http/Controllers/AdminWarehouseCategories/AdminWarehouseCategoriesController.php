<?php

namespace App\Http\Controllers\AdminWarehouseCategories;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminWarehouseCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class AdminWarehouseCategoriesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_warehouse_categories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminWarehouseCategory::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'admin_warehouse_categories';
        $data['page_title'] = 'Admin Warehouse Categories';
        $data['admin_warehouse_categories'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("AdminWarehouseCategories/AdminWarehouseCategories", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'wh_category_code' => 'required|string|max:5|unique:admin_warehouse_categories,wh_category_code',
            'wh_category_description' => 'required|string|max:30|unique:admin_warehouse_categories,wh_category_description',
        ]);
   
        try {

            AdminWarehouseCategory::create([
                'wh_category_code' => $validatedFields['wh_category_code'], 
                'wh_category_description' => $validatedFields['wh_category_description'],          
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Warehouse Category Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Warehouse Categories', $e->getMessage());
            return back()->with(['message' => 'Warehouse Category Creation Failed!', 'type' => 'error']);
        }
    
    }
}
