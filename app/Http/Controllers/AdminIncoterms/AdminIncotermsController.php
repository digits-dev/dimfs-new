<?php

namespace App\Http\Controllers\AdminIncoterms;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminCurrency;
use App\Models\AdminIncoterm;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminIncotermsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_incoterms.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminIncoterm::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'admin_incoterms';
        $data['page_title'] = 'Admin Incoterms';
        $data['admin_incoterms'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("AdminIncoterms/AdminIncoterms", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'incoterms_code' => 'required|string|max:5|unique:admin_incoterms,incoterms_code',
            'incoterms_description' => 'required|string|max:30|unique:admin_incoterms,incoterms_description',
        ]);

        try {

            AdminIncoterm::create([
                'incoterms_code' => $validatedFields['incoterms_code'], 
                'incoterms_description' => $validatedFields['incoterms_description'],     
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Incoterm Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Incoterms', $e->getMessage());
            return back()->with(['message' => 'Incoterm Creation Failed!', 'type' => 'error']);
        }
    
    }

    public function export()
    {

        $headers = [
            'Incoterm Code',
            'Incoterm Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'incoterms_code',
            'incoterms_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Admin Incoterms - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}
