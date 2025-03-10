<?php

namespace App\Http\Controllers\ItemMasterModuleImports;

use app\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ItemMasterModuleImportsController extends Controller
{
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
        $data['page_title'] = 'Item Master - Import Modules';
        return Inertia::render("ItemMasters/ImportModules/ItemMasterBulkImport", $data);
    
    }

}
