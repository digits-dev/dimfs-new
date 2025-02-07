<?php

namespace App\Http\Controllers\SkuStatuses;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\SkuStatuses;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class SkuStatusesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'sku_statuses.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = SkuStatuses::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'sku_statuses';
        $data['page_title'] = 'Sku Statuses';
        $data['sku_statuses'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("SkuStatuses/SkuStatuses", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'sku_status_code' => 'required|string|max:10|unique:sku_statuses,sku_status_code',
            'sku_status_description' => 'required|string|max:255',
        ]);

        try {

            SkuStatuses::create([
                'sku_status_code' => $validatedFields['sku_status_code'],
                'sku_status_description' => $validatedFields['sku_status_description'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Sku Status Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('SkuStatuses', $e->getMessage());
            return back()->with(['message' => 'Sku Status Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'sku_status_code' => 'required|string|max:10',
            'sku_status_description' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $sku_statuses = SkuStatuses::find($request->id);

            if (!$sku_statuses) {
                return back()->with(['message' => 'Sku Status not found!', 'type' => 'error']);
            }
    
            $SkuStatusCodeExist = SkuStatuses::where('sku_status_code', $request->sku_status_code)->exists();

            if ($request->sku_status_code !== $sku_statuses->sku_status_code) {
                if (!$SkuStatusCodeExist) {
                    $sku_statuses->sku_status_code = $validatedFields['sku_status_code'];
                } else {
                    return back()->with(['message' => 'Sku Status Code already exists!', 'type' => 'error']);
                }
            }
    
            $sku_statuses->sku_status_description = $validatedFields['sku_status_description'];
            $sku_statuses->status = $validatedFields['status'];
            $sku_statuses->updated_by = CommonHelpers::myId();
            $sku_statuses->updated_at = now();
    
            $sku_statuses->save();
    
            return back()->with(['message' => 'Sku Status Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Sku Statuses', $e->getMessage());
            return back()->with(['message' => 'Sku Status Updating Failed!', 'type' => 'error']);
        }
    }
}