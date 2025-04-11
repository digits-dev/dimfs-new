<?php

namespace App\Http\Controllers\AdminMarginCategories;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminMarginCategory;
use App\Models\AdminSubClassification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class AdminMarginCategoriesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_margin_categories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminMarginCategory::query()->with(['getCreatedBy', 'getUpdatedBy', 'getAdminSubClassification']);
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
        $data['tableName'] = 'admin_margin_categories';
        $data['page_title'] = 'Admin Margin Categories';
        $data['admin_margin_categories'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_admin_sub_classifications'] = AdminSubClassification::select('id as value', 'sub_class_description as label', 'status')
        ->where('status', 'ACTIVE')
            ->get();
        $data['all_admin_sub_classifications'] = AdminSubClassification::select('id as value', 'sub_class_description as label', 'status')     
            ->get();

        return Inertia::render("AdminMarginCategories/AdminMarginCategories", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'margin_category_code' => 'required|string|max:15|unique:admin_margin_categories,margin_category_code',
            'margin_category_description' => 'required|string|max:30|unique:admin_margin_categories,margin_category_description',
        ]);

        try {

            AdminMarginCategory::create([
                'margin_category_code' => $validatedFields['margin_category_code'], 
                'margin_category_description' => $validatedFields['margin_category_description'],     
                'admin_sub_classifications_id' => $request->admin_sub_classifications_id,     
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Margin Category Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Margin Categories', $e->getMessage());
            return back()->with(['message' => 'Margin Category Creation Failed!', 'type' => 'error']);
        }
    
    }
}
