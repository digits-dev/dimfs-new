<?php

namespace App\Http\Controllers\ItemMasterModuleImports;

use app\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

}
