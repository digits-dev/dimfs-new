<?php

namespace App\Http\Controllers\AdminModelSpecifics;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminModelSpecific;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminModelSpecificsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_model_specifics.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminModelSpecific::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'admin_model_specifics';
        $data['page_title'] = 'Admin Model Specifics';
        $data['admin_model_specifics'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("AdminModelSpecifics/AdminModelSpecifics", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'model_specific_code' => 'required|string|max:15|unique:admin_model_specifics,model_specific_code',
            'model_specific_description' => 'required|string|max:30|unique:admin_model_specifics,model_specific_description',
        ]);

        try {

            AdminModelSpecific::create([
                'model_specific_code' => $validatedFields['model_specific_code'], 
                'model_specific_description' => $validatedFields['model_specific_description'],       
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Model Specific Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Model Specifics', $e->getMessage());
            return back()->with(['message' => 'Model Specific Creation Failed!', 'type' => 'error']);
        }
    
    }

    public function export()
    {

        $headers = [
            'Model Specific Code',
            'Model Specific Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'model_specific_code',
            'model_specific_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Admin Model Specifics - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}
