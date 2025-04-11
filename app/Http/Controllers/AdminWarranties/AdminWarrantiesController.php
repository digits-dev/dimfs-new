<?php

namespace App\Http\Controllers\AdminWarranties;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminWarranty;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class AdminWarrantiesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_warranties.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminWarranty::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'admin_warranties';
        $data['page_title'] = 'Admin Warranties';
        $data['admin_warranties'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("AdminWarranties/AdminWarranties", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'warranty_code' => 'required|string|max:5|unique:admin_warranties,warranty_code',
            'warranty_description' => 'required|string|max:30|unique:admin_warranties,warranty_description',
        ]);
   
        try {

            AdminWarranty::create([
                'warranty_code' => $validatedFields['warranty_code'], 
                'warranty_description' => $validatedFields['warranty_description'],          
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Warranty Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Warranties', $e->getMessage());
            return back()->with(['message' => 'Warranty Creation Failed!', 'type' => 'error']);
        }
    
    }
}
