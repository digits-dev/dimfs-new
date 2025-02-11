<?php

namespace App\Http\Controllers\StoreCategories;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\StoreCategories;
use App\Models\SubClassifications;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubmasterExport;

class StoreCategoriesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'store_categories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = StoreCategories::query()->with(['getCreatedBy', 'getUpdatedBy', 'getSubClassification']);
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
        $data['tableName'] = 'store_categories';
        $data['page_title'] = 'Store Categories';
        $data['store_categories'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_sub_classifications'] = SubClassifications::select('id', 'subclass_description as name', 'status')
            ->where('status', 'ACTIVE')
            ->get();
        $data['all_sub_classifications'] = SubClassifications::select('id', 'subclass_description as name', 'status')     
            ->get();

        return Inertia::render("StoreCategories/StoreCategories", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'sub_classifications_id' => 'required|integer',
            'store_category_description' => 'required|string|max:255',
        ]);

        try {

            StoreCategories::create([
                'sub_classifications_id' => $validatedFields['sub_classifications_id'],
                'store_category_description' => $validatedFields['store_category_description'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Store Category Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('StoreCategories', $e->getMessage());
            return back()->with(['message' => 'Store Category Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'sub_classifications_id' => 'required|integer',
            'store_category_description' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $store_categories = StoreCategories::find($request->id);

            if (!$store_categories) {
                return back()->with(['message' => 'Store Category not found!', 'type' => 'error']);
            }
    
            $SubClassificationsIdExist = StoreCategories::where('sub_classifications_id', $request->sub_classifications_id)->exists();

            if ($request->sub_classifications_id !== $store_categories->sub_classifications_id) {
                if (!$SubClassificationsIdExist) {
                    $store_categories->sub_classifications_id = $validatedFields['sub_classifications_id'];
                } else {
                    return back()->with(['message' => 'Sub Classification ID already exists!', 'type' => 'error']);
                }
            }
    
            $store_categories->store_category_description = $validatedFields['store_category_description'];
            $store_categories->status = $validatedFields['status'];
            $store_categories->updated_by = CommonHelpers::myId();
            $store_categories->updated_at = now();
    
            $store_categories->save();
    
            return back()->with(['message' => 'Store Category Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('StoreCategories', $e->getMessage());
            return back()->with(['message' => 'Store Category Updating Failed!', 'type' => 'error']);
        }
    }
    public function export(Request $request)
    {

        $headers = [
            'Store Category Description',
            'Sub Classification Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'store_category_description',
            'getSubClassification.subclass_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Store Categories - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}