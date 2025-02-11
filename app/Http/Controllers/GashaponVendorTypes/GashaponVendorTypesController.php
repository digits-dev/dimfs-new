<?php

namespace App\Http\Controllers\GashaponVendorTypes;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\GashaponVendorTypes;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class GashaponVendorTypesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'gashapon_vendor_types.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = GashaponVendorTypes::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'gashapon_vendor_types';
        $data['page_title'] = 'Gashapon Vendor Types';
        $data['gashapon_vendor_types'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("GashaponVendorTypes/GashaponVendorTypes", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'vendor_type_code' => 'required|string|max:10|unique:gashapon_vendor_types,vendor_type_code',
            'vendor_type_description' => 'required|string|max:50|unique:gashapon_vendor_types,vendor_type_description',
        ]);

        try {

            GashaponVendorTypes::create([
                'vendor_type_code' => $validatedFields['vendor_type_code'],   
                'vendor_type_description' => $validatedFields['vendor_type_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Gashapon Vendor Type Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon Vendor Types', $e->getMessage());
            return back()->with(['message' => 'Gashapon Vendor Type Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'vendor_type_code' => 'required|string|max:10',
            'vendor_type_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $gashapon_vendor_types = GashaponVendorTypes::find($request->id);

            if (!$gashapon_vendor_types) {
                return back()->with(['message' => 'Gashapon Vendor Type not found!', 'type' => 'error']);
            }
    
            $gashaponVendorTypeCodeExist = GashaponVendorTypes::where('vendor_type_code', $request->vendor_type_code)->exists();
            $gashaponVendorTypeDescriptionExist = GashaponVendorTypes::where('vendor_type_description', $request->vendor_type_description)->exists();


            if ($request->vendor_type_code !== $gashapon_vendor_types->vendor_type_code) {
                if (!$gashaponVendorTypeCodeExist) {
                    $gashapon_vendor_types->vendor_type_code = $validatedFields['vendor_type_code'];
                } else {
                    return back()->with(['message' => 'Vendor Type Code already exists!', 'type' => 'error']);
                }
            }

            if ($request->vendor_type_description !== $gashapon_vendor_types->vendor_type_description) {
                if (!$gashaponVendorTypeDescriptionExist) {
                    $gashapon_vendor_types->vendor_type_description = $validatedFields['vendor_type_description'];
                } else {
                    return back()->with(['message' => 'Vendor Type Description already exists!', 'type' => 'error']);
                }
            }
    
            $gashapon_vendor_types->status = $validatedFields['status'];
            $gashapon_vendor_types->updated_by = CommonHelpers::myId();
            $gashapon_vendor_types->updated_at = now();
    
            $gashapon_vendor_types->save();
    
            return back()->with(['message' => 'Gashapon Vendor Type Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Gashapon Vendor Types', $e->getMessage());
            return back()->with(['message' => 'Gashapon Vendor Type Updating Failed!', 'type' => 'error']);
        }
    }

    public function export(Request $request)
    {

        try {

            $headers = [
                'Vendor Type Code',
                'Vendor Type Description',
                'Status',
                'Created By',
                'Updated By',
                'Created At',
                'Updated At',
            ];
    
            $columns = [
                'vendor_type_code',
                'vendor_type_description',
                'status',
                'getCreatedBy.name',
                'getUpdatedBy.name',
                'created_at',
                'updated_at',
            ];
    
            $filename = "Gashapon Vendor Types - " . date ('Y-m-d H:i:s');
            $query = self::getAllData();
            return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon Vendor Types', $e->getMessage());
        }

    }
}
