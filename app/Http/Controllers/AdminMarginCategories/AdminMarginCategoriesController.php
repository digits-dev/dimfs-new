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

        $data['all_active_sub_classifications'] = AdminSubClassification::select('id as value', 'sub_class_description as label', 'status')
        ->where('status', 'ACTIVE')
            ->get();
        $data['all_sub_classifications'] = AdminSubClassification::select('id as value', 'sub_class_description as label', 'status')     
            ->get();

        return Inertia::render("AdminMarginCategories/AdminMarginCategories", $data);
    }
}
