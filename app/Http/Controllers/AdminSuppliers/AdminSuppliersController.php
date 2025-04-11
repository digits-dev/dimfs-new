<?php

namespace App\Http\Controllers\AdminSuppliers;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminSupplier;
use App\Models\AdminVendor;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class AdminSuppliersController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_suppliers.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminSupplier::query()->with(['getCreatedBy', 'getUpdatedBy', 'getAdminVendor']);
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
        $data['tableName'] = 'admin_suppliers';
        $data['page_title'] = 'Admin Suppliers';
        $data['admin_suppliers'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_admin_vendors'] = AdminVendor::select('id as value', 'vendor_name as label', 'status')
        ->where('status', 'ACTIVE')
            ->get();
        $data['all_admin_vendors'] = AdminVendor::select('id as value', 'vendor_name as label', 'status')     
            ->get();

        return Inertia::render("AdminSuppliers/AdminSuppliers", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'supplier_code' => 'required|string|max:15|unique:admin_suppliers,supplier_code',
            'supplier_name' => 'required|string|max:50|unique:admin_suppliers,supplier_name',
        ]);
   
        try {

            AdminSupplier::create([
                'supplier_code' => $validatedFields['supplier_code'], 
                'supplier_name' => $validatedFields['supplier_name'],        
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Supplier Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Suppliers', $e->getMessage());
            return back()->with(['message' => 'Supplier Creation Failed!', 'type' => 'error']);
        }
    
    }
}
