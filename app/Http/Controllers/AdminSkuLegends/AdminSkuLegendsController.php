<?php

namespace App\Http\Controllers\AdminSkuLegends;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminSkuLegend;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class AdminSkuLegendsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_sku_legends.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminSkuLegend::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'admin_sku_legends';
        $data['page_title'] = 'Admin SKU Legends';
        $data['admin_sku_legends'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("AdminSkuLegends/AdminSkuLegends", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'sku_legend_description' => 'required|string|max:20|unique:admin_sku_legends,sku_legend_description',
        ]);

        try {

            AdminSkuLegend::create([
                'sku_legend_description' => $validatedFields['sku_legend_description'],       
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'SKU Legend Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin SKU Legends', $e->getMessage());
            return back()->with(['message' => 'SKU Legend Creation Failed!', 'type' => 'error']);
        }
    
    }
}
