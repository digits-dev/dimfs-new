<?php

namespace App\Http\Controllers\SkuStatuses;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\SkuStatuses;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubmasterExport;


class SkuStatusesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'sku_statuses.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = SkuStatuses::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'sku_statuses';
        $data['page_title'] = 'SKU Statuses';
        $data['sku_statuses'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("SkuStatuses/SkuStatuses", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'sku_status_code' => 'required|string|max:10|unique:sku_statuses,sku_status_code',
            'sku_status_description' => 'required|string|max:255',
        ]);

        try {

            SkuStatuses::create([
                'sku_status_code' => $validatedFields['sku_status_code'],
                'sku_status_description' => $validatedFields['sku_status_description'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'SKU Status Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('SKU Statuses', $e->getMessage());
            return back()->with(['message' => 'SKU Status Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'sku_status_code' => 'required|string|max:10',
            'sku_status_description' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $sku_statuses = SkuStatuses::find($request->id);

            if (!$sku_statuses) {
                return back()->with(['message' => 'Sku Status not found!', 'type' => 'error']);
            }
    
            $SkuStatusCodeExist = SkuStatuses::where('sku_status_code', $request->sku_status_code)->exists();

            if ($request->sku_status_code !== $sku_statuses->sku_status_code) {
                if (!$SkuStatusCodeExist) {
                    $sku_statuses->sku_status_code = $validatedFields['sku_status_code'];
                } else {
                    return back()->with(['message' => 'SKU Status Code already exists!', 'type' => 'error']);
                }
            }
    
            $sku_statuses->sku_status_description = $validatedFields['sku_status_description'];
            $sku_statuses->status = $validatedFields['status'];
            $sku_statuses->updated_by = CommonHelpers::myId();
            $sku_statuses->updated_at = now();
    
            $sku_statuses->save();
    
            return back()->with(['message' => 'SKU Status Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('SKU Statuses', $e->getMessage());
            return back()->with(['message' => 'SKU Status Updating Failed!', 'type' => 'error']);
        }
    }
    public function export(Request $request)
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

        $filename = "SKU Statuses - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}