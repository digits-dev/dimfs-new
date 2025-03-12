<?php

namespace App\Http\Controllers\ItemMasterModuleImports;

use App\Exports\ImportTemplate;
use app\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ActionTypes;
use App\Models\AdmModels\AdmModules;
use App\Models\ItemMaster;
use App\Models\ItemMasterApproval;
use App\Models\ItemMasterHistory;
use App\Models\ModuleHeaders;
use App\Models\TableSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ItemMasterModuleImportsController extends Controller
{

    // ------------------------------------------ VIEWS -------------------------------------------------------//

    public function getImportModules(){
        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }

        $data = [];
        $data['page_title'] = 'Item Master - Import Modules';
        return Inertia::render("ItemMasters/ItemMasterImportModules", $data);

    }

    public function getItemMasterImport(){
        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }
    
        $data = [];
        $data['page_title'] = 'Item Master - Import';
        return Inertia::render("ItemMasters/ImportModules/ItemMasterBulkImport", $data);
    
    }

    public function getItemMasterSkuLegendImport(){
        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }
    
        $data = [];
        $data['page_title'] = 'Item Master - SKU Legend/Segmentation Bulk Import';
        return Inertia::render("ItemMasters/ImportModules/ItemMasterSkuLegendImport", $data);
    }

    public function getItemMasterSkuStatusImport(){
        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }
    
        $data = [];
        $data['page_title'] = 'Item Master - SKU Status/Segmentation Bulk Import';
        return Inertia::render("ItemMasters/ImportModules/ItemMasterSkuStatusImport", $data);
    }

    public function getItemMasterWrrDateImport(){
        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }
    
        $data = [];
        $data['page_title'] = 'Item Master - WRR Date Bulk Import';
        return Inertia::render("ItemMasters/ImportModules/ItemMasterWrrDateBulkImport", $data);
    }

    public function getItemEcomDetailsImport(){
        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }
    
        $data = [];
        $data['page_title'] = 'Item Master - ECOM Details Bulk Import';
        return Inertia::render("ItemMasters/ImportModules/ItemMasterEcomDetailsImport", $data);
    }

    public function getItemMasterAccountingImport(){
        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }
    
        $data = [];
        $data['page_title'] = 'Item Master Bulk Import (Accounting)';
        return Inertia::render("ItemMasters/ImportModules/ItemMasterAccountingBulkImport", $data);
    }

    public function getItemMasterMcbImport(){
        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }
    
        $data = [];
        $data['page_title'] = 'Item Master Bulk Import (MCB)';
        return Inertia::render("ItemMasters/ImportModules/ItemMasterMcbBulkImport", $data);
    }


    // ----------------------------------------- IMPORT TEMPLATES ---------------------------------------//

    public function importTemplate($actionType, $fileName) {
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, $actionType, CommonHelpers::myPrivilegeId());
        $headers = ModuleHeaders::getHeadersByModule(AdmModules::ITEM_MASTER, $tableSetting)->pluck('header_name')->toArray();
    
        return Excel::download(new ImportTemplate($headers), $fileName);
    }
    
    public function importItemMasterTemplate() {
        return $this->importTemplate(ActionTypes::IMPORT, 'Item Master Template.csv');
    }
    
    public function importSkuLegendTemplate() {
        return $this->importTemplate(ActionTypes::IMPORT_SKU_LEGEND, 'SKU Legend-Segmentation Template.csv');
    }
    
    public function importWrrDateTemplate() {
        return $this->importTemplate(ActionTypes::IMPORT_WRR_DATE, 'WRR Date Template.csv');
    }
    
    public function importItemMasterAccountingTemplate() {
        return $this->importTemplate(ActionTypes::IMPORT_ACCOUNTING, 'Item Master (Accounting) Template.csv');
    }
    
    public function importItemMasterMcbTemplate() {
        return $this->importTemplate(ActionTypes::IMPORT_MCB, 'Item Master (MCB) Template.csv');
    }
    
    // ------------------------------------------- IMPORT ------------------------------------------------//

    public function importItemMasterItem(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,text/plain',
        ]);
    
        $path = $request->file('file')->getRealPath();
        $dataExcel = Excel::toArray([], $path);
    
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, ActionTypes::IMPORT, CommonHelpers::myPrivilegeId());
        $table_headers = ModuleHeaders::getHeadersByModule(AdmModules::ITEM_MASTER, $tableSetting);
    
        $headers = $table_headers->pluck('header_name')->toArray();
        $dbColumns = $table_headers->pluck('name')->toArray();
    
        $uploadedHeaders = array_map('trim', $dataExcel[0][0]);

        
        if ($uploadedHeaders !== $headers) {
            return back()->with(['message' => 'Headers do not match the required format!', 'type' => 'error']);
        }
    
        $dataRows = array_slice($dataExcel[0], 1);
        $jsonItems = [];
       
        foreach ($dataRows as $key => $row) {
            $jsonItemValues = []; // Reset for each row
            $itemValues = array_combine($dbColumns, $row);
    
            foreach ($itemValues as $itemKey => $value) {
                $tableHeader = $table_headers->where('name', $itemKey)->first();
                $tableName = $tableHeader->table;
                $labelColumn = $tableHeader->table_select_label;
                $headerName = $tableHeader->header_name;

                // NOT NULLABLE
                if (empty($value)){
                    return back()->with([
                        'message' => 'Line ' . ($key + 2) . ' with column of ' . $headerName . ' can\'t be null or blank',
                        'type' => 'error'
                    ]);
                }

                // EXISTING UPC CODE
                if ($itemKey == 'upc_code'){
                    if(ItemMaster::where('upc_code', $value)->exists()){
                        return back()->with([
                            'message' => 'Line ' . ($key + 2) . ' with value ' . $value . ' in ' . $headerName . ' exists',
                            'type' => 'error'
                        ]);
                    }
                }

                // ITEM MASTER DESCRIPTION EXCEEDING LENGTH
                if ($itemKey == 'item_description'){
                    if(strlen($value) > 60){
						return back()->with([
                            'message' => 'Line ' . ($key + 2) . ' in ' . $headerName . ' exceed 60 characters',
                            'type' => 'error'
                        ]);
					}
                }

                // WRONG APPLE REPORT INCLUSION
                if ($itemKey == 'apple_report_inclusion'){
                    if($value != 0 && $value != 1){
						return back()->with([
                            'message' => 'Line ' . ($key + 2) . ' with value ' . $value . ' in ' . $headerName . ' have Invalid Value',
                            'type' => 'error'
                        ]);
					}
                }

                if (!$tableHeader || is_null($tableHeader->table)) {
                    $jsonItemValues[$itemKey] = $value;
                    continue;
                }
            
                $description = DB::table($tableName)->where($labelColumn, $value)->where('status', 'ACTIVE')->value($labelColumn);
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
            ItemMasterApproval::create([
                'item_values' => $jsonItemValues,
                'action' => 'CREATE'
            ]);
    
            ItemMasterHistory::create([
                'item_values' => $jsonItemValues,
                'action' => 'CREATE',
                'status' => 'CREATE'
            ]);
        }
    
        return back()->with(['message' => 'File uploaded successfully!', 'type' => 'success']);
    }

}
