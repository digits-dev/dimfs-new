<?php

namespace App\Http\Controllers\ItemMasterModuleImports;

use App\Exports\ImportTemplate;
use app\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ActionTypes;
use App\Models\AdmModels\AdmModules;
use App\Models\ModuleHeaders;
use App\Models\TableSettings;
use Illuminate\Http\Request;
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

    public function importItemMasterTemplate() {
        
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, ActionTypes::IMPORT, CommonHelpers::myPrivilegeId());
        $data['table_headers'] = ModuleHeaders::getHeadersByModule(AdmModules::ITEM_MASTER, $tableSetting);
        $headers = $data['table_headers']->pluck('header_name')->toArray();
        
        return Excel::download(new ImportTemplate($headers), 'Item Master Template.csv');
    }

    public function importSkuLegendTemplate() {
        
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, ActionTypes::IMPORT_SKU_LEGEND, CommonHelpers::myPrivilegeId());
        $data['table_headers'] = ModuleHeaders::getHeadersByModule(AdmModules::ITEM_MASTER, $tableSetting);
        $headers = $data['table_headers']->pluck('header_name')->toArray();
        
        return Excel::download(new ImportTemplate($headers), 'SKU Legend-Segmentation Template.csv');
    }

    public function importSkuStatusTemplate() {
        
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, ActionTypes::IMPORT_SKU_STATUS, CommonHelpers::myPrivilegeId());
        $data['table_headers'] = ModuleHeaders::getHeadersByModule(AdmModules::ITEM_MASTER, $tableSetting);
        $headers = $data['table_headers']->pluck('header_name')->toArray();
        
        return Excel::download(new ImportTemplate($headers), 'SKU Status-Segmentation Template.csv');
    }

    public function importWrrDateTemplate() {
        
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, ActionTypes::IMPORT_WRR_DATE, CommonHelpers::myPrivilegeId());
        $data['table_headers'] = ModuleHeaders::getHeadersByModule(AdmModules::ITEM_MASTER, $tableSetting);
        $headers = $data['table_headers']->pluck('header_name')->toArray();
        
        return Excel::download(new ImportTemplate($headers), 'WRR Date Template.csv');
    }

    public function importEcomDetailsTemplate() {
        
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, ActionTypes::IMPORT_ECOM_DETAILS, CommonHelpers::myPrivilegeId());
        $data['table_headers'] = ModuleHeaders::getHeadersByModule(AdmModules::ITEM_MASTER, $tableSetting);
        $headers = $data['table_headers']->pluck('header_name')->toArray();
        
        return Excel::download(new ImportTemplate($headers), 'ECOM Details Template.csv');
    }

    public function importItemMasterAccountingTemplate() {
        
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, ActionTypes::IMPORT_ACCOUNTING, CommonHelpers::myPrivilegeId());
        $data['table_headers'] = ModuleHeaders::getHeadersByModule(AdmModules::ITEM_MASTER, $tableSetting);
        $headers = $data['table_headers']->pluck('header_name')->toArray();
        
        return Excel::download(new ImportTemplate($headers), 'Item Master (Accounting) Template.csv');
    }

    public function importItemMasterMcbTemplate() {
        
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::ITEM_MASTER, ActionTypes::IMPORT_MCB, CommonHelpers::myPrivilegeId());
        $data['table_headers'] = ModuleHeaders::getHeadersByModule(AdmModules::ITEM_MASTER, $tableSetting);
        $headers = $data['table_headers']->pluck('header_name')->toArray();
        
        return Excel::download(new ImportTemplate($headers), 'Item Master (MCB) Template.csv');
    }

}
