<?php

namespace App\Http\Controllers\GashaponSkuStatuses;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\GashaponSkuStatuses;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class GashaponSkuStatusesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'gashapon_sku_statuses.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = GashaponSkuStatuses::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'gashapon_sku_statuses';
        $data['page_title'] = 'Gashapon SKU Statuses';
        $data['gashapon_sku_statuses'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia("GashaponSkuStatuses/GashaponSkuStatuses", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'status_description' => 'required|string|max:50|unique:gashapon_sku_statuses,status_description',
        ]);

        try {

            GashaponSkuStatuses::create([
                'status_description' => $validatedFields['status_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Gashapon SKU Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon SKU Statuses', $e->getMessage());
            return back()->with(['message' => 'Gashapon SKU Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'status_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $gashapon_sku_statuses = GashaponSkuStatuses::find($request->id);

            if (!$gashapon_sku_statuses) {
                return back()->with(['message' => 'Gashapon SKU not found!', 'type' => 'error']);
            }
    
            $gashaponStatusDescriptionExist = GashaponSkuStatuses::where('status_description', $request->status_description)->exists();


            if ($request->status_description !== $gashapon_sku_statuses->status_description) {
                if (!$gashaponStatusDescriptionExist) {
                    $gashapon_sku_statuses->status_description = $validatedFields['status_description'];
                } else {
                    return back()->with(['message' => 'Status Description already exists!', 'type' => 'error']);
                }
            }
    
            $gashapon_sku_statuses->status = $validatedFields['status'];
            $gashapon_sku_statuses->updated_by = CommonHelpers::myId();
            $gashapon_sku_statuses->updated_at = now();
    
            $gashapon_sku_statuses->save();
    
            return back()->with(['message' => 'Gashapon SKU Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Gashapon SKU Statuses', $e->getMessage());
            return back()->with(['message' => 'Gashapon SKU Updating Failed!', 'type' => 'error']);
        }
    }
}
