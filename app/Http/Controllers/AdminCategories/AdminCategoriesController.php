<?php

namespace App\Http\Controllers\AdminCategories;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class AdminCategoriesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_categories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminCategory::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'admin_categories';
        $data['page_title'] = 'Admin Categories';
        $data['admin_categories'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("AdminCategories/AdminCategories", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'category_code' => 'required|string|max:5|unique:admin_categories,category_code',
            'category_description' => 'required|string|max:30|unique:admin_categories,category_description',
        ]);

        try {

            AdminCategory::create([
                'category_code' => $validatedFields['category_code'], 
                'category_description' => $validatedFields['category_description'],   
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Category Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Categories', $e->getMessage());
            return back()->with(['message' => 'Category Creation Failed!', 'type' => 'error']);
        }
    
    }
}
