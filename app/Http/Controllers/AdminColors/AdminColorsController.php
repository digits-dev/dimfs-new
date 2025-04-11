<?php

namespace App\Http\Controllers\AdminColors;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminColor;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class AdminColorsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_colors.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminColor::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'admin_colors';
        $data['page_title'] = 'Admin Colors';
        $data['admin_colors'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("AdminColors/AdminColors", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'color_code' => 'required|string|max:5|unique:admin_colors,color_code',
            'color_description' => 'required|string|max:30|unique:admin_colors,color_description',
        ]);

        try {

            AdminColor::create([
                'color_code' => $validatedFields['color_code'], 
                'color_description' => $validatedFields['color_description'],   
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Color Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Colors', $e->getMessage());
            return back()->with(['message' => 'Color Creation Failed!', 'type' => 'error']);
        }
    
    }
}
