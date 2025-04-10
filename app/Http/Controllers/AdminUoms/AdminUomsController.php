<?php

namespace App\Http\Controllers\AdminUoms;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminUom;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class AdminUomsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_uoms.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminUom::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'admin_uoms';
        $data['page_title'] = 'Admin UOMs';
        $data['admin_uoms'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("AdminUoms/AdminUoms", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'uom_code' => 'required|string|max:5|unique:admin_uoms,uom_code',
            'uom_description' => 'required|string|max:30|unique:admin_uoms,uom_description',
        ]);
   
        try {

            AdminUom::create([
                'uom_code' => $validatedFields['uom_code'], 
                'uom_description' => $validatedFields['uom_description'],        
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'UOM Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin UOMs', $e->getMessage());
            return back()->with(['message' => 'UOM Creation Failed!', 'type' => 'error']);
        }
    
    }
}
