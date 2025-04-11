<?php

namespace App\Http\Controllers\AdminStoreCategories;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminStoreCategory;
use App\Models\AdminSubClassification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class AdminStoreCategoriesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_store_categories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminStoreCategory::query()->with(['getCreatedBy', 'getUpdatedBy', 'getAdminSubClassification']);
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
        $data['tableName'] = 'admin_store_categories';
        $data['page_title'] = 'Admin Store Categories';
        $data['admin_store_categories'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_admin_sub_classifications'] = AdminSubClassification::select('id as value', 'sub_class_description as label', 'status')
        ->where('status', 'ACTIVE')
            ->get();
        $data['all_admin_sub_classifications'] = AdminSubClassification::select('id as value', 'sub_class_description as label', 'status')     
            ->get();

        return Inertia::render("AdminStoreCategories/AdminStoreCategories", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'store_category_code' => 'required|string|max:15|unique:admin_store_categories,store_category_code',
            'store_category_description' => 'required|string|max:30|unique:admin_store_categories,store_category_description',
        ]);

        try {

            AdminStoreCategory::create([
                'store_category_code' => $validatedFields['store_category_code'], 
                'store_category_description' => $validatedFields['store_category_description'],     
                'admin_sub_classifications_id' => $request->admin_sub_classifications_id,     
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Store Category Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Store Categories', $e->getMessage());
            return back()->with(['message' => 'Store Category Creation Failed!', 'type' => 'error']);
        }
    
    }
}
