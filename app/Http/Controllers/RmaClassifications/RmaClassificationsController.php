<?php

namespace App\Http\Controllers\RmaClassifications;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\RmaCategories;
use App\Models\RmaClassifications;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class RmaClassificationsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'rma_classifications.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = RmaClassifications::query()->with(['getCreatedBy', 'getUpdatedBy', 'getRmaCategory']);
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
        $data['tableName'] = 'rma_classifications';
        $data['page_title'] = 'RMA Classifications';
        $data['rma_classifications'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_rma_categories'] = RmaCategories::select('id', 'category_description as name', 'status')
            ->where('status', 'ACTIVE')
            ->get();
        $data['all_rma_categories'] = RmaCategories::select('id', 'category_description as name', 'status')     
            ->get();

        return Inertia::render("RmaClassifications/RmaClassifications", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'rma_categories_id' => 'required|integer',
            'class_code' => 'required|string|max:3|unique:rma_classifications,class_code',
            'class_description' => 'required|string|max:30|unique:rma_classifications,class_description',
        ]);

        try {

            RmaClassifications::create([
                'rma_categories_id' => $validatedFields['rma_categories_id'],   
                'class_code' => $validatedFields['class_code'],   
                'class_description' => $validatedFields['class_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'RMA Classification Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('RMA Classifications', $e->getMessage());
            return back()->with(['message' => 'RMA Classification Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'rma_categories_id' => 'required|integer',
            'class_code' => 'required|string|max:3',
            'class_description' => 'required|string|max:30',
            'status' => 'required|string',
        ]);

        try {
    
            $rma_classifications = RmaClassifications::find($request->id);

            if (!$rma_classifications) {
                return back()->with(['message' => 'RMA Classification not found!', 'type' => 'error']);
            }

            
            $ClassCodeExist = RmaClassifications::where('class_code', $request->class_code)->exists();
            $ClassDescriptionExist = RmaClassifications::where('class_description', $request->class_description)->exists();
            $RmaCategoriesIdExist = RmaCategories::where('id', $validatedFields['rma_categories_id'])->exists();

            if ($RmaCategoriesIdExist) {
                $rma_classifications->rma_categories_id = $validatedFields['rma_categories_id'];
            } else {
                return back()->with(['message' => 'RMA Category not found', 'type' => 'error']);
            }

            if ($request->class_code !== $rma_classifications->class_code) {
                if (!$ClassCodeExist) {
                    $rma_classifications->class_code = $validatedFields['class_code'];
                } else {
                    return back()->with(['message' => 'RMA Class Code already exists!', 'type' => 'error']);
                }
            }

            if ($request->class_description !== $rma_classifications->class_description) {
                if (!$ClassDescriptionExist) {
                    $rma_classifications->class_description = $validatedFields['class_description'];
                } else {
                    return back()->with(['message' => 'RMA Class Description already exists!', 'type' => 'error']);
                }
            }
    
            $rma_classifications->status = $validatedFields['status'];
            $rma_classifications->updated_by = CommonHelpers::myId();
            $rma_classifications->updated_at = now();
    
            $rma_classifications->save();
    
            return back()->with(['message' => 'RMA Classification Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('RMA Classifications', $e->getMessage());
            return back()->with(['message' => 'RMA Classification Updating Failed!', 'type' => 'error']);
        }
    }
}
