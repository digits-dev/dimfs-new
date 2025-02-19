<?php

namespace App\Http\Controllers\ItemMasterApprovals;

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
use Illuminate\Support\Facades\Schema;


class ItemMasterApprovalsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'item_master_approvals.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = ItemMasterApproval::query()->with(['getCreatedBy', 'getUpdatedBy', 'getApprovedBy']);
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

    public function approvalView($id)
    {
        $data = [];
    
        $data['page_title'] = 'Item Master Approval View';
        $approval = ItemMasterApproval::find($id);
        $moduleHeaders = ModuleHeaders::getModuleHeaders();
    
        $itemValues = json_decode($approval->item_values, true) ?? [];
    
        foreach ($itemValues as $key => $value) {
            if (isset($moduleHeaders[$key])) {
                $tableName = $moduleHeaders[$key]->table;
                $labelColumn = $moduleHeaders[$key]->table_select_label;
    
                $description = DB::table($tableName)->where('id', $value)->value($labelColumn);
                $itemValues[$key] = $description ?? $value;
            }
        }
    
        $approval->item_values = $itemValues; 
        $data['item_master_approval'] = $approval;
        
        $table_setting = explode(',', TableSettings::where('adm_moduls_id', AdmModules::ITEM_MASTER)
        ->where('action_types_id', ActionTypes::VIEW)
        ->where('adm_privileges_id', CommonHelpers::myPrivilegeId())
        ->where('status', 'ACTIVE')
        ->pluck('report_header')
        ->first());

        $data['table_headers'] = ModuleHeaders::whereIn('header_name', $table_setting)
        ->where('module_id', AdmModules::ITEM_MASTER)
        ->select('name', 'header_name')
        ->get();


        return Inertia::render("ItemMasterApprovals/ItemMasterApprovalView", $data);
    }
    
    public function approval(Request $request) {
        // dd($request->all());

        $approval = ItemMasterApproval::find($request->id);
        $itemValues = json_decode($approval->item_values, true) ?? [];
        $validColumns = Schema::getColumnListing('item_masters');


        if ($request->action === 'approve') {

            $itemMasterData = array_filter($itemValues, function ($key) use ($validColumns) {
                return in_array($key, $validColumns);
            }, ARRAY_FILTER_USE_KEY);
    
            $itemMasterData['approved_by'] = CommonHelpers::myId();
            $itemMasterData['approved_at'] = now();
    
            $approval->update(['status' => 'APPROVED',
                'approved_by' => CommonHelpers::myId(),
                'approved_at' => now()
            ]);
    
            $newItem = ItemMaster::create($itemMasterData);
            return redirect('/item_master_approvals')->with(['message' => 'New item inserted successfully!', 'type' => 'success']);
        }
        else {
            $approval->update(['status' => 'REJECTED',
            'rejected_by' => CommonHelpers::myId(),
            'rejected_at' => now()
            ]);

        return redirect('/item_master_approvals')->with(['message' => 'Item Rejected successfully!', 'type' => 'success']);
        }


    }
}