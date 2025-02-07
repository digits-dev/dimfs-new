<?php

namespace App\Http\Controllers\RmaStoreCategories;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\RmaStoreCategories;
use App\Models\RmaSubClassifications;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class RmaStoreCategoriesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'rma_store_categories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = RmaStoreCategories::query()->with(['getCreatedBy', 'getUpdatedBy', 'getRmaSubClassification']);
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
        $data['tableName'] = 'rma_store_categories';
        $data['page_title'] = 'RMA Store Categories';
        $data['rma_store_categories'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_rma_sub_classifications'] = RmaSubClassifications::select('id', 'sub_classification_description as name', 'status')
            ->where('status', 'ACTIVE')
            ->get();
        $data['all_rma_sub_classifications'] = RmaSubClassifications::select('id', 'sub_classification_description as name', 'status')     
            ->get();

        return Inertia::render("RmaStoreCategories/RmaStoreCategories", $data);
    }

    
    public function create(Request $request){

        $validatedFields = $request->validate([
            'rma_sub_classifications_id' => 'required|integer',
            'store_category_description' => 'required|string|max:30|unique:rma_store_categories,store_category_description',
        ]);

        try {

            RmaStoreCategories::create([
                'rma_sub_classifications_id' => $validatedFields['rma_sub_classifications_id'],   
                'store_category_description' => $validatedFields['store_category_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'RMA Store Category Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('RMA Store Categories', $e->getMessage());
            return back()->with(['message' => 'RMA Store Category Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'rma_sub_classifications_id' => 'required|integer',
            'store_category_description' => 'required|string|max:30',
            'status' => 'required|string',
        ]);

        try {
    
            $rma_store_categories = RmaStoreCategories::find($request->id);

            if (!$rma_store_categories) {
                return back()->with(['message' => 'RMA Store Category not found!', 'type' => 'error']);
            }

            $rma_store_categories->rma_sub_classifications_id = $validatedFields['rma_sub_classifications_id'];
    
            $StoreCategoryDescriptionExist = RmaStoreCategories::where('store_category_description', $request->store_category_description)->exists();

            if ($request->store_category_description !== $rma_store_categories->store_category_description) {
                if (!$StoreCategoryDescriptionExist) {
                    $rma_store_categories->store_category_description = $validatedFields['store_category_description'];
                } else {
                    return back()->with(['message' => 'Store Category Description already exists!', 'type' => 'error']);
                }
            }
    
            $rma_store_categories->status = $validatedFields['status'];
            $rma_store_categories->updated_by = CommonHelpers::myId();
            $rma_store_categories->updated_at = now();
    
            $rma_store_categories->save();
    
            return back()->with(['message' => 'RMA Store Category Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('RMA Store Categories', $e->getMessage());
            return back()->with(['message' => 'RMA Store Category Updating Failed!', 'type' => 'error']);
        }
    }
}
