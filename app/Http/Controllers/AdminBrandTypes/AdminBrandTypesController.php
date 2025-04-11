<?php

namespace App\Http\Controllers\AdminBrandTypes;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminBrandType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class AdminBrandTypesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_brand_types.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminBrandType::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'admin_brand_types';
        $data['page_title'] = 'Admin Brand Types';
        $data['admin_brand_types'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("AdminBrandTypes/AdminBrandTypes", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'brand_type_code' => 'required|string|max:5|unique:admin_brand_types,brand_type_code',
            'brand_type_description' => 'required|string|max:30|unique:admin_brand_types,brand_type_description',
        ]);

        try {

            AdminBrandType::create([
                'brand_type_code' => $validatedFields['brand_type_code'], 
                'brand_type_description' => $validatedFields['brand_type_description'],   
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Brand Type Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Brand Types', $e->getMessage());
            return back()->with(['message' => 'Brand Type Creation Failed!', 'type' => 'error']);
        }
    
    }
}
