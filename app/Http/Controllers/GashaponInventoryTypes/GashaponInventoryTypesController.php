<?php

namespace App\Http\Controllers\GashaponInventoryTypes;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\GashaponInventoryTypes;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class GashaponInventoryTypesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'gashapon_inventory_types.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = GashaponInventoryTypes::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'gashapon_inventory_types';
        $data['page_title'] = 'Gashapon Inventory Types';
        $data['gashapon_inventory_types'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("GashaponInventoryTypes/GashaponInventoryTypes", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'inventory_type_description' => 'required|string|max:50|unique:gashapon_inventory_types,inventory_type_description',
        ]);

        try {

            GashaponInventoryTypes::create([
                'inventory_type_description' => $validatedFields['inventory_type_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Gashapon Inventory Type Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon Inventory Types', $e->getMessage());
            return back()->with(['message' => 'Gashapon Inventory Type Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'inventory_type_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $gashapon_inventory_types = GashaponInventoryTypes::find($request->id);

            if (!$gashapon_inventory_types) {
                return back()->with(['message' => 'Gashapon Inventory Type not found!', 'type' => 'error']);
            }
    
            $gashaponInventoryTypeDescriptionExist = GashaponInventoryTypes::where('inventory_type_description', $request->inventory_type_description)->exists();


            if ($request->inventory_type_description !== $gashapon_inventory_types->inventory_type_description) {
                if (!$gashaponInventoryTypeDescriptionExist) {
                    $gashapon_inventory_types->inventory_type_description = $validatedFields['inventory_type_description'];
                } else {
                    return back()->with(['message' => 'Gashapon Inventory Type Description already exists!', 'type' => 'error']);
                }
            }
    
            $gashapon_inventory_types->status = $validatedFields['status'];
            $gashapon_inventory_types->updated_by = CommonHelpers::myId();
            $gashapon_inventory_types->updated_at = now();
    
            $gashapon_inventory_types->save();
    
            return back()->with(['message' => 'Gashapon Inventory Type Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Gashapon Inventory Types', $e->getMessage());
            return back()->with(['message' => 'Gashapon Inventory Type Updating Failed!', 'type' => 'error']);
        }
    }

    public function export(Request $request)
    {

        try {

            $headers = [
                'Inventory Type Description',
                'Status',
                'Created By',
                'Updated By',
                'Created At',
                'Updated At',
            ];
    
            $columns = [
                'inventory_type_description',
                'status',
                'getCreatedBy.name',
                'getUpdatedBy.name',
                'created_at',
                'updated_at',
            ];
    
            $filename = "Gashapon Inventory Types - " . date ('Y-m-d H:i:s');
            $query = self::getAllData();
            return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon Inventory Types', $e->getMessage());
        }
    }
}
