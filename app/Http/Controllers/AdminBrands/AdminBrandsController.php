<?php

namespace App\Http\Controllers\AdminBrands;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminBrand;
use App\Models\AdminBrandType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AdminBrandsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_brands.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminBrand::query()->with(['getCreatedBy', 'getUpdatedBy', 'getAdminBrandTypes']);
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
        $data['tableName'] = 'admin_brands';
        $data['page_title'] = 'Admin Brands';
        $data['admin_brands'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_brand_types'] = AdminBrandType::select('id as value', 'brand_type_description as label', 'status')
            ->where('status', 'ACTIVE')
                ->get();
        $data['all_brand_types'] = AdminBrandType::select('id as value', 'brand_type_description as label', 'status')     
            ->get();

        return Inertia::render("AdminBrands/AdminBrands", $data);
    }
}
