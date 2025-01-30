<?php

namespace App\Http\Controllers\BrandDirections;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\BrandDirections;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class BrandDirectionsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'brand_directions.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = BrandDirections::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'brand_directions';
        $data['page_title'] = 'Brand Directions';
        $data['brand_directions'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("BrandDirections/BrandDirections", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'brand_direction_description' => 'required|string|max:50',
        ]);

        try {

            BrandDirections::create([
                'brand_direction_description' => $validatedFields['brand_direction_description'], 
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Brand Direction Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Brand Directions', $e->getMessage());
            return back()->with(['message' => 'Brand Direction Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'brand_direction_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $brandsDirection = BrandDirections::find($request->id);

            if (!$brandsDirection) {
                return back()->with(['message' => 'Brand Direction not found!', 'type' => 'error']);
            }

            $brandsDirection->brand_direction_description = $validatedFields['brand_direction_description'];
            $brandsDirection->status = $validatedFields['status'];
            $brandsDirection->updated_by = CommonHelpers::myId();
            $brandsDirection->updated_at = now();
            $brandsDirection->save();
    
            return back()->with(['message' => 'Brand Direction Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Brand Directions', $e->getMessage());
            return back()->with(['message' => 'Brand Direction Updating Failed!', 'type' => 'error']);
        }
    }
}
