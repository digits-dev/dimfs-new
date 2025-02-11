<?php

namespace App\Http\Controllers\GashaponCategories;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\GashaponCategories;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class GashaponCategoriesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'gashapon_categories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = GashaponCategories::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'gashapon_categories';
        $data['page_title'] = 'Gashapon Categories';
        $data['gashapon_categories'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("GashaponCategories/GashaponCategories", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'category_description' => 'required|string|max:50|unique:gashapon_categories,category_description',
        ]);

        try {

            GashaponCategories::create([
                'category_description' => $validatedFields['category_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Gashapon Category Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon Categories', $e->getMessage());
            return back()->with(['message' => 'Gashapon Category Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'category_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $gashapon_categories = GashaponCategories::find($request->id);

            if (!$gashapon_categories) {
                return back()->with(['message' => 'Gashapon Category not found!', 'type' => 'error']);
            }
    
            $gashaponCategoryDescriptionExist = GashaponCategories::where('category_description', $request->category_description)->exists();


            if ($request->category_description !== $gashapon_categories->category_description) {
                if (!$gashaponCategoryDescriptionExist) {
                    $gashapon_categories->category_description = $validatedFields['category_description'];
                } else {
                    return back()->with(['message' => 'Gashapon Category Description already exists!', 'type' => 'error']);
                }
            }
    
            $gashapon_categories->status = $validatedFields['status'];
            $gashapon_categories->updated_by = CommonHelpers::myId();
            $gashapon_categories->updated_at = now();
    
            $gashapon_categories->save();
    
            return back()->with(['message' => 'Gashapon Category Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Gashapon Categories', $e->getMessage());
            return back()->with(['message' => 'Gashapon Category Updating Failed!', 'type' => 'error']);
        }
    }

    public function export(Request $request)
    {

        try {

            $headers = [
                'Category Description',
                'Status',
                'Created By',
                'Updated By',
                'Created At',
                'Updated At',
            ];
    
            $columns = [
                'category_description',
                'status',
                'getCreatedBy.name',
                'getUpdatedBy.name',
                'created_at',
                'updated_at',
            ];
    
            $filename = "Gashapon Categories - " . date ('Y-m-d H:i:s');
            $query = self::getAllData();
            return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon Categories', $e->getMessage());
        }

    }

}
