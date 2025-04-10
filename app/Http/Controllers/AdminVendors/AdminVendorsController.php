<?php

namespace App\Http\Controllers\AdminVendors;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminBrand;
use App\Models\AdminIncoterm;
use App\Models\AdminVendor;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class AdminVendorsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_vendors.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminVendor::query()->with(['getCreatedBy', 'getUpdatedBy', 'getAdminBrand', 'getAdminIncoterm']);
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
        $data['tableName'] = 'admin_vendors';
        $data['page_title'] = 'Admin Vendors';
        $data['admin_vendors'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_admin_brands'] = AdminBrand::select('id as value', 'brand_description as label', 'status')
        ->where('status', 'ACTIVE')
            ->get();
        $data['all_admin_brands'] = AdminBrand::select('id as value', 'brand_description as label', 'status')     
            ->get();

        $data['all_active_admin_incoterms'] = AdminIncoterm::select('id as value', 'incoterms_description as label', 'status')
        ->where('status', 'ACTIVE')
            ->get();
        $data['all_admin_incoterms'] = AdminIncoterm::select('id as value', 'incoterms_description as label', 'status')     
            ->get();

        return Inertia::render("AdminVendors/AdminVendors", $data);
    }
}
