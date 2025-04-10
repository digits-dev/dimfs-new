<?php

namespace App\Http\Controllers\AdminSubClassifications;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminClassification;
use App\Models\AdminSubClassification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class AdminSubClassificationsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_sub_classifications.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminSubClassification::query()->with(['getCreatedBy', 'getUpdatedBy', 'getAdminClassification']);
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
        $data['tableName'] = 'admin_sub_classifications';
        $data['page_title'] = 'Admin Sub Classifications';
        $data['admin_sub_classifications'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_admin_classifications'] = AdminClassification::select('id as value', 'class_description as label', 'status')
        ->where('status', 'ACTIVE')
            ->get();
        $data['all_admin_classifications'] = AdminClassification::select('id as value', 'class_description as label', 'status')     
            ->get();

        return Inertia::render("AdminSubClassifications/AdminSubClassifications", $data);
    }
}
