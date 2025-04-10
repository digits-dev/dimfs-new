<?php

namespace App\Http\Controllers\AdminClassifications;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminCategory;
use App\Models\AdminClassification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class AdminClassificationsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_classifications.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminClassification::query()->with(['getCreatedBy', 'getUpdatedBy', 'getAdminCategory']);
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
        $data['tableName'] = 'admin_classifications';
        $data['page_title'] = 'Admin Classifications';
        $data['admin_classifications'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_admin_categories'] = AdminCategory::select('id as value', 'category_description as label', 'status')
            ->where('status', 'ACTIVE')
                ->get();
        $data['all_admin_categories'] = AdminCategory::select('id as value', 'category_description as label', 'status')     
            ->get();

        return Inertia::render("AdminClassifications/AdminClassifications", $data);
    }
}
