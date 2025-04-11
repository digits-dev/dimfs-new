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


    public function create(Request $request){

        $validatedFields = $request->validate([
            'sub_class_code' => 'required|string|max:10|unique:admin_sub_classifications,sub_class_code',
            'sub_class_description' => 'required|string|max:50|unique:admin_sub_classifications,sub_class_description',
        ]);
   
        try {

            AdminSubClassification::create([
                'sub_class_code' => $validatedFields['sub_class_code'], 
                'sub_class_description' => $validatedFields['sub_class_description'],     
                'admin_classifications_id' => $request->admin_classifications_id,     
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Sub Classification Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Sub Classifications', $e->getMessage());
            return back()->with(['message' => 'Sub Classification Creation Failed!', 'type' => 'error']);
        }
    
    }
}
