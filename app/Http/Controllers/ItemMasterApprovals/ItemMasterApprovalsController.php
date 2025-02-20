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
        $query = ItemMasterApproval::query()->with(['getCreatedBy', 'getUpdatedBy', 'getApprovedBy', 'getRejectedBy']);
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

    public function approvalView($action, $id)
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

        $data['action'] = $action;
        return Inertia::render("ItemMasterApprovals/ItemMasterApprovalView", $data);
    }
    
    public function approval(Request $request)
    {
        // dd(Counters::getCode('item_masters', 'Code 1'));
        $approval = ItemMasterApproval::find($request->id);
    
        if (!$approval) {
            return redirect('/item_master_approvals')->with(['message' => 'Approval record not found!', 'type' => 'error']);
        }
    
        $itemValues = json_decode($approval->item_values, true) ?? [];
        $validColumns = Schema::getColumnListing('item_masters');
        // Reject
        if ($request->action !== 'approve') {
            $approval->update([
                'status'      => 'REJECTED',
                'rejected_by' => CommonHelpers::myId(),
                'rejected_at' => now(),
            ]);
            
            return redirect('/item_master_approvals')->with(['message' => 'Item Rejected successfully!', 'type' => 'success']);
        }
        
        // Approve
        $itemMasterData = array_filter($itemValues, function ($key) use ($validColumns) {
            return in_array($key, $validColumns);
        }, ARRAY_FILTER_USE_KEY);
        
        $itemMasterData['approved_by'] = CommonHelpers::myId();
        $itemMasterData['approved_at'] = now();
        
        $digits_code = $this->generateItemCode(
            $itemValues['categories_id'],
            $itemValues['inventory_types_id'],
            $itemValues['vendor_types_id']
        ) + 1;
    
        $itemValues['digits_code']      = $digits_code;
        $itemMasterData['digits_code']  = $digits_code;
        
        $approval->update([
            'item_values' => json_encode($itemValues),
            'status'      => 'APPROVED',
            'approved_by' => CommonHelpers::myId(),
            'approved_at' => now(),
        ]);
    
        ItemMaster::updateOrCreate(
            ['id' => $approval->item_master_id],
            $itemMasterData
        );
    
        return redirect('/item_master_approvals')->with(['message' => 'New item inserted successfully!', 'type' => 'success']);
    }
    
    public function generateItemCode($categories_id, $inventory_types_id, $vendor_types_id) {

        $data=[
            'categories_id' => $categories_id,
            'inventory_types_id' => $inventory_types_id,
            'vendor_types_id' => $vendor_types_id
        ];
        return $this->getDigitsCode($data);
        
    }


    public function getDigitsCode($params) {

        $category_code = Categories::getCodeById($params['categories_id']);
		$inventory_type_code = InventoryTypes::getCodeById($params['inventory_types_id']);
		$vendor_type_code = VendorTypes::getCodeById($params['vendor_types_id']);

		if($category_code == 'SPR') {
			return Counters::getCode('item_master','code_2');
		}
		elseif(in_array($category_code,['DEM','SAM'])) {
            // dd(Counters::getCode('item_masters', 'Code 9'));
			return Counters::getCode('item_masters', 'Code 9');
		}
		elseif(in_array($category_code,['MKT','PPB','OTH'])) {
			return Counters::getCode('code_3');
		}
		else {
			if($inventory_type_code == 'N-TRADE') {
				return Counters::getCode('code_3');
			}
			else {
				if(in_array($vendor_type_code,['IMP-OUT','LR-OUT','LOC-OUT'])) {
					return Counters::getCode('code_8');
				}
				elseif(in_array($vendor_type_code,['IMP-CON','LOC-CON','LR-CON'])){
					return Counters::getCode('code_7');
				}
			}
		}
    }

    
}