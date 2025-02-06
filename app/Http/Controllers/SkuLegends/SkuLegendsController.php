<?php

namespace App\Http\Controllers\SkuLegends;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\SkuLegends;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class SkuLegendsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'sku_legends.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = SkuLegends::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'sku_legends';
        $data['page_title'] = 'SKU Legends';
        $data['sku_legends'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("SkuLegends/SkuLegends", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'sku_legend_description' => 'required|string|max:255',
        ]);

        try {

            SkuLegends::create([
                'sku_legend_description' => $validatedFields['sku_legend_description'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'SKU Legend Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('SkuLegends', $e->getMessage());
            return back()->with(['message' => 'SKU Legend Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'sku_legend_description' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $sku_legends = SkuLegends::find($request->id);

            if (!$sku_legends) {
                return back()->with(['message' => 'SKU Legend not found!', 'type' => 'error']);
            }
    
            $sku_legends->sku_legend_description = $validatedFields['sku_legend_description'];
            $sku_legends->status = $validatedFields['status'];
            $sku_legends->updated_by = CommonHelpers::myId();
            $sku_legends->updated_at = now();
    
            $sku_legends->save();
    
            return back()->with(['message' => 'SKU Legend Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('SkuLegends', $e->getMessage());
            return back()->with(['message' => 'SKU Legend Updating Failed!', 'type' => 'error']);
        }
    }
}