<?php

namespace App\Http\Controllers\RmaItemMasters;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ActionTypes;
use App\Models\AdmModels\AdmModules;
use App\Models\RmaItemMaster;
use App\Models\RmaItemMasterApproval;
use App\Models\RmaItemMasterHistory;
use App\Models\ModuleHeaders;
use App\Models\TableSettings;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class RmaItemMastersController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    private $joins = [
        'getCreatedBy',
        'getUpdatedBy', 
        'getApprovedBy', 
        'getDeletedBy', 
        'getApprovedByAcctg', 
        'getBrand', 
        'getBrandGroup',
        'getBrandDirection',
        'getBrandMarketing',
        'getCategory',
        'getClassification',
        'getSubClassification',
        'getStoreCategory',
        'getMarginCategory',
        'getWarehouseCategory',
        'getModelSpecific',
        'getColor',
        'getVendor',
        'getVendorType',
        'getIncoterm',
        'getInventoryType',
        'getSkuStatus',
        'getSkuLegend',
        'getCurrency',
        'getWarranty',
    ];

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'rma_item_masters.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = RmaItemMaster::query()->with($this->joins);
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

        $data['tableName'] = 'rma_item_masters';
        $data['page_title'] = 'Item Master';
        $data['rma_item_masters'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['table_setting'] = explode(',', TableSettings::where('adm_moduls_id', AdmModules::RMA_ITEM_MASTER)
        ->where('action_types_id', ActionTypes::VIEW)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->pluck('report_header')
        ->first());

        $data['table_headers'] = ModuleHeaders::whereIn('header_name', $data['table_setting'])
        ->where('module_id', AdmModules::RMA_ITEM_MASTER)
        ->get();

        $data['filter_inputs'] = $data['table_headers']
        ->map(function ($columns) {
            if ($columns->table) {
                $columns->table_data = DB::table($columns->table)
                    ->select("{$columns->table_select_value} as value", "{$columns->table_select_label} as label")
                    ->get();
            }
            return $columns;
        });

        // PERMISSIONS

        $permissions = TableSettings::where('adm_moduls_id', AdmModules::RMA_ITEM_MASTER)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->whereIn('action_types_id', [ActionTypes::CREATE, ActionTypes::UPDATE, ActionTypes::EXPORT])
        ->pluck('action_types_id')
        ->toArray();

        $data['can_create'] = in_array(ActionTypes::CREATE, $permissions);
        $data['can_update'] = in_array(ActionTypes::UPDATE, $permissions);
        $data['can_export'] = in_array(ActionTypes::EXPORT, $permissions);

        return Inertia::render("RmaItemMasters/RmaItemMasters", $data);
    }

    // ------------------------------------------ CREATE ITEM ------------------------------------------ //

    public function getCreate(){
        if(!CommonHelpers::isCreate()) {
            return Inertia::render('Errors/RestrictionPage');
        }

        $data = [];
        $data['page_title'] = 'RMA Item Master - Create';

        
        $data['table_setting'] = explode(',', TableSettings::where('adm_moduls_id', AdmModules::RMA_ITEM_MASTER)
        ->where('action_types_id', ActionTypes::CREATE)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->pluck('report_header')
        ->first());

        $data['table_setting_read_only'] = explode(',', TableSettings::where('adm_moduls_id', AdmModules::RMA_ITEM_MASTER)
        ->where('action_types_id', ActionTypes::CREATE_READONLY)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->pluck('report_header')
        ->first());

        $data['create_inputs'] = ModuleHeaders::whereIn('header_name', $data['table_setting'])
        ->where('module_id', AdmModules::RMA_ITEM_MASTER)
        ->get()
        ->map(function ($columns) {
            if ($columns->table) {
                $columns->table_data = DB::table($columns->table)
                    ->select("{$columns->table_select_value} as value", "{$columns->table_select_label} as label")
                    ->get();
            }
            return $columns;
        });

        return Inertia::render("RmaItemMasters/RmaItemMasterCreate", $data);
    }

    public function create(Request $request){

        $request->validate($request->validation);

        $itemValues = json_encode(Arr::except($request->all(), ['validation']));
        try {
            
            RmaItemMasterApproval::create([
                'item_values' => $itemValues,
                'action' => 'CREATE'
            ]);

            RmaItemMasterHistory::create([
                'item_values' => $itemValues,
                'action' => 'CREATE',
                'status' => 'CREATE'
            ]);
    
            return redirect('/rma_item_masters')->with(['message' => 'RMA Item Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('RMA Item Master', $e->getMessage());
            return back()->with(['message' => 'RMA Item Creation Failed!', 'type' => 'error']);
        }
    }

    // ---------------------------------------- UPDATE ITEM ---------------------------------------- //

    public function getUpdate(RmaItemMaster $item){

        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }

        $data = [];
        $data['page_title'] = 'Item Master - Update';

        $data['rma_item_master_detail'] = RmaItemMaster::where('id', $item->id)->with($this->joins)->first();
        
        $data['table_setting'] = explode(',', TableSettings::where('adm_moduls_id', AdmModules::RMA_ITEM_MASTER)
        ->where('action_types_id', ActionTypes::UPDATE)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->pluck('report_header')
        ->first());

        $data['table_setting_read_only'] = explode(',', TableSettings::where('adm_moduls_id', AdmModules::RMA_ITEM_MASTER)
        ->where('action_types_id', ActionTypes::UPDATE_READONLY)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->pluck('report_header')
        ->first());

        $data['update_inputs'] = ModuleHeaders::whereIn('header_name', $data['table_setting'])
        ->where('module_id', AdmModules::RMA_ITEM_MASTER)
        ->get()
        ->map(function ($columns) {
            if ($columns->table) {
                $columns->table_data = DB::table($columns->table)
                    ->select("{$columns->table_select_value} as value", "{$columns->table_select_label} as label")
                    ->get();
            }
            return $columns;
        });

        return Inertia::render("RmaItemMasters/RmaItemMasterUpdate", $data);
    }

    public function update(Request $request){

        $request->validate($request->validation);

        $itemValues = json_encode(Arr::except($request->all(), ['validation']));
        try {

            RmaItemMasterApproval::updateOrCreate(
                [
                    'action' => 'UPDATE', 
                    'rma_item_master_id' => $request->id
                ],
                [
                    'item_values' => $itemValues,
                    'action' => 'UPDATE',
                    'status' => 'FOR APPROVAL',
                    'rma_item_master_id' => $request->id,
                ]
            );

            RmaItemMasterHistory::create([
                'item_values' => $itemValues,
                'action' => 'UPDATE',
                'status' => 'UPDATE',
                'rma_item_master_id' => $request->id,
            ]);

            return redirect('/rma_item_masters')->with(['message' => 'Item Update Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('RMA Item Master', $e->getMessage());
            return back()->with(['message' => 'Item Update Failed!', 'type' => 'error']);
        }
    }

    // ---------------------------------------- VIEW ITEM -------------------------------------------//

    public function getView(RmaItemMaster $item){

        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }

        $data = [];
        $data['page_title'] = 'Item Master - Item Details';
        $data['rma_item_master_detail'] = RmaItemMaster::where('id', $item->id)->with($this->joins)->first();

        $data['table_setting'] = explode(',', TableSettings::where('adm_moduls_id', AdmModules::RMA_ITEM_MASTER)
        ->where('action_types_id', ActionTypes::VIEW)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->pluck('report_header')
        ->first());

        $data['table_headers'] = ModuleHeaders::whereIn('header_name', $data['table_setting'])
        ->where('module_id', AdmModules::RMA_ITEM_MASTER)
        ->select('name', 'header_name', 'width', 'table_join')
        ->get();


        return Inertia::render("RmaItemMasters/RmaItemMasterView", $data);
    }

    // -------------------------------------- EXPORT --------------------------------------------//

    public function export()
    {

        $data['table_setting'] = explode(',', TableSettings::where('adm_moduls_id', AdmModules::RMA_ITEM_MASTER)
        ->where('action_types_id', ActionTypes::EXPORT)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->pluck('report_header')
        ->first());

        $data['table_headers'] = ModuleHeaders::whereIn('header_name', $data['table_setting'])
        ->where('module_id', AdmModules::RMA_ITEM_MASTER)
        ->select('name', 'header_name', 'width', 'table_join')
        ->get();

        $headers = [];
        $columns = [];
        
        foreach ($data['table_headers'] as $header) {
            $headers[] = $header->header_name;
            if (!empty($header->table_join)) {
                $columns[] = preg_replace_callback('/(\w+)\.(\w+)/', function ($matches) {
                    $camelCaseFirstPart = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $matches[1]))));
                    return $camelCaseFirstPart . '.' . $matches[2];
                }, $header->table_join);
            } else {
                $columns[] = $header->name;
            }
        }
        
        $filename = "Item Masters - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
    
}