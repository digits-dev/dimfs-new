<?php

namespace App\Http\Controllers\AdminVendorTypes;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminVendorType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class AdminVendorTypesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_vendor_types.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminVendorType::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'admin_vendor_types';
        $data['page_title'] = 'Admin Vendor Types';
        $data['admin_vendor_types'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("AdminVendorTypes/AdminVendorTypes", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'vendor_type_code' => 'required|string|max:15|unique:admin_vendor_types,vendor_type_code',
            'vendor_type_description' => 'required|string|max:30|unique:admin_vendor_types,vendor_type_description',
        ]);
   
        try {

            AdminVendorType::create([
                'vendor_type_code' => $validatedFields['vendor_type_code'], 
                'vendor_type_description' => $validatedFields['vendor_type_description'],          
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Vendor Type Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Vendor Types', $e->getMessage());
            return back()->with(['message' => 'Vendor Type Creation Failed!', 'type' => 'error']);
        }
    
    }
}
