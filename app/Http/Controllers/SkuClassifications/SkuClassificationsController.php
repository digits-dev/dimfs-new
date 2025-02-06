<?php

namespace App\Http\Controllers\SkuClassifications;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\SkuClassifications;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class SkuClassificationsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'sku_classifications.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = SkuClassifications::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'sku_classifications';
        $data['page_title'] = 'SKU Classifications';
        $data['sku_classifications'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("SkuClassifications/SkuClassifications", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'sku_class_description' => 'required|string|max:255',
        ]);

        try {

            SkuClassifications::create([
                'sku_class_description' => $validatedFields['sku_class_description'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'SKU Classification Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('SkuClassifications', $e->getMessage());
            return back()->with(['message' => 'SKU Classification Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'sku_class_description' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $sku_classifications = SkuClassifications::find($request->id);

            if (!$sku_classifications) {
                return back()->with(['message' => 'SKU Classification not found!', 'type' => 'error']);
            }
    
            $sku_classifications->sku_class_description = $validatedFields['sku_class_description'];
            $sku_classifications->status = $validatedFields['status'];
            $sku_classifications->updated_by = CommonHelpers::myId();
            $sku_classifications->updated_at = now();
    
            $sku_classifications->save();
    
            return back()->with(['message' => 'SKU Classification Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('SKU Classifications', $e->getMessage());
            return back()->with(['message' => 'SKU Classification Updating Failed!', 'type' => 'error']);
        }
    }
}