<?php

namespace App\Http\Controllers\Classifications;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Classifications;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubmasterExport;

class ClassificationsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'classifications.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }


    public function getAllData(){
        $query = Classifications::query()->with(['getCreatedBy', 'getUpdatedBy', 'getCategory']);
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
        $data['tableName'] = 'classifications';
        $data['page_title'] = 'Classifications';
        $data['classifications'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_categories'] = Categories::select('id', 'category_description as name', 'status')
            ->where('status', 'ACTIVE')
            ->get();
        $data['all_categories'] = Categories::select('id', 'category_description as name', 'status')     
            ->get();

        return Inertia::render("Classifications/Classifications", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'categories_id' => 'required',
            'class_code' => 'required|string|max:3|unique:classifications,class_code',
            'class_description' => 'required|string|max:30|unique:classifications,class_description',
        ]);

        try {

            Classifications::create([
                'categories_id' => $validatedFields['categories_id'], 
                'class_code' => $validatedFields['class_code'],   
                'class_description' => $validatedFields['class_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Classification Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Classifications', $e->getMessage());
            return back()->with(['message' => 'Classification Creation Failed!', 'type' => 'error']);
        }
          
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'categories_id' => 'required',
            'class_code' => 'required|string|max:3',
            'class_description' => 'required|string|max:30',
            'status' => 'required|string',
        ]);

        try {
    
            $classifications = Classifications::find($request->id);

            if (!$classifications) {
                return back()->with(['message' => 'Classification not found!', 'type' => 'error']);
            }

            $classifications->categories_id = $validatedFields['categories_id'];
    
            $classCodeExist = Classifications::where('class_code', $request->class_code)->exists();
            $classDescriptionExist = Classifications::where('class_description', $request->class_description )->exists();

            if ($request->class_code !== $classifications->class_code) {
                if (!$classCodeExist) {
                    $classifications->class_code = $validatedFields['class_code'];
                } else {
                    return back()->with(['message' => 'Class code already exists!', 'type' => 'error']);
                }
            }

            if ($request->class_description !== $classifications->class_description) {
                if (!$classDescriptionExist) {
                    $classifications->class_description = $validatedFields['class_description'];
                } else {
                    return back()->with(['message' => 'Class Description already exists!', 'type' => 'error']);
                }
            }
    
            $classifications->status = $validatedFields['status'];
            $classifications->updated_by = CommonHelpers::myId();
            $classifications->updated_at = now();
    
            $classifications->save();
    
            return back()->with(['message' => 'Classification Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Classifications', $e->getMessage());
            return back()->with(['message' => 'Classification Updating Failed!', 'type' => 'error']);
        }
    }

    
    public function export(Request $request)
    {

        $headers = [
            'Class Code',
            'Class Description',
            'Category Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'class_code',
            'class_description',
            'getCategory.category_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Classfications - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}