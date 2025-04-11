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
        $query = AdminVendor::query()->with(['getCreatedBy', 'getUpdatedBy', 'getAdminBrand']);
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

        return Inertia::render("AdminVendors/AdminVendors", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'vendor_code' => 'required|string|max:5|unique:admin_vendors,vendor_code',
            'vendor_name' => 'required|string|max:30|unique:admin_vendors,vendor_name',
        ]);
   
        try {

            AdminVendor::create([
                'vendor_code' => $validatedFields['vendor_code'], 
                'vendor_name' => $validatedFields['vendor_name'],        
                'admin_brands_id' => $request->admin_brands_id,        
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Vendor Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Vendors', $e->getMessage());
            return back()->with(['message' => 'Vendor Creation Failed!', 'type' => 'error']);
        }
    
    }
}
