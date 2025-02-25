<?php

namespace App\Http\Controllers\ItemMasterApprovals;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ActionTypes;
use App\Models\AdmModels\AdmModules;
use App\Models\ItemMaster;
use App\Models\ItemMasterApproval;
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
use App\Exports\ItemMasterApprovalsExport;
use Maatwebsite\Excel\Facades\Excel;



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
        $approval = ItemMasterApproval::find($request->id);
    
        if (!$approval) {
            return redirect('/item_master_approvals')->with(['message' => 'Approval record not found!', 'type' => 'error']);
        }
    
        $itemValues = json_decode($approval->item_values, true) ?? [];
        $validColumns = Schema::getColumnListing('item_masters');
    
        // Determine action type
        $isApproved = $request->action === 'approve';
        $status = $isApproved ? 'APPROVED' : 'REJECTED';
    
        // If rejected, update status and return
        if (!$isApproved) {
            $approval->update([
                'status'      => $status,
                'rejected_by' => CommonHelpers::myId(),
                'rejected_at' => now(),
            ]);
    
            ItemMasterHistory::create([
                'item_values'   => json_encode($itemValues),
                'action'        => $approval->action.'-'.$status,
                'item_master_id'=> $approval->item_master_id,
                'status'        => $status,
                'rejected_by'   => CommonHelpers::myId(),
                'rejected_at'   => now(),
            ]);
    
            return redirect('/item_master_approvals')->with(['message' => 'Item Rejected successfully!', 'type' => 'success']);
        }
    
        // Approve logic
        $itemMasterData = array_filter($itemValues, function ($key) use ($validColumns) {
            return in_array($key, $validColumns);
        }, ARRAY_FILTER_USE_KEY);
    
        $itemMasterData['approved_by'] = CommonHelpers::myId();
        $itemMasterData['approved_at'] = now();
    
        // Prepare update data
        $updateData = [
            'status'      => $status,
            'approved_by' => CommonHelpers::myId(),
            'approved_at' => now(),
        ];
    
        // If item_master_id is null, generate a new digits_code
        if (is_null($approval->item_master_id)) {
            $digits_code = $this->getDigitsCode(
                $itemValues['categories_id'],
                $itemValues['inventory_types_id'],
                $itemValues['vendor_types_id']
            );
    
            $itemMasterData['digits_code']  = $digits_code['counter_code'];
            $itemValues['digits_code']      = $digits_code['counter_code'];
            $updateData['item_values']      = json_encode($itemValues);
    
            // Increment counter
            Counters::incrementCode('item_masters', $digits_code['code_identifier']);
        }
    
        // Update or create ItemMaster
        $itemMaster = ItemMaster::updateOrCreate(
            ['id' => $approval->item_master_id],
            $itemMasterData
        );
    
        ItemMasterHistory::create([
            'item_values'   => json_encode($itemValues),
            'action'        => $approval->action.'-'.$status,
            'item_master_id'=> $itemMaster->id,
            'status'        => $status,
            'approved_by' => CommonHelpers::myId(),
            'approved_at' => now(),
        ]);
    
        $updateData['item_master_id'] = $itemMaster->id;
        $approval->update($updateData);
    
        return redirect('/item_master_approvals')->with(['message' => 'Item processed successfully!', 'type' => 'success']);
    }
    
    
    public function bulkActions(Request $request){
        $selectedItems = ItemMasterApproval::whereIn('id',$request->selectedIds)->where('status', "FOR APPROVAL")->get();

        if ($selectedItems->isEmpty()) {
            return back()->with(['message' => 'No valid records found for processing!', 'type' => 'error']);
        }
    
        // Determine action type
        $isApproved = $request->bulkAction === 'Approve';
        $status = $isApproved ? 'APPROVED' : 'REJECTED';

        foreach($selectedItems as $approval ) {
            $itemValues = json_decode($approval->item_values, true) ?? [];
            $validColumns = Schema::getColumnListing('item_masters');

            if ($request->bulkAction !== 'Approve') {
                // Reject Item
                $approval->update([
                    'status'      => $status,
                    'rejected_by' => CommonHelpers::myId(),
                    'rejected_at' => now(),
                ]);

                ItemMasterHistory::create([
                    'item_values'   => json_encode($itemValues),
                    'action'        => $approval->action.'-'.$status,
                    'item_master_id'=> $approval->item_master_id,
                    'status'        => $status,
                    'rejected_by'   => CommonHelpers::myId(),
                    'rejected_at'   => now(),
                ]);
        
                continue;
            }
    
             // Approve
            $itemMasterData = array_filter($itemValues, function ($key) use ($validColumns) {
                return in_array($key, $validColumns);
            }, ARRAY_FILTER_USE_KEY);
            
            $itemMasterData['approved_by'] = CommonHelpers::myId();
            $itemMasterData['approved_at'] = now();
            
                // Generate digits code only if item_master_id is null
            $updateData = [
                'status'      => 'APPROVED',
                'approved_by' => CommonHelpers::myId(),
                'approved_at' => now(),
            ];
            
            // If item_master_id is null, generate a new digits_code and include item_values
            if (is_null($approval->item_master_id)) {
                $digits_code = $this->getDigitsCode(
                    $itemValues['categories_id'],
                    $itemValues['inventory_types_id'],
                    $itemValues['vendor_types_id']
                );
        
                $itemMasterData['digits_code']  = $digits_code['counter_code'];
                $itemValues['digits_code']      = $digits_code['counter_code'];
                $updateData['item_values'] = json_encode($itemValues);

                // Increment the counter
                Counters::incrementCode('item_masters', $digits_code['code_identifier']);
            }

            $itemMaster = ItemMaster::updateOrCreate(
                ['id' => $approval->item_master_id],
                $itemMasterData
            );

            ItemMasterHistory::create([
                'item_values'   => json_encode($itemValues),
                'action'        => $approval->action.'-'.$status,
                'item_master_id'=> $itemMaster->id,
                'status'        => $status,
                'approved_by' => CommonHelpers::myId(),
                'approved_at' => now(),
            ]);
        
            
            $updateData['item_master_id'] = $itemMaster->id;
            $approval->update($updateData);
        }

        return back()->with(['message' => "Bulk {$request->bulkAction} completed successfully!", 'type' => 'success']);
    
    }

    public function getDigitsCode($categories_id, $inventory_types_id, $vendor_types_id) {

        $category_code = Categories::getCodeById($categories_id);
		$inventory_type_code = InventoryTypes::getCodeById($inventory_types_id);
		$vendor_type_code = VendorTypes::getCodeById($vendor_types_id);

		if($category_code == 'SPR') {
			return Counters::getCode('item_masters','Code 2');
		}
		elseif(in_array($category_code,['DEM','SAM'])) {
			return Counters::getCode('item_masters', 'Code 9');
		}
		elseif(in_array($category_code,['MKT','PPB','OTH'])) {
			return Counters::getCode('item_masters','Code 3');
		}
		else {
			if($inventory_type_code == 'N-TRADE') {
                return Counters::getCode('item_masters','Code 3');
			}
			else {
				if(in_array($vendor_type_code,['IMP-OUT','LR-OUT','LOC-OUT'])) {
                    return Counters::getCode('item_masters','Code 8');
				}
				elseif(in_array($vendor_type_code,['IMP-CON','LOC-CON','LR-CON'])){
                    return Counters::getCode('item_masters','Code 7');
				}
			}
		}
    }

    public function export() {
     
        $fileName = 'Pending Items for Approval-'.date("d M Y - h.i.sa").'.xlsx';
        return Excel::download(new ItemMasterApprovalsExport, $fileName);
    }
    
}