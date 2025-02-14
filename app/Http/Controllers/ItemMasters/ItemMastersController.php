<?php

namespace App\Http\Controllers\ItemMasters;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ActionTypes;
use App\Models\AdmModels\AdmModules;
use App\Models\ItemMaster;
use App\Models\ModuleHeaders;
use App\Models\TableSettings;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

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
        $query = ItemMaster::query()->with(['getCreatedBy', 'getUpdatedBy']);
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

        $data['table_headers'] = ModuleHeaders::whereIn('header_name', $data['table_setting'])
        ->where('module_id', AdmModules::ITEM_MASTER)
        ->select('name', 'header_name', 'width')
        ->get();

        return Inertia::render("ItemMasters/ItemMasters", $data);
    }

    public function getCreate(){
        if(!CommonHelpers::isCreate()) {
            return Inertia::render('Errors/RestrictionPage');
        }

        $data = [];
        $data['page_title'] = 'Item Master - Create';

        return Inertia::render("ItemMasters/ItemMasterCreate", $data);
    }

    public function getUpdate(){

    }
}
