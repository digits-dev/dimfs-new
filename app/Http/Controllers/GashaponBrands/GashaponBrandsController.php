<?php

namespace App\Http\Controllers\GashaponBrands;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\GashaponBrands;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class GashaponBrandsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'gashapon_brands.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = GashaponBrands::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'gashapon_brands';
        $data['page_title'] = 'Gashapon Brands';
        $data['gashapon_brands'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();
        return Inertia::render("GashaponBrands/GashaponBrands", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'brand_description' => 'required|string|max:50|unique:gashapon_brands,brand_description',
        ]);

        try {

            GashaponBrands::create([
                'brand_description' => $validatedFields['brand_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Gashapon Brand Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon Brands', $e->getMessage());
            return back()->with(['message' => 'Gashapon Brand Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'brand_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $gashapon_brands = GashaponBrands::find($request->id);

            if (!$gashapon_brands) {
                return back()->with(['message' => 'Gashapon Brand not found!', 'type' => 'error']);
            }
    
            $gashaponBrandDescriptionExist = GashaponBrands::where('brand_description', $request->brand_description)->exists();


            if ($request->brand_description !== $gashapon_brands->brand_description) {
                if (!$gashaponBrandDescriptionExist) {
                    $gashapon_brands->brand_description = $validatedFields['brand_description'];
                } else {
                    return back()->with(['message' => 'Gashapon Brand Description already exists!', 'type' => 'error']);
                }
            }
    
            $gashapon_brands->status = $validatedFields['status'];
            $gashapon_brands->updated_by = CommonHelpers::myId();
            $gashapon_brands->updated_at = now();
    
            $gashapon_brands->save();
    
            return back()->with(['message' => 'Gashapon Brand Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Gashapon Brands', $e->getMessage());
            return back()->with(['message' => 'Gashapon Brand Updating Failed!', 'type' => 'error']);
        }
    }

    public function export(Request $request)
    {

        $headers = [
            'Brand Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'brand_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Gashapon Brands - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}
