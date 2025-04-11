<?php

namespace App\Http\Controllers\AdminSkuStatuses;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminSkuStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminSkuStatusesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_sku_statuses.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminSkuStatus::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'admin_sku_statuses';
        $data['page_title'] = 'Admin SKU Statuses';
        $data['admin_sku_statuses'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("AdminSkuStatuses/AdminSkuStatuses", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'sku_status_code' => 'required|string|max:5|unique:admin_sku_statuses,sku_status_code',
            'sku_status_description' => 'required|string|max:30|unique:admin_sku_statuses,sku_status_description',
        ]);

        try {

            AdminSkuStatus::create([
                'sku_status_code' => $validatedFields['sku_status_code'], 
                'sku_status_description' => $validatedFields['sku_status_description'],       
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'SKU Status Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin SKU Statuses', $e->getMessage());
            return back()->with(['message' => 'SKU Status Creation Failed!', 'type' => 'error']);
        }
    
    }

    public function export()
    {

        $headers = [
            'SKU Status Code',
            'SKU Status Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'sku_status_code',
            'sku_status_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Admin SKU Statuses - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}
