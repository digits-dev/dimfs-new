<?php

namespace App\Http\Controllers\EcommMarginMatrices;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\Brands;
use App\Models\EcommMarginMatrix;
use App\Models\VendorTypes;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class EcommMarginMatricesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'ecomm_margin_matrices.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = EcommMarginMatrix::query()->with(['getCreatedBy', 'getUpdatedBy', 'getBrand', 'getVendorType']);
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
        $data['tableName'] = 'ecomm_margin_matrices';
        $data['page_title'] = 'Margin Matrix (ECOMM)';
        $data['ecomm_margin_matrices'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        // BRANDS
        $data['all_active_brands'] = Brands::select('id', 'brand_description as name', 'status')
            ->where('status', 'ACTIVE')
            ->get();
        $data['all_brands'] = Brands::select('id', 'brand_description as name', 'status')     
            ->get();

        // VENDOR TYPES
        $data['all_active_vendor_types'] = VendorTypes::select('id', 'vendor_type_description as name', 'status')
            ->where('status', 'ACTIVE')
            ->get();
        $data['all_vendor_types'] = VendorTypes::select('id', 'vendor_type_description as name', 'status')     
            ->get();

        return Inertia::render("EcommMarginMatrices/EcommMarginMatrices", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'brands_id' => 'nullable|integer',
            'margin_category' => 'required|string',
            'max' => 'required|numeric',
            'min' => 'required|numeric',
            'store_margin_percentage' => 'required|numeric',
            'matrix_type' => 'required|string',
            'vendor_types_id' => 'nullable|integer',
        ]);

        try {

            EcommMarginMatrix::create([
                'brands_id' => $validatedFields['brands_id'], 
                'margin_category' => $validatedFields['margin_category'],   
                'max' => $validatedFields['max'],  
                'min' => $validatedFields['min'],
                'store_margin_percentage' => $validatedFields['store_margin_percentage'],
                'vendor_types_id' => $validatedFields['vendor_types_id'],
                'matrix_type' => $validatedFields['matrix_type'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);

            return back()->with(['message' => 'Margin Matrix Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Margin Matrix (ECOMM)', $e->getMessage());
            return back()->with(['message' => 'Margin Matrix Creation Failed!', 'type' => 'error']);
        }

    }


    
    public function update(Request $request){

        $validatedFields = $request->validate([
            'brands_id' => 'nullable|integer',
            'margin_category' => 'required|string',
            'max' => 'required|numeric',
            'min' => 'required|numeric',
            'store_margin_percentage' => 'required|numeric',
            'matrix_type' => 'required|string',
            'vendor_types_id' => 'nullable|integer',
            'status' => 'required|string',
        ]);

        try {
    
            $margin_matrix = EcommMarginMatrix::find($request->id);

            if (!$margin_matrix) {
                return back()->with(['message' => 'Matrix not found!', 'type' => 'error']);
            }
    
            $margin_matrix->brands_id = $validatedFields['brands_id'];
            $margin_matrix->margin_category = $validatedFields['margin_category'];
            $margin_matrix->max = $validatedFields['max'];
            $margin_matrix->min = $validatedFields['min'];
            $margin_matrix->store_margin_percentage = $validatedFields['store_margin_percentage'];
            $margin_matrix->matrix_type = $validatedFields['matrix_type'];
            $margin_matrix->vendor_types_id = $validatedFields['vendor_types_id'];
            $margin_matrix->status = $validatedFields['status'];
            $margin_matrix->updated_at = now();
    
            $margin_matrix->save();

            return back()->with(['message' => 'Margin Matrix Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Margin Matrix (ECOMM)', $e->getMessage());
            return back()->with(['message' => 'Margin Matrix Updating Failed!', 'type' => 'error']);
        }
    }

    public function export()
    {

        $headers = [
            'Brand',
            'Margin Category',
            'Max',
            'Min',
            'Store Margin (%)',
            'Type',
            'Vendor Type',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'getBrand.brand_description',
            'margin_category',
            'max',
            'min',
            'store_margin_percentage',
            'matrix_type',
            'getVendorType.vendor_type_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Margin Matrix ECOMM - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}
