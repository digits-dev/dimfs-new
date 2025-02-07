<?php

namespace App\Http\Controllers\Vendors;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\Vendors;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

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
        $query = Vendors::query()->with(['getCreatedBy', 'getUpdatedBy']);
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

        return Inertia::render("Vendors/Vendors", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'brands_id' => 'required|string|max:3',
            'vendor_name' => 'required|string|max:255',
            'vendor_types_id' => 'required|string|max:3',
            'incoterms_id' => 'required|string|max:3',
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
            'brands_id' => 'required|string|max:3',
            'vendor_name' => 'required|string|max:255',
            'vendor_types_id' => 'required|string|max:3',
            'incoterms_id' => 'required|string|max:3',
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
}