<?php

namespace App\Http\Controllers\GashaponProductTypes;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\GashaponProductTypes;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class GashaponProductTypesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'gashapon_product_types.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = GashaponProductTypes::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'gashapon_product_types';
        $data['page_title'] = 'Gashapon Product Types';
        $data['gashapon_product_types'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia("GashaponProductTypes/GashaponProductTypes", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'product_type_description' => 'required|string|max:50|unique:gashapon_product_types,product_type_description',
        ]);

        try {

            GashaponProductTypes::create([
                'product_type_description' => $validatedFields['product_type_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Gashapon Product Type Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon Product Types', $e->getMessage());
            return back()->with(['message' => 'Gashapon Product Type Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'product_type_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $gashapon_product_types = GashaponProductTypes::find($request->id);

            if (!$gashapon_product_types) {
                return back()->with(['message' => 'Gashapon Product Type not found!', 'type' => 'error']);
            }
    
            $gashaponProductTypeDescriptionExist = GashaponProductTypes::where('product_type_description', $request->product_type_description)->exists();


            if ($request->product_type_description !== $gashapon_product_types->product_type_description) {
                if (!$gashaponProductTypeDescriptionExist) {
                    $gashapon_product_types->product_type_description = $validatedFields['product_type_description'];
                } else {
                    return back()->with(['message' => 'Gashapon Product Type Description already exists!', 'type' => 'error']);
                }
            }
    
            $gashapon_product_types->status = $validatedFields['status'];
            $gashapon_product_types->updated_by = CommonHelpers::myId();
            $gashapon_product_types->updated_at = now();
    
            $gashapon_product_types->save();
    
            return back()->with(['message' => 'Gashapon Product Type Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Gashapon Product Types', $e->getMessage());
            return back()->with(['message' => 'Gashapon Product Type Updating Failed!', 'type' => 'error']);
        }
    }
}
