<?php

namespace App\Http\Controllers\Vendors;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\Brands;
use App\Models\Incoterms;
use App\Models\Vendors;
use App\Models\VendorTypes;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubmasterExport;

class VendorsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'vendors.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = Vendors::query()->with(['getCreatedBy', 'getUpdatedBy', 'getBrand', 'getVendorType', 'getIncoterm']);
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
        $data['tableName'] = 'vendors';
        $data['page_title'] = 'Vendors';
        $data['vendors'] = self::getAllData()->paginate($this->perPage)->withQueryString();
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

        // INCOTERMS

        $data['all_active_incoterms'] = Incoterms::select('id', 'incoterms_description as name', 'status')
            ->where('status', 'ACTIVE')
            ->get();
        $data['all_incoterms'] = Incoterms::select('id', 'incoterms_description as name', 'status')     
            ->get();

        return Inertia::render("Vendors/Vendors", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'brands_id' => 'required|integer',
            'vendor_types_id' => 'required|integer',
            'incoterms_id' => 'required|integer',
            'vendor_name' => 'required|string|max:255',
        ]);
        
        try {

            Vendors::create([
                'brands_id' => $validatedFields['brands_id'],
                'vendor_name' => $validatedFields['vendor_name'],
                'vendor_types_id' => $validatedFields['vendor_types_id'],
                'incoterms_id' => $validatedFields['incoterms_id'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Vendor Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Vendors', $e->getMessage());
            return back()->with(['message' => 'Vendor Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'brands_id' => 'required|integer',
            'vendor_types_id' => 'required|integer',
            'incoterms_id' => 'required|integer',
            'vendor_name' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $vendors = Vendors::find($request->id);

            if (!$vendors) {
                return back()->with(['message' => 'Vendor not found!', 'type' => 'error']);
            }
    
            $vendors->brands_id = $validatedFields['brands_id'];
            $vendors->vendor_name = $validatedFields['vendor_name'];
            $vendors->vendor_types_id = $validatedFields['vendor_types_id'];
            $vendors->incoterms_id = $validatedFields['incoterms_id'];
            $vendors->status = $validatedFields['status'];
            $vendors->updated_by = CommonHelpers::myId();
            $vendors->updated_at = now();
    
            $vendors->save();
    
            return back()->with(['message' => 'Vendor Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Vendors', $e->getMessage());
            return back()->with(['message' => 'Vendor Updating Failed!', 'type' => 'error']);
        }
    }
    public function export(Request $request)
    {

        $headers = [
            'Vendor Name',
            'Brand Description',
            'Vendor Type Description',
            'Incoterms Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'vendor_name',
            'getBrand.brand_description', 
            'getVendorType.vendor_type_description', 
            'getIncoterm.incoterms_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Vendors - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}