<?php

namespace App\Http\Controllers\AdminSubCategories;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminCategory;
use App\Models\AdminSubCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class AdminSubCategoriesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_sub_categories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminSubCategory::query()->with(['getCreatedBy', 'getUpdatedBy', 'getAdminCategory']);
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
        $data['tableName'] = 'admin_sub_categories';
        $data['page_title'] = 'Admin Sub Categories';
        $data['admin_sub_categories'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_admin_categories'] = AdminCategory::select('id as value', 'category_description as label', 'status')
        ->where('status', 'ACTIVE')
            ->get();
        $data['all_admin_categories'] = AdminCategory::select('id as value', 'category_description as label', 'status')     
            ->get();

        return Inertia::render("AdminSubCategories/AdminSubCategories", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'sub_category_code' => 'required|string|max:15|unique:admin_sub_categories,sub_category_code',
            'sub_category_description' => 'required|string|max:50|unique:admin_sub_categories,sub_category_description',
        ]);

        try {

            AdminSubCategory::create([
                'sub_category_code' => $validatedFields['sub_category_code'], 
                'sub_category_description' => $validatedFields['sub_category_description'],     
                'admin_categories_id' => $request->admin_categories_id,     
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Sub Category Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Sub Categories', $e->getMessage());
            return back()->with(['message' => 'Sub Category Creation Failed!', 'type' => 'error']);
        }
    
    }
}
