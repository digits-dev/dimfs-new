<?php

namespace App\Http\Controllers\ItemMasterAccountingApprovals;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ItemMaster;
use App\Models\ItemMasterAccountingApproval;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class ItemMasterAccountingApprovalsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'item_master_accounting_approvals.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = ItemMasterAccountingApproval::query()->with(['getCreatedBy', 'getUpdatedBy', 'getItem', 'getBrand', 'getCategory', 'getMarginCategory', 'getSupportType', 'getApprovedBy', 'getRejectedBy']);
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
        $data['tableName'] = 'item_master_accounting_approvals';
        $data['page_title'] = 'Item Master Approval (Accounting)';
        $data['item_accounting_approvals'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("ItemMasterAccountingApprovals/ItemMasterAccountingApprovals", $data);
    }

    public function approvalView($action, $id){

        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }

        $data = [];
        $data['tableName'] = 'item_master_accounting_approvals';
        $data['page_title'] = 'Item Master Approval (Accounting)';
        $data['item_details'] = ItemMasterAccountingApproval::with(['getCreatedBy', 'getUpdatedBy', 'getItem', 'getBrand', 'getCategory', 'getMarginCategory', 'getSupportType', 'getApprovedBy', 'getRejectedBy'])->find($id);
        $data['action'] = $action;

        return Inertia::render("ItemMasterAccountingApprovals/ItemMasterAccountingApprovalView", $data);
    }

    public function approveItem(Request $request)
    {
        try {
            $item_for_approval = ItemMasterAccountingApproval::findOrFail($request->id);
            $item_master = ItemMaster::findOrFail($item_for_approval->item_masters_id);

            $effectiveDate = Carbon::parse($item_for_approval->effective_date);
            $actualDate = Carbon::today();

           
            DB::beginTransaction();

            if ($request->action === 'approve') {
                
                // APPROVED
                if ($effectiveDate->lessThanOrEqualTo($actualDate)) {
                    // UPDATING ITEM APPROVAL
                    $item_for_approval->status = 'APPROVED';
                    $item_for_approval->approver_privileges_id = CommonHelpers::myPrivilegeId();
                    $item_for_approval->approved_by = CommonHelpers::myId();
                    $item_for_approval->approved_at = now();

                    // UPDATING ITEM MASTER
                    $item_master->store_cost = $item_for_approval->store_cost ?? null;
                    $item_master->store_cost_percentage = $item_for_approval->store_cost_percentage ?? null;
                    $item_master->ecom_store_cost = $item_for_approval->ecom_store_cost ?? null;
                    $item_master->ecom_store_cost_percentage = $item_for_approval->ecom_store_cost_percentage ?? null;
                    $item_master->landed_cost = $item_for_approval->landed_cost ?? null;
                    $item_master->landed_cost_sea = $item_for_approval->landed_cost_sea ?? null;
                    $item_master->actual_landed_cost = $item_for_approval->actual_landed_cost ?? null;
                    $item_master->working_store_cost = $item_for_approval->working_store_cost ?? null;
                    $item_master->working_store_cost_percentage = $item_for_approval->working_store_cost_percentage ?? null;
                    $item_master->ecom_working_store_cost = $item_for_approval->ecom_working_store_cost ?? null;
                    $item_master->ecom_working_store_cost_percentage = $item_for_approval->ecom_working_store_cost_percentage ?? null;
                    $item_master->working_landed_cost = $item_for_approval->working_landed_cost ?? null;
                    $item_master->approved_by_acctg = CommonHelpers::myId();
                    $item_master->approved_at_acctg = now();

                    $item_for_approval->save();
                    $item_master->save();
                } 
                // APPROVED - SCHEDULED
                else {
                    // UPDATING ITEM APPROVAL
                    $item_for_approval->status = 'APPROVED - SCHEDULED';
                    $item_for_approval->approver_privileges_id = CommonHelpers::myPrivilegeId();
                    $item_for_approval->approved_by = CommonHelpers::myId();
                    $item_for_approval->approved_at = now();

                    $item_for_approval->save();
                }
            } 
            else if ($request->action === 'reject') {
                // UPDATING ITEM APPROVAL
                $item_for_approval->status = 'REJECTED';
                $item_for_approval->approver_privileges_id = CommonHelpers::myPrivilegeId();
                $item_for_approval->rejected_by = CommonHelpers::myId();
                $item_for_approval->rejected_at = now();

                $item_for_approval->save();
            } 

            DB::commit();

            return redirect('item_master_accounting_approvals')->with(['message' => 'Item Approval Success', 'type' => 'success']);
        } 
        catch (\Exception $e) {

            DB::rollBack();

            CommonHelpers::LogSystemError('Item Master Approval (Accounting)', $e->getMessage());
            return redirect('item_master_accounting_approvals')->with(['message' => 'Item Approval Failed', 'type' => 'error']);

        }
    }

    public function bulkActions(Request $request){
        $selectedItems = ItemMasterAccountingApproval::whereIn('id',$request->selectedIds)->where('status', "FOR APPROVAL")->get();

        if ($selectedItems->isEmpty()) {
            return back()->with(['message' => 'No valid records found for processing!', 'type' => 'error']);
        }

        DB::beginTransaction();

        try {
           
            foreach ($selectedItems as $item){

                $item_for_approval = ItemMasterAccountingApproval::findOrFail($item->id);
                $item_master = ItemMaster::findOrFail($item_for_approval->item_masters_id);
    
                $effectiveDate = Carbon::parse($item_for_approval->effective_date);
                $actualDate = Carbon::today();

                if ($request->bulkAction === 'Approve') {
                
                    // APPROVED
                    if ($effectiveDate->lessThanOrEqualTo($actualDate)) {
                        // UPDATING ITEM APPROVAL
                        $item_for_approval->status = 'APPROVED';
                        $item_for_approval->approver_privileges_id = CommonHelpers::myPrivilegeId();
                        $item_for_approval->approved_by = CommonHelpers::myId();
                        $item_for_approval->approved_at = now();
    
                        // UPDATING ITEM MASTER
                        $item_master->store_cost = $item_for_approval->store_cost ?? null;
                        $item_master->store_cost_percentage = $item_for_approval->store_cost_percentage ?? null;
                        $item_master->ecom_store_cost = $item_for_approval->ecom_store_cost ?? null;
                        $item_master->ecom_store_cost_percentage = $item_for_approval->ecom_store_cost_percentage ?? null;
                        $item_master->landed_cost = $item_for_approval->landed_cost ?? null;
                        $item_master->landed_cost_sea = $item_for_approval->landed_cost_sea ?? null;
                        $item_master->actual_landed_cost = $item_for_approval->actual_landed_cost ?? null;
                        $item_master->working_store_cost = $item_for_approval->working_store_cost ?? null;
                        $item_master->working_store_cost_percentage = $item_for_approval->working_store_cost_percentage ?? null;
                        $item_master->ecom_working_store_cost = $item_for_approval->ecom_working_store_cost ?? null;
                        $item_master->ecom_working_store_cost_percentage = $item_for_approval->ecom_working_store_cost_percentage ?? null;
                        $item_master->working_landed_cost = $item_for_approval->working_landed_cost ?? null;
                        $item_master->approved_by_acctg = CommonHelpers::myId();
                        $item_master->approved_at_acctg = now();
    
                        $item_for_approval->save();
                        $item_master->save();
                    } 
                    // APPROVED - SCHEDULED
                    else {
                        // UPDATING ITEM APPROVAL
                        $item_for_approval->status = 'APPROVED - SCHEDULED';
                        $item_for_approval->approver_privileges_id = CommonHelpers::myPrivilegeId();
                        $item_for_approval->approved_by = CommonHelpers::myId();
                        $item_for_approval->approved_at = now();
    
                        $item_for_approval->save();
                    }
                } 
                else if ($request->bulkAction === 'Reject') {
                    // UPDATING ITEM APPROVAL
                    $item_for_approval->status = 'REJECTED';
                    $item_for_approval->approver_privileges_id = CommonHelpers::myPrivilegeId();
                    $item_for_approval->rejected_by = CommonHelpers::myId();
                    $item_for_approval->rejected_at = now();
    
                    $item_for_approval->save();
                } 
            }

            DB::commit();

            return redirect('item_master_accounting_approvals')->with(['message' => "Bulk {$request->bulkAction} completed successfully!", 'type' => 'success']);
        } 
        catch (\Exception $e) {

            DB::rollBack();

            CommonHelpers::LogSystemError('Item Master Approval (Accounting)', $e->getMessage());
            return redirect('item_master_accounting_approvals')->with(['message' => 'Bulk {$request->bulkAction} Failed', 'type' => 'error']);

        }
    
    }


    public function export(Request $request)
    {

        $headers = [
            'Approval Status',
            'Digits Code',
            'Brand Description',
            'Category Description',
            'Margin Category Description',
            'Store Cost',
            'Store Margin (%)',
            'ECOMM - Store Cost',
            'ECOMM - Store Margin (%)',
            'Landed Cost',
            'Landed Cost Via SEA',
            'Actual Landed Cost',
            'Working Store Cost',
            'Working Store Margin (%)',
            'ECOMM - Working Store Cost',
            'ECOMM - Working Store Margin (%)',
            'Working Landed Cost',
            'Effective Date',
            'Duration From',
            'Duration To',
            'Support Type',
            'Approved By',
            'Rejected By',
            'Updated By',
            'Approved At',
            'Rejected At',
            'Updated At',
        ];

        $columns = [
            'status',
            'getItem.digits_code',
            'getBrand.brand_description',
            'getCategory.category_description',
            'getMarginCategory.margin_category_description',
            'store_cost',
            'store_cost_percentage',
            'ecom_store_cost',
            'ecom_store_cost_percentage',
            'landed_cost',
            'landed_cost_sea',
            'actual_landed_cost',
            'working_store_cost',
            'working_store_cost_percentage',
            'ecom_working_store_cost',
            'ecom_working_store_cost_percentage',
            'working_landed_cost',
            'effective_date',
            'duration_from',
            'duration_to',
            'getSupportType.support_type_description',
            'getApprovedBy.name',
            'getRejectedBy.name',
            'getUpdatedBy.name',
            'approved_at',
            'rejected_at',
            'updated_at',
        ];

        $filename = "Approval Items Accounting - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }

}
