<?php

namespace App\Http\Controllers\GashaponItemMasters;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ActionTypes;
use App\Models\AdmModels\AdmModules;
use App\Models\GashaponItemMaster;
use App\Models\GashaponItemMasterApproval;
use App\Models\GashaponItemMasterHistory;
use App\Models\ModuleHeaders;
use App\Models\TableSettings;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Arr;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ImportTemplate;

class GashaponItemMastersController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    private $joins = [
        'getCreatedBy',
        'getUpdatedBy', 
        'getApprovedBy', 
        'getApprovedByAcctg', 
        'getGashaponBrand', 
        'getGashaponCategory', 
        'getGashaponProductType', 
        'getGashaponIncoterm', 
        'getGashaponUoms', 
        'getGashaponWarehouseCategory', 
        'getGashaponInventoryType', 
        'getGashaponVendorType', 
        'getGashaponVendorGroup', 
        'getGashaponCountry', 
        'getGashaponSkuStatus', 
        'getCurrency', 
    ];

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'gashapon_item_masters.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = GashaponItemMaster::query()->with($this->joins);
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
        $data['page_title'] = 'Gashapon Item Master';
        $data['gashapon_item_masters'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $tableSetting = TableSettings::getActiveHeaders(AdmModules::GASHAPON_ITEM_MASTER, ActionTypes::VIEW, CommonHelpers::myPrivilegeId());
        $data['table_headers'] = ModuleHeaders::getHeadersByModule(AdmModules::GASHAPON_ITEM_MASTER, $tableSetting);

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

        $permissions = TableSettings::where('adm_moduls_id', AdmModules::GASHAPON_ITEM_MASTER)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->whereIn('action_types_id', [ActionTypes::CREATE, ActionTypes::UPDATE, ActionTypes::EXPORT, ActionTypes::IMPORT])
        ->pluck('action_types_id')
        ->toArray();

        $data['can_create'] = in_array(ActionTypes::CREATE, $permissions);
        $data['can_update'] = in_array(ActionTypes::UPDATE, $permissions);
        $data['can_export'] = in_array(ActionTypes::EXPORT, $permissions);
        $data['can_import'] = in_array(ActionTypes::IMPORT, $permissions);
        

        return Inertia::render("GashaponItemMasters/GashaponItemMasters", $data);
    }

    // ------------------------------------------ CREATE ITEM ------------------------------------------ //

    public function getCreate(){
        if(!CommonHelpers::isCreate()) {
            return Inertia::render('Errors/RestrictionPage');
        }

        $data = [];
        $data['page_title'] = 'Gashapon Item Master - Create';

        $tableSetting = TableSettings::getActiveHeaders(AdmModules::GASHAPON_ITEM_MASTER, ActionTypes::CREATE, CommonHelpers::myPrivilegeId());
        $data['table_setting_read_only'] = TableSettings::getActiveHeaders(AdmModules::GASHAPON_ITEM_MASTER, ActionTypes::CREATE_READONLY, CommonHelpers::myPrivilegeId());

        $data['create_inputs'] = ModuleHeaders::whereIn('header_name', $tableSetting)
        ->where('module_id', AdmModules::GASHAPON_ITEM_MASTER)
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

        return Inertia::render("GashaponItemMasters/GashaponItemMastersCreate", $data);
    }

    public function create(Request $request){

        $request->validate($request->validation);

        $itemValues = json_encode(Arr::except($request->all(), ['validation']));
        try {

            GashaponItemMasterApproval::create([
               'item_values' => json_encode($request->all()),
               'action' => 'CREATE'
            ]);

            GashaponItemMasterHistory::create([
                'item_values' => $itemValues,
                'action' => 'CREATE',
                'status' => 'CREATE'
            ]);
    
    
            return redirect('/gashapon_item_masters')->with(['message' => 'Gashapon Item Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon Item Master', $e->getMessage());
            return back()->with(['message' => 'Gashapon Item Creation Failed!', 'type' => 'error']);
        }
    }

    // ---------------------------------------- UPDATE ITEM ---------------------------------------- //

    public function getUpdate(GashaponItemMaster $item){

        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }

        $data = [];
        $data['page_title'] = 'Gashapon Item Master - Update';

        $data['gashapon_item_master_detail'] = GashaponItemMaster::where('id', $item->id)->with($this->joins)->first();
        
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::GASHAPON_ITEM_MASTER, ActionTypes::UPDATE, CommonHelpers::myPrivilegeId());
        $data['table_setting_read_only'] = TableSettings::getActiveHeaders(AdmModules::GASHAPON_ITEM_MASTER, ActionTypes::UPDATE_READONLY, CommonHelpers::myPrivilegeId());

        $data['update_inputs'] = ModuleHeaders::whereIn('header_name', $tableSetting)
        ->where('module_id', AdmModules::GASHAPON_ITEM_MASTER)
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

        return Inertia::render("GashaponItemMasters/GashaponItemMastersUpdate", $data);
    }

    public function update(Request $request){

        $request->validate($request->validation);
        $itemValues = json_encode(Arr::except($request->all(), ['validation']));
        
        try {

             GashaponItemMasterApproval::updateOrCreate(
                [
                    'action' => 'UPDATE', 
                    'gashapon_item_master_id' => $request->id
                ],
                [
                    'item_values' => $itemValues,
                    'action' => 'UPDATE',
                    'status' => 'FOR APPROVAL',
                    'gashapon_item_master_id' => $request->id,
                ]
            );

             GashaponItemMasterHistory::create([
                'item_values' => $itemValues,
                'action' => 'UPDATE',
                'status' => 'UPDATE',
                'gashapon_item_master_id' => $request->id,
            ]);


            return redirect('/gashapon_item_masters')->with(['message' => 'Gashapon Item Update Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon Item Master', $e->getMessage());
            return back()->with(['message' => 'Gashapon Item Update Failed!', 'type' => 'error']);
        }
    }

    // ---------------------------------------- VIEW ITEM -------------------------------------------//

    public function getView(GashaponItemMaster $item){

        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }

        $data = [];
        $data['page_title'] = 'Gashapon Item Master - Item Details';
        $data['gashapon_item_master_detail'] = GashaponItemMaster::where('id', $item->id)->with($this->joins)->first();

        $tableSetting = TableSettings::getActiveHeaders(AdmModules::GASHAPON_ITEM_MASTER, ActionTypes::VIEW, CommonHelpers::myPrivilegeId());
        $data['table_headers'] = ModuleHeaders::getHeadersByModule(AdmModules::GASHAPON_ITEM_MASTER, $tableSetting);


        return Inertia::render("GashaponItemMasters/GashaponItemMastersView", $data);
    }

    // -------------------------------------- EXPORT --------------------------------------------//

    public function export()
    {

        $data['table_setting'] = explode(',', TableSettings::where('adm_moduls_id', AdmModules::GASHAPON_ITEM_MASTER)
        ->where('action_types_id', ActionTypes::EXPORT)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->pluck('report_header')
        ->first());

        $data['table_headers'] = ModuleHeaders::whereIn('header_name', $data['table_setting'])
        ->where('module_id', AdmModules::GASHAPON_ITEM_MASTER)
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
        
        $filename = "Gashapon Item Masters - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }

    public function importView() {
        $data = [];
        $data['page_title'] = 'Gashapon Item Master - Import';

        return Inertia::render("GashaponItemMasters/GashaponItemMastersImportView", $data);
    }

    public function importGashaponTemplate() {
        
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::GASHAPON_ITEM_MASTER, ActionTypes::IMPORT, CommonHelpers::myPrivilegeId());
        $data['table_headers'] = ModuleHeaders::getHeadersByModule(AdmModules::GASHAPON_ITEM_MASTER, $tableSetting);
        $headers = $data['table_headers']->pluck('header_name')->toArray();
        
        return Excel::download(new ImportTemplate($headers), 'Gashapon Import Template.csv');
    }
    
    public function importGashaponItem(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,text/plain',
        ]);
    
        $path = $request->file('file')->getRealPath();
        $dataExcel = Excel::toArray([], $path);
    
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::GASHAPON_ITEM_MASTER, ActionTypes::IMPORT, CommonHelpers::myPrivilegeId());
        $data['table_headers'] = ModuleHeaders::getHeadersByModule(AdmModules::GASHAPON_ITEM_MASTER, $tableSetting);
    
        $headers = $data['table_headers']->pluck('header_name')->toArray();
        $dbColumns = $data['table_headers']->pluck('name')->toArray();
    
        $uploadedHeaders = array_map('trim', $dataExcel[0][0]);
    
        if ($uploadedHeaders !== $headers) {
            return back()->with(['message' => 'Headers do not match the required format!', 'type' => 'error']);
        }
    
        $dataRows = array_slice($dataExcel[0], 1);
        $jsonItems = []; // Store all valid rows here
    
        // First loop: Validation phase (check all rows before inserting)
        foreach ($dataRows as $key => $row) {
            $jsonItemValues = []; // Reset for each row
            $itemValues = array_combine($dbColumns, $row);
    
            foreach ($itemValues as $itemKey => $value) {
                $tableHeader = $data['table_headers']->where('name', $itemKey)->first();
    
                if (!$tableHeader || is_null($tableHeader->table)) {
                    $jsonItemValues[$itemKey] = $value;
                    continue;
                }
            
                $tableName = $tableHeader->table;
                $labelColumn = $tableHeader->table_select_label;
                $headerName = $tableHeader->header_name;
            
                $description = DB::table($tableName)->where($labelColumn, $value)->value($labelColumn);
                $itemId = DB::table($tableName)->where($labelColumn, $value)->value('id');
                
                if ($description === null) {
                    return back()->with([
                        'message' => 'Line ' . ($key + 2) . ' with value ' . $value . ' in ' . $headerName . ' is not found in submaster',
                        'type' => 'error'
                    ]);
                }
    
                $jsonItemValues[$itemKey] = $itemId;
            }
    
            // Store the valid row for insertion later
            $jsonItems[] = json_encode($jsonItemValues, JSON_PRETTY_PRINT);
        }
    
        // Second loop: Insertion phase (only if validation passed)
        foreach ($jsonItems as $jsonItemValues) {
            GashaponItemMasterApproval::create([
                'item_values' => $jsonItemValues,
                'action' => 'CREATE'
            ]);
    
            GashaponItemMasterHistory::create([
                'item_values' => $jsonItemValues,
                'action' => 'CREATE',
                'status' => 'CREATE'
            ]);
        }
    
        return back()->with(['message' => 'File uploaded successfully!', 'type' => 'success']);
    }
    
    
}