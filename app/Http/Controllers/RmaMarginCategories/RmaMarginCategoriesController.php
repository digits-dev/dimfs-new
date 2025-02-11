<?php

namespace App\Http\Controllers\RmaMarginCategories;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\RmaMarginCategories;
use App\Models\RmaSubClassifications;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class RmaMarginCategoriesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'rma_margin_categories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = RmaMarginCategories::query()->with(['getCreatedBy', 'getUpdatedBy', 'getRmaSubClassification']);
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
        $data['tableName'] = 'rma_margin_categories';
        $data['page_title'] = 'RMA Margin Categories';
        $data['rma_margin_categories'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_rma_sub_classifications'] = RmaSubClassifications::select('id', 'sub_classification_description as name', 'status')
            ->where('status', 'ACTIVE')
            ->get();
        $data['all_rma_sub_classifications'] = RmaSubClassifications::select('id', 'sub_classification_description as name', 'status')     
            ->get();

        return Inertia::render("RmaMarginCategories/RmaMarginCategories", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'rma_sub_classifications_id' => 'required|integer',
            'margin_category_code' => 'required|string|max:3|unique:rma_margin_categories,margin_category_code',
            'margin_category_description' => 'required|string|max:30|unique:rma_margin_categories,margin_category_description',
        ]);

        try {

            RmaMarginCategories::create([
                'rma_sub_classifications_id' => $validatedFields['rma_sub_classifications_id'],   
                'margin_category_code' => $validatedFields['margin_category_code'],   
                'margin_category_description' => $validatedFields['margin_category_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'RMA Margin Category Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('RMA Margin Categories', $e->getMessage());
            return back()->with(['message' => 'RMA Margin Category Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'rma_sub_classifications_id' => 'required|integer',
            'margin_category_code' => 'required|string|max:3',
            'margin_category_description' => 'required|string|max:30',
            'status' => 'required|string',
        ]);

        try {
    
            $rma_margin_categories = RmaMarginCategories::find($request->id);

            if (!$rma_margin_categories) {
                return back()->with(['message' => 'RMA Margin Category not found!', 'type' => 'error']);
            }

            $rma_margin_categories->rma_sub_classifications_id = $validatedFields['rma_sub_classifications_id'];
    
            $MarginCategoryCodeExist = RmaMarginCategories::where('margin_category_code', $request->margin_category_code)->exists();
            $MarginCategoryDescriptionExist = RmaMarginCategories::where('margin_category_description', $request->margin_category_description)->exists();


            if ($request->margin_category_code !== $rma_margin_categories->margin_category_code) {
                if (!$MarginCategoryCodeExist) {
                    $rma_margin_categories->margin_category_code = $validatedFields['margin_category_code'];
                } else {
                    return back()->with(['message' => 'Margin Category Code already exists!', 'type' => 'error']);
                }
            }

            if ($request->margin_category_description !== $rma_margin_categories->margin_category_description) {
                if (!$MarginCategoryDescriptionExist) {
                    $rma_margin_categories->margin_category_description = $validatedFields['margin_category_description'];
                } else {
                    return back()->with(['message' => 'Margin Category Description already exists!', 'type' => 'error']);
                }
            }
    
            $rma_margin_categories->status = $validatedFields['status'];
            $rma_margin_categories->updated_by = CommonHelpers::myId();
            $rma_margin_categories->updated_at = now();
    
            $rma_margin_categories->save();
    
            return back()->with(['message' => 'RMA Margin Category Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('RMA Margin Categories', $e->getMessage());
            return back()->with(['message' => 'RMA Margin Category Updating Failed!', 'type' => 'error']);
        }
    }

    public function export(Request $request)
    {
        try {

            $headers = [
                'RMA Margin Category Code',
                'RMA Margin Category Description',
                'RMA Sub Classification Description',
                'Status',
                'Created By',
                'Updated By',
                'Created At',
                'Updated At',
            ];
    
            $columns = [
                'margin_category_code',
                'margin_category_description',
                'getRmaSubClassification.sub_classification_description',
                'status',
                'getCreatedBy.name',
                'getUpdatedBy.name',
                'created_at',
                'updated_at',
            ];
    
            $filename = "RMA Margin Categories - " . date ('Y-m-d H:i:s');
            $query = self::getAllData();
            return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');
        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('RMA Margin Categories', $e->getMessage());
        }

    }
}
