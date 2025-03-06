<?php

namespace App\Http\Controllers\GashaponItemMasterApprovals;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\GashaponItemMaster;
use App\Models\GashaponItemMasterApproval;
use App\Models\GashaponItemMasterHistory;
use App\Models\ModuleHeaders;
use App\Models\TableSettings;
use App\Models\AdmModels\AdmModules;
use App\Models\ActionTypes;
use App\Models\Counters;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Schema;
use DB;

class GashaponItemMasterApprovalsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'gashapon_item_master_approvals.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = GashaponItemMasterApproval::query()->with(['getCreatedBy', 'getUpdatedBy', 'getApprovedBy', 'getRejectedBy']);
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

        $data['page_title'] = 'Gashapon Item Master Approvals';
        
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

        $data['gashapon_item_master_approvals'] = $approvals;

        $data['queryParams'] = request()->query();
        
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::GASHAPON_ITEM_MASTER, ActionTypes::VIEW, CommonHelpers::myPrivilegeId());
        $data['table_headers'] = ModuleHeaders::getHeadersByModule(AdmModules::GASHAPON_ITEM_MASTER, $tableSetting);

        return Inertia::render("GashaponItemMasterApprovals/GashaponItemMasterApprovals", $data);
    }

    public function approvalView($action, $id)
    {
        $data = [];
        $data['page_title'] = 'Gashapon Item Master Approval View';
        $approval = GashaponItemMasterApproval::find($id);
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
        $data['gashapon_item_master_approval'] = $approval;
        
        $tableSetting = TableSettings::getActiveHeaders(AdmModules::GASHAPON_ITEM_MASTER, ActionTypes::VIEW, CommonHelpers::myPrivilegeId());
        $data['table_headers'] = ModuleHeaders::getHeadersByModule(AdmModules::GASHAPON_ITEM_MASTER, $tableSetting);

        $data['action'] = $action;
        return Inertia::render("GashaponItemMasterApprovals/GashaponItemMasterApprovalView", $data);
    }

    public function approval(Request $request)
    {
        $approval = GashaponItemMasterApproval::find($request->id);
        if (!$approval) {
            return redirect('/gashapon_item_master_approvals')->with(['message' => 'Approval record not found!', 'type' => 'error']);
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
    
            GashaponItemMasterHistory::create([
                'item_values'   => json_encode($itemValues),
                'action'        => $approval->action.'-'.$status,
                'gashapon_item_master_id'=> $approval->gashapon_item_master_id,
                'status'        => $status,
                'rejected_by'   => CommonHelpers::myId(),
                'rejected_at'   => now(),
            ]);
    
            return redirect('/gashapon_item_master_approvals')->with(['message' => 'Item Rejected successfully!', 'type' => 'success']);
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

    
        // If gashapon_item_master_id is null, generate a new digits_code
        if (is_null($approval->gashapon_item_master_id)) {
            $digits_code = Counters::getCode('gashapon_item_masters','Code 6');
    
            $itemMasterData['digits_code']  = $digits_code['counter_code'];
            $itemValues['digits_code']      = $digits_code['counter_code'];
            $updateData['item_values']      = json_encode($itemValues);
    
            // Increment counter
            Counters::incrementCode('gashapon_item_masters', $digits_code['code_identifier']);
        }
    
        // Update or create GashaponItemMaster
        $gashaponItemMaster = GashaponItemMaster::updateOrCreate(
            ['id' => $approval->gashapon_item_master_id],
            $itemMasterData
        );
    
        GashaponItemMasterHistory::create([
            'item_values'   => json_encode($itemValues),
            'action'        => $approval->action.'-'.$status,
            'gashapon_item_master_id'=> $gashaponItemMaster->id,
            'status'        => $status,
            'approved_by' => CommonHelpers::myId(),
            'approved_at' => now(),
        ]);
    
        $updateData['gashapon_item_master_id'] = $gashaponItemMaster->id;
        $approval->update($updateData);
    
        return redirect('/gashapon_item_master_approvals')->with(['message' => 'Item processed successfully!', 'type' => 'success']);
    }
    
    
    public function bulkActions(Request $request){
        $selectedItems = GashaponItemMasterApproval::whereIn('id',$request->selectedIds)->where('status', "FOR APPROVAL")->get();
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

                GashaponItemMasterHistory::create([
                    'item_values'   => json_encode($itemValues),
                    'action'        => $approval->action.'-'.$status,
                    'gashapon_item_master_id'=> $approval->gashapon_item_master_id,
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
            
                // Generate digits code only if gashapon_item_master_id is null
            $updateData = [
                'status'      => 'APPROVED',
                'approved_by' => CommonHelpers::myId(),
                'approved_at' => now(),
            ];
            
            // If gashapon_item_master_id is null, generate a new digits_code and include item_values
            if (is_null($approval->gashapon_item_master_id)) {
                $digits_code = Counters::getCode('gashapon_item_masters','Code 6');
                
                $itemMasterData['digits_code']  = $digits_code['counter_code'];
                $itemValues['digits_code']      = $digits_code['counter_code'];
                $updateData['item_values'] = json_encode($itemValues);

                // Increment the counter
                Counters::incrementCode('gashapon_item_masters', $digits_code['code_identifier']);
            }

            $gashaponItemMaster = GashaponItemMaster::updateOrCreate(
                ['id' => $approval->gashapon_item_master_id],
                $itemMasterData
            );

            GashaponItemMasterHistory::create([
                'item_values'   => json_encode($itemValues),
                'action'        => $approval->action.'-'.$status,
                'gashapon_item_master_id'=> $gashaponItemMaster->id,
                'status'        => $status,
                'approved_by' => CommonHelpers::myId(),
                'approved_at' => now(),
            ]);
        
            
            $updateData['gashapon_item_master_id'] = $gashaponItemMaster->id;
            $approval->update($updateData);
        }

        return back()->with(['message' => "Bulk {$request->bulkAction} completed successfully!", 'type' => 'success']);
    
    }


    public function export() {
     
        $fileName = 'Pending Gashapon Items for Approval-'.date("d M Y - h.i.sa").'.xlsx';
        return Excel::download(new ItemMasterApprovalsExport, $fileName);
    }
}