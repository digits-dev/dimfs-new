<?php

namespace App\Http\Controllers\Categories;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class CategoriesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'categories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = Categories::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'categories';
        $data['page_title'] = 'Categories';
        $data['categories'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();
        return Inertia::render("Categories/Categories", $data);
        
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'category_code' => 'required|string|max:3|unique:categories,category_code',
            'category_description' => 'required|string|max:30|unique:categories,category_description',
        ]);

        try {

            Categories::create([
                'category_code' => $validatedFields['category_code'], 
                'category_description' => $validatedFields['category_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Category Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Categories', $e->getMessage());
            return back()->with(['message' => 'Category Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'category_code' => 'required|string|max:3',
            'category_description' => 'required|string|max:30',
            'status' => 'required|string',
        ]);

        try {
    
            $categories = Categories::find($request->id);

            if (!$categories) {
                return back()->with(['message' => 'Category not found!', 'type' => 'error']);
            }
    
            $categoriesCodeExist = Categories::where('category_code', $request->category_code)->exists();
            $categoriesDescriptionExist = Categories::where('category_description', $request->category_description)->exists();

            if ($request->category_code !== $categories->category_code) {
                if (!$categoriesCodeExist) {
                    $categories->category_code = $validatedFields['category_code'];
                } else {
                    return back()->with(['message' => 'Category code already exists!', 'type' => 'error']);
                }
            }
            if ($request->category_description !== $categories->category_description) {
                if (!$categoriesDescriptionExist) {
                    $categories->category_description = $validatedFields['category_description'];
                } else {
                    return back()->with(['message' => 'Category Description already exists!', 'type' => 'error']);
                }
            }
    
            $categories->status = $validatedFields['status'];
            $categories->updated_by = CommonHelpers::myId();
            $categories->updated_at = now();
    
            $categories->save();
    
            return back()->with(['message' => 'Category Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Categories', $e->getMessage());
            return back()->with(['message' => 'Category Updating Failed!', 'type' => 'error']);
        }
    }

}
