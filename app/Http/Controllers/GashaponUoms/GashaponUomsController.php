<?php

namespace App\Http\Controllers\GashaponUoms;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\GashaponUoms;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class GashaponUomsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'gashapon_uoms.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = GashaponUoms::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'gashapon_uoms';
        $data['page_title'] = 'Gashapon UOMs';
        $data['gashapon_uoms'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("GashaponUoms/GashaponUoms", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'uom_code' => 'required|string|max:3|unique:gashapon_uoms,uom_code',
            'uom_description' => 'required|string|max:50|unique:gashapon_uoms,uom_description',
        ]);

        try {

            GashaponUoms::create([
                'uom_code' => $validatedFields['uom_code'],   
                'uom_description' => $validatedFields['uom_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Gashapon UOM Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon UOMs', $e->getMessage());
            return back()->with(['message' => 'Gashapon UOM Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'uom_code' => 'required|string|max:3',
            'uom_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $gashapon_oums = GashaponUoms::find($request->id);

            if (!$gashapon_oums) {
                return back()->with(['message' => 'Gashapon UOM not found!', 'type' => 'error']);
            }
    
            $gashaponUomCodeExist = GashaponUoms::where('uom_code', $request->uom_code)->exists();
            $gashaponUomDescriptionExist = GashaponUoms::where('uom_description', $request->uom_description)->exists();


            if ($request->uom_code !== $gashapon_oums->uom_code) {
                if (!$gashaponUomCodeExist) {
                    $gashapon_oums->uom_code = $validatedFields['uom_code'];
                } else {
                    return back()->with(['message' => 'UOM Code already exists!', 'type' => 'error']);
                }
            }

            if ($request->uom_description !== $gashapon_oums->uom_description) {
                if (!$gashaponUomDescriptionExist) {
                    $gashapon_oums->uom_description = $validatedFields['uom_description'];
                } else {
                    return back()->with(['message' => 'UOM Description already exists!', 'type' => 'error']);
                }
            }
    
            $gashapon_oums->status = $validatedFields['status'];
            $gashapon_oums->updated_by = CommonHelpers::myId();
            $gashapon_oums->updated_at = now();
    
            $gashapon_oums->save();
    
            return back()->with(['message' => 'Gashapon UOM Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Gashapon UOMs', $e->getMessage());
            return back()->with(['message' => 'Gashapon UOM Updating Failed!', 'type' => 'error']);
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
    
            $filename = "Gashapon UOMs - " . date ('Y-m-d H:i:s');
            $query = self::getAllData();
            return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon UOMs', $e->getMessage());
        }

    }
}
