<?php

namespace App\Http\Controllers\SubCategories;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\SubCategories;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class SubCategoriesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'sub_categories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = SubCategories::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'sub_categories';
        $data['page_title'] = 'Sub Categories';
        $data['sub_categories'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("SubCategories/SubCategories", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'categories_id' => 'required|integer',
            'subcategory_code' => 'required|string|max:10|unique:sub_categories,subcategory_code',
            'subcategory_description' => 'required|string|max:255',
        ]);

        try {

            SubCategories::create([
                'categories_id' => $validatedFields['categories_id'],
                'subcategory_code' => $validatedFields['subcategory_code'],
                'subcategory_description' => $validatedFields['subcategory_description'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Sub Category Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('SubCategories', $e->getMessage());
            return back()->with(['message' => 'Sub Category Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'categories_id' => 'required|integer',
            'subcategory_code' => 'required|string|max:10',
            'subcategory_description' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $sub_categories = SubCategories::find($request->id);

            if (!$sub_categories) {
                return back()->with(['message' => 'Sub Category not found!', 'type' => 'error']);
            }
    
            $CategoriesIdExist = SubCategories::where('categories_id', $request->categories_id)->exists();
            $SubcategoryCodeExist = SubCategories::where('subcategory_code', $request->subcategory_code)->exists();

            if ($request->categories_id !== $sub_categories->categories_id) {
                if (!$CategoriesIdExist) {
                    $sub_categories->categories_id = $validatedFields['categories_id'];
                } else {
                    return back()->with(['message' => 'Category ID already exists!', 'type' => 'error']);
                }
            }
            
            if ($request->subcategory_code !== $sub_categories->subcategory_code) {
                if (!$SubcategoryCodeExist) {
                    $sub_categories->subcategory_code = $validatedFields['subcategory_code'];
                } else {
                    return back()->with(['message' => 'Subcategory Code already exists!', 'type' => 'error']);
                }
            }
    
            $sub_categories->subcategory_description = $validatedFields['subcategory_description'];
            $sub_categories->status = $validatedFields['status'];
            $sub_categories->updated_by = CommonHelpers::myId();
            $sub_categories->updated_at = now();
    
            $sub_categories->save();
    
            return back()->with(['message' => 'Sub Category Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('SubCategories', $e->getMessage());
            return back()->with(['message' => 'Sub Category Updating Failed!', 'type' => 'error']);
        }
    }
}