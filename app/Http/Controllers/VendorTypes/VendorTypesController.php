<?php

namespace App\Http\Controllers\VendorTypes;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\VendorTypes;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class VendorTypesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'vendor_types.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = VendorTypes::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'vendor_types';
        $data['page_title'] = 'Vendor Types';
        $data['vendor_types'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("VendorTypes/VendorTypes", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'vendor_type_code' => 'required|string|max:10|unique:vendor_types,vendor_type_code',
            'vendor_type_description' => 'required|string|max:255',
        ]);

        try {

            VendorTypes::create([
                'vendor_type_code' => $validatedFields['vendor_type_code'],
                'vendor_type_description' => $validatedFields['vendor_type_description'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Vendor Type Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('VendorTypes', $e->getMessage());
            return back()->with(['message' => 'Vendor Type Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'vendor_type_code' => 'required|string|max:10',
            'vendor_type_description' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $vendor_types = VendorTypes::find($request->id);

            if (!$vendor_types) {
                return back()->with(['message' => 'Vendor Type not found!', 'type' => 'error']);
            }
    
            $VendorTypeCodeExist = VendorTypes::where('vendor_type_code', $request->vendor_type_code)->exists();

            if ($request->vendor_type_code !== $vendor_types->vendor_type_code) {
                if (!$VendorTypeCodeExist) {
                    $vendor_types->vendor_type_code = $validatedFields['vendor_type_code'];
                } else {
                    return back()->with(['message' => 'Vendor Type Code already exists!', 'type' => 'error']);
                }
            }
    
            $vendor_types->vendor_type_description = $validatedFields['vendor_type_description'];
            $vendor_types->status = $validatedFields['status'];
            $vendor_types->updated_by = CommonHelpers::myId();
            $vendor_types->updated_at = now();
    
            $vendor_types->save();
    
            return back()->with(['message' => 'Vendor Type Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('VendorTypes', $e->getMessage());
            return back()->with(['message' => 'Vendor Type Updating Failed!', 'type' => 'error']);
        }
    }
}