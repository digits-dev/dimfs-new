<?php

namespace App\Http\Controllers\ItemMasterHistories;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ActionTypes;
use App\Models\AdmModels\AdmModules;
use App\Models\ItemMaster;
use App\Models\ItemMasterHistory;
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


class ItemMasterHistoriesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'item_master_histories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = ItemMasterHistory::query()->with(['getCreatedBy', 'getUpdatedBy', 'getApprovedBy', 'getRejectedBy']);
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

        $data['page_title'] = 'Item Master Approvals';
        $data['tableName'] = 'item_master_approvals';
        
        $moduleHeaders = ModuleHeaders::getModuleHeaders();
        $approvals = self::getAllData()->paginate($this->perPage)->withQueryString();
        
        $approvals->getCollection()->transform(function ($item) use ($moduleHeaders) {
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

        $data['item_master_approvals'] = $approvals;

        $data['queryParams'] = request()->query();
        
        $table_setting = explode(',', TableSettings::where('adm_moduls_id', AdmModules::ITEM_MASTER)
        ->where('action_types_id', ActionTypes::VIEW)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->pluck('report_header')
        ->first());

        $data['table_headers'] = ModuleHeaders::whereIn('header_name', $table_setting)
        ->where('module_id', AdmModules::ITEM_MASTER)
        ->select('name', 'header_name', 'width')
        ->get();


        return Inertia::render("ItemMasterApprovals/ItemMasterApprovals", $data);
    }
    
}