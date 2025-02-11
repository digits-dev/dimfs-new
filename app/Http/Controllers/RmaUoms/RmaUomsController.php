<?php

namespace App\Http\Controllers\RmaUoms;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\RmaUoms;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class RmaUomsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'rma_uoms.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = RmaUoms::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'rma_uoms';
        $data['page_title'] = 'RMA UOMs';
        $data['rma_uoms'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("RmaUoms/RmaUoms", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'uom_code' => 'required|string|max:3|unique:rma_uoms,uom_code',
            'uom_description' => 'required|string|max:30|unique:rma_uoms,uom_description',
        ]);

        try {

            RmaUoms::create([
                'uom_code' => $validatedFields['uom_code'],   
                'uom_description' => $validatedFields['uom_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'RMA UOM Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('RMA UOMs', $e->getMessage());
            return back()->with(['message' => 'RMA UOM Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'uom_code' => 'required|string|max:3',
            'uom_description' => 'required|string|max:30',
            'status' => 'required|string',
        ]);

        try {
    
            $rma_uoms = RmaUoms::find($request->id);

            if (!$rma_uoms) {
                return back()->with(['message' => 'RMA UOM not found!', 'type' => 'error']);
            }
    
            $UomCodeExist = RmaUoms::where('uom_code', $request->uom_code)->exists();
            $UomDescriptionExist = RmaUoms::where('uom_description', $request->uom_description )->exists();


            if ($request->uom_code !== $rma_uoms->uom_code) {
                if (!$UomCodeExist) {
                    $rma_uoms->uom_code = $validatedFields['uom_code'];
                } else {
                    return back()->with(['message' => 'RMA UOM Code already exists!', 'type' => 'error']);
                }
            }

            if ($request->uom_description !== $rma_uoms->uom_description) {
                if (!$UomDescriptionExist) {
                    $rma_uoms->uom_description = $validatedFields['uom_description'];
                } else {
                    return back()->with(['message' => 'RMA UOM Description already exists!', 'type' => 'error']);
                }
            }
    
            $rma_uoms->status = $validatedFields['status'];
            $rma_uoms->updated_by = CommonHelpers::myId();
            $rma_uoms->updated_at = now();
    
            $rma_uoms->save();
    
            return back()->with(['message' => 'RMA UOM Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('RMA UOMs', $e->getMessage());
            return back()->with(['message' => 'RMA UOM Updating Failed!', 'type' => 'error']);
        }
    }

    public function export(Request $request)
    {

        try {
            
            $headers = [
                'UOM Code',
                'UOM Description',
                'Status',
                'Created By',
                'Updated By',
                'Created At',
                'Updated At',
            ];
    
            $columns = [
                'uom_code',
                'uom_description',
                'status',
                'getCreatedBy.name',
                'getUpdatedBy.name',
                'created_at',
                'updated_at',
            ];
    
            $filename = "RMA UOMs - " . date ('Y-m-d H:i:s');
            $query = self::getAllData();
            return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('RMA UOMs', $e->getMessage());
        }

    }
}
