<?php

namespace App\Http\Controllers\BrandMarketings;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\BrandMarketings;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class BrandMarketingsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'brand_marketings.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = BrandMarketings::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'brand_marketings';
        $data['page_title'] = 'Brand Groups';
        $data['brand_marketings'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("BrandMarketings/BrandMarketings", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'brand_marketing_description' => 'required|string|max:50',
        ]);

        try {

            BrandMarketings::create([
                'brand_marketing_description' => $validatedFields['brand_marketing_description'], 
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
                'created_at' => now(),
            ]);
    
            return back()->with(['message' => 'Brand Marketing Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Brand Marketings', $e->getMessage());
            return back()->with(['message' => 'Brand Marketing Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'brand_marketing_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $brandMarketings = BrandMarketings::find($request->id);

            if (!$brandMarketings) {
                return back()->with(['message' => 'Brand Marketing not found!', 'type' => 'error']);
            }

            $brandMarketings->brand_marketing_description = $validatedFields['brand_marketing_description'];
            $brandMarketings->status = $validatedFields['status'];
            $brandMarketings->updated_by = CommonHelpers::myId();
            $brandMarketings->updated_at = now();
            $brandMarketings->save();
    
            return back()->with(['message' => 'Brand Marketing Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Brand Marketings', $e->getMessage());
            return back()->with(['message' => 'Brand Marketing Updating Failed!', 'type' => 'error']);
        }
    }
}
