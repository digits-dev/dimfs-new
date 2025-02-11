<?php

namespace App\Http\Controllers\RmaCategories;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\RmaCategories;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class RmaCategoriesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'rma_categories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = RmaCategories::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'rma_categories';
        $data['page_title'] = 'RMA Categories';
        $data['rma_categories'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("RmaCategories/RmaCategories", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'category_code' => 'required|string|max:3|unique:rma_categories,category_code',
            'category_description' => 'required|string|max:30|unique:rma_categories,category_description',
        ]);

        try {

            RmaCategories::create([
                'category_code' => $validatedFields['category_code'],   
                'category_description' => $validatedFields['category_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'RMA Category Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('RMA Categories', $e->getMessage());
            return back()->with(['message' => 'RMA Category Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'category_code' => 'required|string|max:3',
            'category_description' => 'required|string|max:30',
            'status' => 'required|string',
        ]);

        try {
    
            $rma_categories = RmaCategories::find($request->id);

            if (!$rma_categories) {
                return back()->with(['message' => 'RMA Category not found!', 'type' => 'error']);
            }
    
            $RmaCategoryCodeExist = RmaCategories::where('category_code', $request->category_code)->exists();
            $RmaCategoryDescriptionExist = RmaCategories::where('category_description', $request->category_description )->exists();


            if ($request->category_code !== $rma_categories->category_code) {
                if (!$RmaCategoryCodeExist) {
                    $rma_categories->category_code = $validatedFields['category_code'];
                } else {
                    return back()->with(['message' => 'RMA Category Code already exists!', 'type' => 'error']);
                }
            }

            if ($request->category_description !== $rma_categories->category_description) {
                if (!$RmaCategoryDescriptionExist) {
                    $rma_categories->category_description = $validatedFields['category_description'];
                } else {
                    return back()->with(['message' => 'RMA Category Description already exists!', 'type' => 'error']);
                }
            }
    
            $rma_categories->status = $validatedFields['status'];
            $rma_categories->updated_by = CommonHelpers::myId();
            $rma_categories->updated_at = now();
    
            $rma_categories->save();
    
            return back()->with(['message' => 'RMA Category Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('RMA Categories', $e->getMessage());
            return back()->with(['message' => 'RMA Category Updating Failed!', 'type' => 'error']);
        }
    }

    public function export(Request $request)
    {
        try {

            $headers = [
                'Category Code',
                'Category Description',
                'Status',
                'Created By',
                'Updated By',
                'Created At',
                'Updated At',
            ];

            $columns = [
                'category_code',
                'category_description',
                'status',
                'getCreatedBy.name',
                'getUpdatedBy.name',
                'created_at',
                'updated_at',
            ];

            $filename = "RMA Categories - " . date ('Y-m-d H:i:s');
            $query = self::getAllData();
            return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('RMA Categories', $e->getMessage());
        }

    }
}
