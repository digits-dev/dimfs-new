<?php

namespace App\Http\Controllers\ItemMasters;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ActionTypes;
use App\Models\AdmModels\AdmModules;
use App\Models\ItemMaster;
use App\Models\ItemMasterApproval;
use App\Models\ItemMasterHistory;
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

class ItemMastersController extends Controller
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
        $this->sortBy = request()->get('sortBy', 'item_masters.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = ItemMaster::query()->with($this->joins);
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

        $data['tableName'] = 'item_masters';
        $data['page_title'] = 'Item Master';
        $data['item_masters'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, ActionTypes::VIEW, CommonHelpers::myPrivilegeId());
        $data['table_headers'] = ModuleHeaders::getHeadersByModule(AdmModules::ITEM_MASTER, $tableSetting);

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

        $permissions = TableSettings::where('adm_moduls_id', AdmModules::ITEM_MASTER)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->whereIn('action_types_id', [ActionTypes::CREATE, ActionTypes::UPDATE, ActionTypes::EXPORT])
        ->pluck('action_types_id')
        ->toArray();

        $data['can_create'] = in_array(ActionTypes::CREATE, $permissions);
        $data['can_update'] = in_array(ActionTypes::UPDATE, $permissions);
        $data['can_export'] = in_array(ActionTypes::EXPORT, $permissions);

        return Inertia::render("ItemMasters/ItemMasters", $data);
    }

    // ------------------------------------------ CREATE ITEM ------------------------------------------ //

    public function getCreate(){
        if(!CommonHelpers::isCreate()) {
            return Inertia::render('Errors/RestrictionPage');
        }

        $data = [];
        $data['page_title'] = 'Item Master - Create';

        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, ActionTypes::CREATE, CommonHelpers::myPrivilegeId());
        $data['table_setting_read_only'] = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, ActionTypes::CREATE_READONLY, CommonHelpers::myPrivilegeId());
        
        $data['create_inputs'] = ModuleHeaders::whereIn('header_name', $tableSetting)
        ->where('module_id', AdmModules::ITEM_MASTER)
        ->orderBy('sorting')
        ->get()
        ->map(function ($columns) {
            if ($columns->table) {
                $columns->table_data = DB::table($columns->table)
                ->select("{$columns->table_select_value} as value", "{$columns->table_select_label} as label")
                ->get();
            }
            return $columns;
        });
        
        return Inertia::render("ItemMasters/ItemMasterCreate", $data);
    }

    public function create(Request $request){

        $request->validate($request->validation);

        $itemValues = json_encode(Arr::except($request->all(), ['validation']));

        try {

            ItemMasterApproval::create([
               'item_values' => $itemValues,
               'action' => 'CREATE'
            ]);

            ItemMasterHistory::create([
                'item_values' => $itemValues,
                'action' => 'CREATE',
                'status' => 'CREATE'
            ]);
    
            return redirect('/item_masters')->with(['message' => 'Item Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Item Master', $e->getMessage());
            return back()->with(['message' => 'Item Creation Failed!', 'type' => 'error']);
        }
    }

    // ---------------------------------------- UPDATE ITEM ---------------------------------------- //

    public function getUpdate(ItemMaster $item){

        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }

        $data = [];
        $data['page_title'] = 'Item Master - Update';

        $data['item_master_detail'] = ItemMaster::where('id', $item->id)->with($this->joins)->first();
        
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, ActionTypes::UPDATE, CommonHelpers::myPrivilegeId());
        $data['table_setting_read_only'] = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, ActionTypes::UPDATE_READONLY, CommonHelpers::myPrivilegeId());

        $data['update_inputs'] = ModuleHeaders::whereIn('header_name', $tableSetting)
        ->where('module_id', AdmModules::ITEM_MASTER)
        ->orderBy('sorting')
        ->get()
        ->map(function ($columns) {
            if ($columns->table) {
                $columns->table_data = DB::table($columns->table)
                    ->select("{$columns->table_select_value} as value", "{$columns->table_select_label} as label")
                    ->get();
            }
            return $columns;
        });

        return Inertia::render("ItemMasters/ItemMasterUpdate", $data);
    }

    public function update(Request $request){

        $request->validate($request->validation);

        $itemValues = json_encode(Arr::except($request->all(), ['validation']));

        try {

            ItemMasterApproval::updateOrCreate(
                [
                    'action' => 'UPDATE', 
                    'item_master_id' => $request->id
                ],
                [
                    'item_values' => $itemValues,
                    'action' => 'UPDATE',
                    'status' => 'FOR APPROVAL',
                    'item_master_id' => $request->id,
                ]
            );

            ItemMasterHistory::create([
                'item_values' => $itemValues,
                'action' => 'UPDATE',
                'status' => 'UPDATE',
                'item_master_id' => $request->id,
            ]);

            return redirect('/item_masters')->with(['message' => 'Item Update Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Item Master', $e->getMessage());
            return back()->with(['message' => 'Item Update Failed!', 'type' => 'error']);
        }
    }

    // ---------------------------------------- VIEW ITEM -------------------------------------------//

    public function getView(ItemMaster $item){

        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }

        $data = [];
        $data['page_title'] = 'Item Master - Item Details';
        $data['item_master_detail'] = ItemMaster::where('id', $item->id)->with($this->joins)->first();
        
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, ActionTypes::VIEW, CommonHelpers::myPrivilegeId());
        $data['table_headers'] = ModuleHeaders::getHeadersByModule(AdmModules::ITEM_MASTER, $tableSetting);


        return Inertia::render("ItemMasters/ItemMasterView", $data);
    }

    // -------------------------------------- EXPORT --------------------------------------------//

    public function export()
    {

        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, ActionTypes::EXPORT, CommonHelpers::myPrivilegeId());
        $data['table_headers'] = ModuleHeaders::getHeadersByModule(AdmModules::ITEM_MASTER, $tableSetting);

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