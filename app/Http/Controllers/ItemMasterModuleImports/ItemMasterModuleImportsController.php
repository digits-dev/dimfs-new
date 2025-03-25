<?php

namespace App\Http\Controllers\ItemMasterModuleImports;

use App\Exports\ImportTemplate;
use app\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ActionTypes;
use App\Models\AdmModels\AdmModules;
use App\Models\ItemMaster;
use App\Models\ItemMasterAccountingApproval;
use App\Models\ItemMasterApproval;
use App\Models\ItemMasterHistory;
use App\Models\ItemSegmentations;
use App\Models\ModuleHeaders;
use App\Models\Segmentations;
use App\Models\SkuLegends;
use App\Models\TableSettings;
use Carbon\Carbon;
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
    
    public function importItemMasterAccountingTemplate() {
        
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER_APPROVAL_ACCOUNTING, ActionTypes::IMPORT_ACCOUNTING, CommonHelpers::myPrivilegeId());
        $headers = ModuleHeaders::getHeadersByModule(AdmModules::ITEM_MASTER_APPROVAL_ACCOUNTING, $tableSetting)->pluck('header_name')->toArray();
        return Excel::download(new ImportTemplate($headers), 'Item Master (Accounting) Template.csv');
        
    }
    
    public function importItemMasterMcbTemplate() {
        return $this->importTemplate(ActionTypes::IMPORT_MCB, 'Item Master (MCB) Template.csv');
    }

    public function importWrrDateTemplate() {
        return $this->importTemplate(ActionTypes::IMPORT_WRR_DATE, 'Item Master WRR Date Template.csv');
    }

    public function importSkuLegendTemplate() {
        $headers = Segmentations::where('status', 'ACTIVE')->pluck('import_header_name')->toArray();
        array_unshift($headers, 'DIGITS CODE', 'SKU LEGEND');
    
        return Excel::download(new ImportTemplate($headers), 'SKU Legend Segmentation Template.csv');
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

        if (empty($dataRows)) {
            return back()->with(['message' => 'Fields should not be Empty', 'type' => 'error']);
        }
       
        foreach ($dataRows as $key => $row) {
            $jsonItemValues = []; // Reset for each row
            $itemValues = array_combine($dbColumns, $row);

            
    
            foreach ($itemValues as $itemKey => $value) {
                $tableHeader = $table_headers->where('name', $itemKey)->first();
                $tableName = $tableHeader->table;
                $labelColumn = $tableHeader->table_select_label;
                $headerName = $tableHeader->header_name;

                // NOT NULLABLE
                if ($value === null || $value === '') {
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

    public function importItemMasterItemAccounting(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,text/plain',
        ]);
    
        $path = $request->file('file')->getRealPath();
        $dataExcel = Excel::toArray([], $path);
    
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER_APPROVAL_ACCOUNTING, ActionTypes::IMPORT_ACCOUNTING, CommonHelpers::myPrivilegeId());
        $table_headers = ModuleHeaders::getHeadersByModule(AdmModules::ITEM_MASTER_APPROVAL_ACCOUNTING, $tableSetting);
    
        $headers = $table_headers->pluck('header_name')->toArray();
        $dbColumns = $table_headers->pluck('name')->toArray();
    
        $uploadedHeaders = array_map('trim', $dataExcel[0][0]);

        
        if ($uploadedHeaders !== $headers) {
            return back()->with(['message' => 'Headers do not match the required format!', 'type' => 'error']);
        }
    
        $dataRows = array_slice($dataExcel[0], 1);
        $jsonItems = [];

        if (empty($dataRows)) {
            return back()->with(['message' => 'Fields should not be Empty', 'type' => 'error']);
        }
        
        foreach ($dataRows as $key => $row) {
            
            $numericKeys = ['store_cost', 'ecom_store_cost', 'landed_cost', 'actual_landed_cost', 'landed_cost_sea', 'working_store_cost', 'ecom_working_store_cost', 'working_landed_cost'];
            $itemValues = array_combine($dbColumns, $row);
            $itemMaster = ItemMaster::with(['getVendorType', 'getMarginCategory'])->where('digits_code', $itemValues['digits_code'])->first();

    
            foreach ($numericKeys as $numKey) {
                if (isset($itemValues[$numKey])) {
                    $itemValues[$numKey] = (float) str_replace(',', '', $itemValues[$numKey]);
                }
            }

            // VALIDATIONS 
            
            // EXISTING DIGITS CODE
            if(!$itemMaster){
                return back()->with([
                    'message' => 'Line ' . ($key + 2) . ' Digits Code: ' . $itemValues['digits_code'] . ' not exist ',
                    'type' => 'error'
                ]);
            }

            // EFFECTIVE DATE FORMAT
            $date = \DateTime::createFromFormat('Y-m-d', $itemValues['effective_date']);
            if (!$date || $date->format('Y-m-d') !== $itemValues['effective_date']) {
                return back()->with([
                    'message' => 'Effective Date format should be a valid date in "YYYY-MM-DD" format',
                    'type' => 'error'
                ]);
            }
         
            $storeCostPercentage = ItemMasterAccountingApproval::calculateCostPercentage($itemValues['store_cost'], $itemMaster);
            $ecommStoreCostPercentage = ItemMasterAccountingApproval::calculateCostPercentage($itemValues['ecom_store_cost'], $itemMaster);
            
            $vendorType = $itemMaster->getVendorType;
            $marginCategory = $itemMaster->getMarginCategory;

            $jsonItemValues = [
                "item_masters_id" => $itemMaster->id,
                "brands_id" => $itemMaster->brands_id,
                "categories_id" => $itemMaster->categories_id,
                "margin_categories_id" => $itemMaster->margin_categories_id,
                "support_types_id" => $itemMaster->support_types_id,
                "current_srp" => $itemMaster->current_srp,
                "promo_srp" => $itemMaster->promo_srp,
                "duration_from" => $itemMaster->duration_from,
                "duration_to" => $itemMaster->duration_to,
                "encoder_privileges_id" => CommonHelpers::myPrivilegeId(),
                "created_by" => CommonHelpers::myId(),
                "store_cost_percentage" => $storeCostPercentage,
                "working_store_cost_percentage" => ItemMasterAccountingApproval::calculateCostPercentage($itemValues['working_store_cost'], $itemMaster),
                "ecom_store_cost_percentage" => $ecommStoreCostPercentage,
                "ecom_working_store_cost_percentage" => ItemMasterAccountingApproval::calculateCostPercentage($itemValues['ecom_working_store_cost'], $itemMaster),
                
            ];

            foreach ($itemValues as $itemKey => $value) {
                $tableHeader = $table_headers->where('name', $itemKey)->first();
                $tableName = $tableHeader->table;
                $labelColumn = $tableHeader->table_select_label;
                $headerName = $tableHeader->header_name;

                // NOT NULLABLE
                if ($value === null || $value === ''){
                    return back()->with([
                        'message' => 'Line ' . ($key + 2) . ' with column of ' . $headerName . ' can\'t be null or blank',
                        'type' => 'error'
                    ]);
                }

                // FOR COSTS TYPE CHECK
                $costs = [
                    'store_cost',
                    'ecom_store_cost', 
                    'landed_cost', 
                    'actual_landed_cost', 
                    'landed_cost_sea', 
                    'working_store_cost', 
                    'ecom_working_store_cost', 
                    'working_landed_cost'
                ];

                if (in_array($itemKey, $costs)) {
                    if (!is_numeric($value)) {
                        return back()->with([
                            'message' => $headerName . ' must be a valid number or decimal on ' . 'Line ' . ($key + 2),
                            'type' => 'error'
                        ]);
                    }
                }

                if(isset($itemValues['working_store_cost']) && isset($itemValues['working_landed_cost']) && isset($itemValues['ecom_working_store_cost'])) {
                    
                    if($marginCategory->margin_category_description == "UNITS"){
    
                        $checkUntWCost = ItemMasterAccountingApproval::checkUntWorkingStoreCost($itemValues, $itemMaster);
                        if($checkUntWCost == 1){
                            return back()->with([
                                'message' => 'Line '. ($key + 2) .': with digits code "'. $itemValues['digits_code'] .'" check Working Store Cost.',
                                'type' => 'error'
                            ]);
                        }

                    }
                    
                    elseif($marginCategory->margin_category_description == "ACCESSORIES"){
                    
                        $checkAccWCost = ItemMasterAccountingApproval::checkAccWorkingStoreCost($itemValues, $itemMaster, $storeCostPercentage);
                        if($checkAccWCost == 1){
                            return back()->with([
                                'message' => 'Line '. ($key + 2) .': with digits code "'. $itemValues['digits_code'] .'" check Working Store Cost.',
                                'type' => 'error'
                            ]);
                        }
                        
                        $checkEcomAccWCost = ItemMasterAccountingApproval::checkAccEcomWorkingStoreCost($itemValues, $itemMaster, $ecommStoreCostPercentage);
                        if($checkEcomAccWCost == 1){
                            return back()->with([
                                'message' => 'Line '. ($key + 2) .': with digits code "'. $itemValues['digits_code'] .'" check ECOMM Working Store Cost.',
                                'type' => 'error'
                            ]);
                        }

                    }
                }


                // STORE COST CHECKING
                $vendor_type = ["LOC-CON","LOC-OUT","LR-CON","LR-OUT"];
                    
                if(in_array($vendorType->vendor_type_code,$vendor_type)){
                   
                    $checkLocalCost = ItemMasterAccountingApproval::checkLocalStoreCost($itemValues, $itemMaster);

                    if($checkLocalCost == 1){
                        return back()->with([
                            'message' => 'Line '. ($key + 2) .': with digits code "'. $itemValues['digits_code'] .'" check store cost.',
                            'type' => 'error'
                        ]);
                    }
                }
                elseif($marginCategory->margin_category_description == "UNITS"){
           
                    $checkUntCost = ItemMasterAccountingApproval::checkUnitStoreCost($itemValues, $itemMaster);
                    if($checkUntCost == 1){
                        return back()->with([
                            'message' => 'Line '. ($key + 2) .': with digits code "'. $itemValues['digits_code'] .'" check store cost.',
                            'type' => 'error'
                        ]);
                    }
                    
                }
                
                elseif($marginCategory->margin_category_description == "ACCESSORIES"){

                    $checkAccCost = ItemMasterAccountingApproval::checkAccStoreCost($itemValues, $itemMaster, $storeCostPercentage);
                    if($checkAccCost == 1){
                        return back()->with([
                            'message' => 'Line '. ($key + 2) .': with digits code "'. $itemValues['digits_code'] .'" check store cost.',
                            'type' => 'error'
                        ]);
                    }
                }

                // ADDING UPLOADED DATA

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


            $jsonItems[] = $jsonItemValues;

        }

        try {

            DB::beginTransaction();

            foreach ($jsonItems as $jsonItemValues) {
                ItemMasterAccountingApproval::create([
                    'item_masters_id' => $jsonItemValues['item_masters_id'],
                    'brands_id' => $jsonItemValues['brands_id'],
                    'categories_id' => $jsonItemValues['categories_id'],
                    'margin_categories_id' => $jsonItemValues['margin_categories_id'],
                    'support_types_id' => $jsonItemValues['support_types_id'],
                    'current_srp' => $jsonItemValues['current_srp'],
                    'promo_srp' => $jsonItemValues['promo_srp'],
                    'duration_from' => $jsonItemValues['duration_from'],
                    'duration_to' => $jsonItemValues['duration_to'],
                    'encoder_privileges_id' => $jsonItemValues['encoder_privileges_id'],
                    'store_cost_percentage' => $jsonItemValues['store_cost_percentage'],
                    'working_store_cost_percentage' => $jsonItemValues['working_store_cost_percentage'],
                    'ecom_store_cost_percentage' => $jsonItemValues['ecom_store_cost_percentage'],
                    'ecom_working_store_cost_percentage' => $jsonItemValues['ecom_working_store_cost_percentage'],
                    'store_cost' => $jsonItemValues['store_cost'],
                    'ecom_store_cost' => $jsonItemValues['ecom_store_cost'],
                    'landed_cost' => $jsonItemValues['landed_cost'],
                    'actual_landed_cost' => $jsonItemValues['actual_landed_cost'],
                    'landed_cost_sea' => $jsonItemValues['landed_cost_sea'],
                    'working_store_cost' => $jsonItemValues['working_store_cost'],
                    'ecom_working_store_cost' => $jsonItemValues['ecom_working_store_cost'],
                    'working_landed_cost' => $jsonItemValues['working_landed_cost'],
                    'effective_date' => $jsonItemValues['effective_date'],
                    'created_by' => $jsonItemValues['created_by'],
                    'encoder_privileges_id' => CommonHelpers::myPrivilegeId(),
            
                ]);
        
            }

            DB::commit();

            return back()->with(['message' => 'File uploaded successfully!', 'type' => 'success']);

        }

        catch (\Exception $e) {

            DB::rollBack();
            CommonHelpers::LogSystemError('Item Master Accounting Export', $e->getMessage());
            return back()->with(['message' => 'File uploading failed', 'type' => 'error']);
        }
    
    }

    public function importItemMasterWrrDate(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,text/plain',
        ]);
    
        $path = $request->file('file')->getRealPath();
        $dataExcel = Excel::toArray([], $path);
    
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, ActionTypes::IMPORT_WRR_DATE, CommonHelpers::myPrivilegeId());
        $table_headers = ModuleHeaders::getHeadersByModule(AdmModules::ITEM_MASTER, $tableSetting);
    
        $headers = $table_headers->pluck('header_name')->toArray();
        $dbColumns = $table_headers->pluck('name')->toArray();
    
        $uploadedHeaders = array_map('trim', $dataExcel[0][0]);

        
        if ($uploadedHeaders !== $headers) {
            return back()->with(['message' => 'Headers do not match the required format!', 'type' => 'error']);
        }
    
        $dataRows = array_slice($dataExcel[0], 1);
        $jsonItems = [];

        if (empty($dataRows)) {
            return back()->with(['message' => 'Fields should not be Empty', 'type' => 'error']);
        }
        
        foreach ($dataRows as $key => $row) {
            
            $itemValues = array_combine($dbColumns, $row);
            $itemMaster = ItemMaster::where('digits_code', $itemValues['digits_code'])->first();
    
            // VALIDATIONS 
            
            // EXISTING DIGITS CODE
            if(!$itemMaster){
                return back()->with([
                    'message' => 'Line ' . ($key + 2) . ' Digits Code: ' . $itemValues['digits_code'] . ' not exist ',
                    'type' => 'error'
                ]);
            }

            // EFFECTIVE DATE FORMAT
            $date = \DateTime::createFromFormat('Y-m-d', $itemValues['latest_wrr_date']);
            if (!$date || $date->format('Y-m-d') !== $itemValues['latest_wrr_date']) {
                return back()->with([
                    'message' => 'Latest WRR Date format should be a valid date in "YYYY-MM-DD" format',
                    'type' => 'error'
                ]);
            }
         

            foreach ($itemValues as $itemKey => $value) {
                $tableHeader = $table_headers->where('name', $itemKey)->first();
                $tableName = $tableHeader->table;
                $labelColumn = $tableHeader->table_select_label;
                $headerName = $tableHeader->header_name;

                // NOT NULLABLE
                if ($value === null || $value === ''){
                    return back()->with([
                        'message' => 'Line ' . ($key + 2) . ' with column of ' . $headerName . ' can\'t be null or blank',
                        'type' => 'error'
                    ]);
                }
                


                if ($itemKey === 'latest_wrr_date'){
                    if(empty($itemMaster->initial_wrr_date) || is_null($itemMaster->initial_wrr_date)){
                        $jsonItemValues = [
                            'digits_code' => $itemMaster->digits_code,
                            'initial_wrr_date' => date('Y-m-d', strtotime((string)$value)),
                            'latest_wrr_date' => date('Y-m-d', strtotime((string)$value)),
                            'updated_by' => CommonHelpers::myId()
                            
                        ];        
                    }
                    else{
                        $jsonItemValues = [
                            'digits_code' => $itemMaster->digits_code,
                            'latest_wrr_date' => self::getLatestWRRDate($itemMaster['digits_code'], $value),
                            'updated_by' => CommonHelpers::myId()
                        ]; 
                    
                    }
                
                }

            }

            $jsonItems[] = $jsonItemValues;

        }

        try {

            DB::beginTransaction();

            foreach ($jsonItems as $jsonItemValues) {
                ItemMaster::where('digits_code', $jsonItemValues['digits_code'])->update($jsonItemValues);
            }

            DB::commit();

            return back()->with(['message' => 'File uploaded successfully!', 'type' => 'success']);

        }

        catch (\Exception $e) {

            DB::rollBack();
            CommonHelpers::LogSystemError('Item Master WRR Date Export', $e->getMessage());
            return back()->with(['message' => 'File uploading failed', 'type' => 'error']);
        }
        
    
    }

    public function getLatestWRRDate($digits_code, $latest_wrr_date)
	{
		$data = "";
		$existingItemLatestWRR = ItemMaster::where('digits_code', $digits_code)->value('latest_wrr_date');
		$first = new Carbon((string)$existingItemLatestWRR);
		$second = new Carbon((string)$latest_wrr_date);
		
		if($first->gte($second)){
			$data = $existingItemLatestWRR;
		}
		elseif(!is_null($latest_wrr_date)){
			$data = date('Y-m-d', strtotime((string)$latest_wrr_date));
		}
		else{
			$data = $existingItemLatestWRR;
		}
		return $data;
	}

    public function importItemMasterItemMcb(Request $request){

        $request->validate([
            'file' => 'required|mimes:csv,txt,text/plain',
        ]);
    
        $path = $request->file('file')->getRealPath();
        $dataExcel = Excel::toArray([], $path);
    
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, ActionTypes::IMPORT_MCB, CommonHelpers::myPrivilegeId());
        $table_headers = ModuleHeaders::getHeadersByModule(AdmModules::ITEM_MASTER, $tableSetting);
    
        $headers = $table_headers->pluck('header_name')->toArray();
        $dbColumns = $table_headers->pluck('name')->toArray();
    
        $uploadedHeaders = array_map('trim', $dataExcel[0][0]);

        
        if ($uploadedHeaders !== $headers) {
            return back()->with(['message' => 'Headers do not match the required format!', 'type' => 'error']);
        }
    
        $dataRows = array_slice($dataExcel[0], 1);
        $jsonItems = [];
        $digitsCodes = [];

        if (empty($dataRows)) {
            return back()->with(['message' => 'Fields should not be Empty', 'type' => 'error']);
        }
       
        foreach ($dataRows as $key => $row) {
            $jsonItemValues = []; // Reset for each row
            $itemValues = array_combine($dbColumns, $row);

            $itemMaster = ItemMaster::where('digits_code', $itemValues['digits_code'])->first();

            if (!$itemMaster){
                return back()->with(['message' => 'Digits Code in Line ' . ($key + 2) . ' not found', 'type' => 'error']);
            }

            $existingUPC = ItemMaster::where('upc_code', $itemValues['upc_code'])
            ->where('id', '!=', optional($itemMaster)->id)
            ->exists();

            if ($existingUPC) {
                // UPC code already exists for another item
                return back()->with(['message' => 'UPC code in Line ' . ($key + 2) . ' is already in use by another item', 'type' => 'error']);
            }
            elseif ($itemMaster && $itemMaster->upc_code == $itemValues['upc_code']) {
                // The entered UPC is the same as the existing one
                return back()->with([
                    'message' => 'UPC code in Line ' . ($key + 2) . ' is the same as the original. No need to update.', 
                    'type' => 'error'
                ]);
            }


            if ($itemValues['classifications_id'] != null || $itemValues['sub_classifications_id'] != null || $itemValues['margin_categories_id'] != null || $itemValues['categories_id']){
                if ($itemValues['margin_categories_id'] == null){
                    return back()->with(['message' => 'Margin Category Description in Line ' . ($key + 2) . ' should not be null or blank', 'type' => 'error']);
                }
                if ($itemValues['categories_id'] == null){
                    return back()->with(['message' => 'Category Description in Line ' . ($key + 2) . ' should not be null or blank', 'type' => 'error']);
                }
                if ($itemValues['classifications_id'] == null){
                    return back()->with(['message' => 'Class Description in Line ' . ($key + 2) . ' should not be null or blank', 'type' => 'error']);
                }
                if ($itemValues['sub_classifications_id'] == null){
                    return back()->with(['message' => 'Subclass Description in Line ' . ($key + 2) . ' should not be null or blank', 'type' => 'error']);
                }
            }


            foreach ($itemValues as $itemKey => $value) {
                $tableHeader = $table_headers->where('name', $itemKey)->first();
                $tableName = $tableHeader->table;
                $labelColumn = $tableHeader->table_select_label;
                $headerName = $tableHeader->header_name;

                // NOT NULLABLE
                if ($value === null || $value === '') {
                   continue;
                }

                if ($itemKey == 'digits_code'){
                    if (in_array($value, $digitsCodes)) {
                        return back()->with([
                            'message' => 'Duplicate Digits Code found: ' . $value . ' in Line ' . ($key + 2),
                            'type' => 'error'
                        ]);
                    }

                    $digitsCodes[] = $value;
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
            $jsonItems[] = [
                "item_values" => json_encode($jsonItemValues, JSON_PRETTY_PRINT),
                "item_master_id" => $itemMaster->id
            ];
                    
        }
    
      
        try {

            DB::beginTransaction();

            foreach ($jsonItems as $jsonItemValues) {

                ItemMasterApproval::create([
                    'item_values' => $jsonItemValues['item_values'],
                    'item_master_id' => $jsonItemValues['item_master_id'],
                    'action' => 'UPDATE'
                ]);
        
                ItemMasterHistory::create([
                    'item_values' => $jsonItemValues['item_values'],
                    'action' => 'UPDATE',
                    'status' => 'UPDATE'
                ]);
            }

            DB::commit();

            return back()->with(['message' => 'File uploaded successfully!', 'type' => 'success']);

        }

        catch (\Exception $e) {

            DB::rollBack();
            CommonHelpers::LogSystemError('Item Master MCB Export', $e->getMessage());
            return back()->with(['message' => 'File uploading failed', 'type' => 'error']);
        }
    
    }

    public function importItemMasterSkuSegmentation(Request $request){

        $request->validate([
            'file' => 'required|mimes:csv,txt,text/plain',
        ]);
    
        $path = $request->file('file')->getRealPath();
        $dataExcel = Excel::toArray([], $path);
    
        $headers = Segmentations::where('status', 'ACTIVE')->pluck('import_header_name')->toArray();
        array_unshift($headers, 'DIGITS CODE', 'SKU LEGEND');
    
        $uploadedHeaders = array_map('trim', $dataExcel[0][0]);

        
        if ($uploadedHeaders !== $headers) {
            return back()->with(['message' => 'Headers do not match the required format!', 'type' => 'error']);
        }
    
        $dataRows = array_slice($dataExcel[0], 1);
        $importData = [];
        $digitsCodes = [];

        if (empty($dataRows)) {
            return back()->with(['message' => 'Fields should not be Empty', 'type' => 'error']);
        }
 
        foreach ($dataRows as $key => $row) {
            $itemArray = [];
           

            $itemValues = array_combine($headers, $row);
            $itemMaster = ItemMaster::where('digits_code', $itemValues['DIGITS CODE'])->first();

            if (!$itemMaster){
                return back()->with(['message' => 'Digits Code in Line ' . ($key + 2) . ' not found', 'type' => 'error']);
            }

            foreach ($itemValues as $itemKey => $value) {

                // NOT NULLABLE
                if ($value === null || $value === '') {
                    return back()->with([
                        'message' => 'Line ' . ($key + 2) . ' with column of ' . $itemKey . ' can\'t be null or blank',
                        'type' => 'error'
                    ]);
                }
 
                if ($itemKey == 'DIGITS CODE'){
                    if (in_array($value, $digitsCodes)) {
                        return back()->with([
                            'message' => 'Duplicate DIGITS CODE found: ' . $value . ' in Line ' . ($key + 2),
                            'type' => 'error'
                        ]);
                    }

                    $digitsCodes[] = $value;
                    $itemArray['item_master_id'] = $itemMaster->id;
                }
                else {
                    $sku_legend_id = SkuLegends::where('sku_legend_description', $value)
                        ->where('status', 'ACTIVE')
                        ->value('id');
                
                    if (!$sku_legend_id) {
                        return back()->with([
                            'message' => ($itemKey == 'SKU LEGEND' ? 'SKU Legend ' : 'Header ' . $itemKey . ' with the value ') . 
                                        $value . ' in Line ' . ($key + 2) . ' not found in the submaster',
                            'type' => 'error'
                        ]);
                    }
                
                    if ($itemKey == 'SKU LEGEND') {
                        $itemArray['item_sku_legend_id'] = $sku_legend_id;
                    } 
                    else {
                    
                        $segmentation_id = Segmentations::where('import_header_name', $itemKey)
                            ->where('status', 'ACTIVE')
                            ->value('id');
                
                        if (!isset($itemArray['segmentations'])) {
                            $itemArray['segmentations'] = [];
                        }
                
                        $itemArray['segmentations'][$itemKey] = [
                            "sku_legend_id" => $sku_legend_id,
                            "segmentation_id" => $segmentation_id
                        ];
                    }
                }
              
            }
    
            $importData[] = $itemArray;
                    
        }
    
        try {

            DB::beginTransaction();

            foreach ($importData as $data) {

                $itemMaster = ItemMaster::find($data['item_master_id']);

                $itemMaster->sku_legends_id = $data['item_sku_legend_id'];

                foreach($data['segmentations'] as $segmentation)
                {
                    if (is_array($segmentation) && isset($segmentation['segmentation_id'], $segmentation['sku_legend_id'])) {
                        ItemSegmentations::updateOrCreate(
                            [
                                'item_masters_id' => $data['item_master_id'],
                                'segmentations_id' => $segmentation['segmentation_id'],
                            ],
                            [
                                'sku_legend_id' => $segmentation['sku_legend_id'],
                            ]
                        );
                    }
                }

                $itemMaster->save();
            }

            DB::commit();

            return back()->with(['message' => 'File uploaded successfully!', 'type' => 'success']);

        }

        catch (\Exception $e) {

            DB::rollBack();
            CommonHelpers::LogSystemError('Item Master MCB Export', $e->getMessage());
            return back()->with(['message' => 'File uploading failed', 'type' => 'error']);
        }
    
    }

}
