<?php

namespace App\Http\Controllers\AdminClassifications;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminCategory;
use App\Models\AdminClassification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminClassificationsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_classifications.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminClassification::query()->with(['getCreatedBy', 'getUpdatedBy', 'getAdminCategory']);
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
        $data['tableName'] = 'admin_classifications';
        $data['page_title'] = 'Admin Classifications';
        $data['admin_classifications'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_admin_categories'] = AdminCategory::select('id as value', 'category_description as label', 'status')
            ->where('status', 'ACTIVE')
                ->get();
        $data['all_admin_categories'] = AdminCategory::select('id as value', 'category_description as label', 'status')     
            ->get();

        return Inertia::render("AdminClassifications/AdminClassifications", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'class_code' => 'required|string|max:5|unique:admin_classifications,class_code',
            'class_description' => 'required|string|max:50|unique:admin_classifications,class_description',
        ]);

        try {

            AdminClassification::create([
                'class_code' => $validatedFields['class_code'], 
                'class_description' => $validatedFields['class_description'],   
                'admin_categories_id' => $request->admin_categories_id,   
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Classification Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Classifications', $e->getMessage());
            return back()->with(['message' => 'Classification Creation Failed!', 'type' => 'error']);
        }
    
    }

    public function export()
    {

        $headers = [
            'Classification Code',
            'Classification Description',
            'Category Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'class_code',
            'class_description',
            'getAdminCategory.category_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Admin Classifications - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}
