<?php

namespace App\Http\Controllers\MarginCategories;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\MarginCategories;
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

class MarginCategoriesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'margin_categories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = MarginCategories::query()->with(['getCreatedBy', 'getUpdatedBy', 'getSubClassification']);
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
        $data['tableName'] = 'margin_categories';
        $data['page_title'] = 'Margin Categories';
        $data['margin_categories'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_sub_classifications'] = SubClassifications::select('id', 'subclass_description as name', 'status')
            ->where('status', 'ACTIVE')
            ->get();
        $data['all_sub_classifications'] = SubClassifications::select('id', 'subclass_description as name', 'status')     
            ->get();

        return Inertia::render("MarginCategories/MarginCategories", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'sub_classifications_id' => 'required|integer',
            'margin_category_code' => 'required|string|max:10|unique:margin_categories,margin_category_code',
            'margin_category_description' => 'required|string|max:255',
        ]);
        
        try {

            MarginCategories::create([
                'sub_classifications_id' => $validatedFields['sub_classifications_id'],    
                'margin_category_code' => $validatedFields['margin_category_code'],
                'margin_category_description' => $validatedFields['margin_category_description'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
         
    
            return back()->with(['message' => 'Margin Category Creation Success!', 'type' => 'success']);

        }
        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Margin Categories', $e->getMessage());
            return back()->with(['message' => 'Margin Category Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'sub_classifications_id' => 'required|integer',
            'margin_category_code' => 'required|string|max:10',
            'margin_category_description' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $margin_categories = MarginCategories::find($request->id);

            if (!$margin_categories) {
                return back()->with(['message' => 'Margin Category not found!', 'type' => 'error']);
            }
    
            $SubClassificationsIdExist = MarginCategories::where('sub_classifications_id', $request->sub_classifications_id)->exists();
            $MarginCategoryCodeExist = MarginCategories::where('margin_category_code', $request->margin_category_code)->exists();

            if ($request->sub_classifications_id !== $margin_categories->sub_classifications_id) {
                if (!$SubClassificationsIdExist) {
                    $margin_categories->sub_classifications_id = $validatedFields['sub_classifications_id'];
                } else {
                    return back()->with(['message' => 'Sub Classification ID already exists!', 'type' => 'error']);
                }
            }
            
            if ($request->margin_category_code !== $margin_categories->margin_category_code) {
                if (!$MarginCategoryCodeExist) {
                    $margin_categories->margin_category_code = $validatedFields['margin_category_code'];
                } else {
                    return back()->with(['message' => 'Margin Category Code already exists!', 'type' => 'error']);
                }
            }
    
            $margin_categories->margin_category_description = $validatedFields['margin_category_description'];
            $margin_categories->status = $validatedFields['status'];
            $margin_categories->updated_by = CommonHelpers::myId();
            $margin_categories->updated_at = now();
    
            $margin_categories->save();
    
            return back()->with(['message' => 'Margin Category Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('MarginCategories', $e->getMessage());
            return back()->with(['message' => 'Margin Category Updating Failed!', 'type' => 'error']);
        }
    }
    public function export(Request $request)
    {

        $headers = [
            'Margin Category Code',
            'Margin Category Description',
            'Sub Classification Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'margin_category_code',
            'margin_category_description',
            'getSubClassification.subclass_description',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Margin Categories - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}