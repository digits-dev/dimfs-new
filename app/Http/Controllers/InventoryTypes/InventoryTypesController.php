<?php

namespace App\Http\Controllers\InventoryTypes;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\InventoryTypes;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class InventoryTypesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'inventory_types.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = InventoryTypes::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'inventory_types';
        $data['page_title'] = 'Inventory Types';
        $data['inventory_types'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();
        
        return Inertia::render("InventoryTypes/InventoryTypes", $data);
    }

    public function create(Request $request){
        
        $validatedFields = $request->validate([
            'inventory_type_code' => 'required|string|max:3|unique:inventory_types,inventory_type_code',
            'inventory_type_description' => 'required|string|max:30|unique:inventory_types,inventory_type_description',
        ]);
        
        try {

            InventoryTypes::create([
                'inventory_type_code' => $validatedFields['inventory_type_code'], 
                'inventory_type_description' => $validatedFields['inventory_type_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Inventory Type Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('InventoryTypes', $e->getMessage());
            return back()->with(['message' => 'Inventory Type Creation Failed!', 'type' => 'error']);
        }
        
       
    }


    public function update(Request $request){

        $validatedFields = $request->validate([
            'inventory_type_code' => 'required|string|max:3',
            'inventory_type_description' => 'required|string|max:30',
            'status' => 'required|string',
        ]);

        try {
    
            $inventory_types = InventoryTypes::find($request->id);

            if (!$inventory_types) {
                return back()->with(['message' => 'Inventory Type not found!', 'type' => 'error']);
            }
    
            $InventoryTypeCodeExist = InventoryTypes::where('inventory_type_code', $request->inventory_type_code)->exists();
            $InventoryTypeDescriptionExist = InventoryTypes::where('inventory_type_description', $request->inventory_type_description)->exists();

            if ($request->inventory_type_code !== $inventory_types->inventory_type_code) {
                if (!$InventoryTypeCodeExist) {
                    $inventory_types->inventory_type_code = $validatedFields['inventory_type_code'];
                } else {
                    return back()->with(['message' => 'Inventory Type code already exists!', 'type' => 'error']);
                }
            }
            if ($request->inventory_type_description !== $inventory_types->inventory_type_description) {
                if (!$InventoryTypeDescriptionExist) {
                    $inventory_types->inventory_type_description = $validatedFields['inventory_type_description'];
                } else {
                    return back()->with(['message' => 'Inventory Type Description already exists!', 'type' => 'error']);
                }
            }
    
            $inventory_types->status = $validatedFields['status'];
            $inventory_types->updated_by = CommonHelpers::myId();
            $inventory_types->updated_at = now();
    
            $inventory_types->save();
    
            return back()->with(['message' => 'Inventory Type Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('InventoryTypes', $e->getMessage());
            return back()->with(['message' => 'Inventory Type Updating Failed!', 'type' => 'error']);
        }
    }
}