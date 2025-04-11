<?php

namespace App\Http\Controllers\AdminSizes;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminSizes;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class AdminSizesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_sizes.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminSizes::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'admin_sizes';
        $data['page_title'] = 'Admin Sizes';
        $data['admin_sizes'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("AdminSizes/AdminSizes", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'size_code' => 'required|string|max:5|unique:admin_sizes,size_code',
            'size_description' => 'required|string|max:30|unique:admin_sizes,size_description',
        ]);

        try {

            AdminSizes::create([
                'size_code' => $validatedFields['size_code'], 
                'size_description' => $validatedFields['size_description'],       
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Size Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Sizes', $e->getMessage());
            return back()->with(['message' => 'Size Creation Failed!', 'type' => 'error']);
        }
    
    }
}
