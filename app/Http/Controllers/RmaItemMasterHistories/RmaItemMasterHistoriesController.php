<?php

namespace App\Http\Controllers\RmaItemMasterHistories;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ActionTypes;
use App\Models\AdmModels\AdmModules;
use App\Models\RmaItemMasterHistory;
use App\Models\ModuleHeaders;
use App\Models\TableSettings;
use App\Models\Counters;
use App\Models\VendorTypes;
use App\Models\InventoryTypes;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Schema;


class RmaItemMasterHistoriesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'rma_item_master_histories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = RmaItemMasterHistory::query()->with(['getCreatedBy', 'getUpdatedBy', 'getApprovedBy', 'getRejectedBy']);
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
        $data['page_title'] = 'Gashapon Item Master Histories';
        $data['tableName'] = 'rma_item_master_histories';
        
        $moduleHeaders = ModuleHeaders::getModuleHeaders();
        $RmaItemMaster = self::getAllData()->paginate($this->perPage)->withQueryString();
        
        $RmaItemMaster->getCollection()->transform(function ($item) use ($moduleHeaders) {
            $itemValues = json_decode($item->item_values, true) ?? [];

            foreach ($itemValues as $key => $value) {
                if (isset($moduleHeaders[$key])) {
                    $tableName = $moduleHeaders[$key]->table;
                    $labelColumn = $moduleHeaders[$key]->table_select_label;

                    $description = DB::table($tableName)->where('id', $value)->value($labelColumn);
                    $itemValues[$key] = $description ?? $value;
                }
            }

            $item->item_values = $itemValues; 
            return $item;
        });

        $data['rma_item_master_histories'] = $RmaItemMaster;
        $data['queryParams'] = request()->query();
        $data['table_headers'] = $this->getTableHeaders();

        return Inertia::render("RmaItemMasterHistories/RmaItemMasterHistories", $data);
    }

    public function view ($id) {
        $data = [];
        $data['page_title'] = 'Item Master History View';
        $RmaItemMaster = RmaItemMasterHistory::find($id);
        $moduleHeaders = ModuleHeaders::getModuleHeaders();
    
        $itemValues = json_decode($RmaItemMaster->item_values, true) ?? [];
    
        foreach ($itemValues as $key => $value) {
            if (isset($moduleHeaders[$key])) {
                $tableName = $moduleHeaders[$key]->table;
                $labelColumn = $moduleHeaders[$key]->table_select_label;
    
                $description = DB::table($tableName)->where('id', $value)->value($labelColumn);
                $itemValues[$key] = $description ?? $value;
            }
        }
    
        $RmaItemMaster->item_values = $itemValues; 
        $data['rma_item_master_histories'] = $RmaItemMaster;
        $data['table_headers'] = $this->getTableHeaders();

        return Inertia::render("RmaItemMasterHistories/RmaItemMasterHistoriesView", $data);
    }

        private function getTableHeaders()
    {
        $table_setting = explode(',', TableSettings::where('adm_moduls_id', AdmModules::RMA_ITEM_MASTER)
        ->where('action_types_id', ActionTypes::VIEW)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->pluck('report_header')
        ->first());

        return ModuleHeaders::whereIn('header_name', $table_setting)
        ->where('module_id', AdmModules::RMA_ITEM_MASTER)
        ->select('name', 'header_name', 'width')
        ->get();
    }

    
}