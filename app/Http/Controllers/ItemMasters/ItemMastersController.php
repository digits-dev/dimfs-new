<?php

namespace App\Http\Controllers\ItemMasters;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ActionTypes;
use App\Models\AdmModels\AdmModules;
use App\Models\ItemMaster;
use App\Models\ItemMasterApproval;
use App\Models\ModuleHeaders;
use App\Models\TableSettings;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;


class ItemMastersController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'item_masters.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = ItemMaster::query()->with(['getCreatedBy', 'getUpdatedBy', 'getBrand', 'getBrandGroup']);
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

        $data['table_setting'] = explode(',', TableSettings::where('adm_moduls_id', AdmModules::ITEM_MASTER)
        ->where('action_types_id', ActionTypes::VIEW)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->pluck('report_header')
        ->first());

        $data['can_create'] = TableSettings::where('adm_moduls_id', AdmModules::ITEM_MASTER)
        ->where('action_types_id', ActionTypes::CREATE)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->exists();

        $data['table_headers'] = ModuleHeaders::whereIn('header_name', $data['table_setting'])
        ->where('module_id', AdmModules::ITEM_MASTER)
        ->select('name', 'header_name', 'width', 'table_join')
        ->get();

        return Inertia::render("ItemMasters/ItemMasters", $data);
    }

    // ------------------------------------------ CREATE ITEM ------------------------------------------ //

    public function getCreate(){
        if(!CommonHelpers::isCreate()) {
            return Inertia::render('Errors/RestrictionPage');
        }

        $data = [];
        $data['page_title'] = 'Item Master - Create';

        
        $data['table_setting'] = explode(',', TableSettings::where('adm_moduls_id', AdmModules::ITEM_MASTER)
        ->where('action_types_id', ActionTypes::CREATE)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->pluck('report_header')
        ->first());

        $data['create_inputs'] = ModuleHeaders::whereIn('header_name', $data['table_setting'])
        ->where('module_id', AdmModules::ITEM_MASTER)
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

        try {

            ItemMasterApproval::create([
               'item_values' => json_encode($request->all()),
            ]);
    
            return redirect('/item_masters')->with(['message' => 'Item Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Item Master', $e->getMessage());
            return back()->with(['message' => 'Item Creation Failed!', 'type' => 'error']);
        }
    }

    // ---------------------------------------- UPDATE ITEM ---------------------------------------- //

    public function getUpdate(){

    }

    // ---------------------------------------- VIEW ITEM -------------------------------------------//

    public function getView(ItemMaster $item){

        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }

        $data = [];
        $data['page_title'] = 'Item Master - Item Details';
        $data['item_master_detail'] = ItemMaster::where('id', $item->id)->with(['getCreatedBy', 'getUpdatedBy', 'getBrand', 'getBrandGroup'])->first();

        $data['table_setting'] = explode(',', TableSettings::where('adm_moduls_id', AdmModules::ITEM_MASTER)
        ->where('action_types_id', ActionTypes::VIEW)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->pluck('report_header')
        ->first());

        $data['table_headers'] = ModuleHeaders::whereIn('header_name', $data['table_setting'])
        ->where('module_id', AdmModules::ITEM_MASTER)
        ->select('name', 'header_name', 'width', 'table_join')
        ->get();

        return Inertia::render("ItemMasters/ItemMasterView", $data);
    }
    
}
