<?php

namespace App\Http\Controllers\RmaModelSpecifics;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\RmaModelSpecifics;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class RmaModelSpecificsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'rma_model_specifics.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = RmaModelSpecifics::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'rma_model_specifics';
        $data['page_title'] = 'RMA Model Specifics';
        $data['rma_model_specifics'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("RmaModelSpecifics/RmaModelSpecifics", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'model_specific_code' => 'required|string|max:15|unique:rma_model_specifics,model_specific_code',
            'model_specific_description' => 'required|string|max:50|unique:rma_model_specifics,model_specific_description',
        ]);

        try {

            RmaModelSpecifics::create([
                'model_specific_code' => $validatedFields['model_specific_code'],   
                'model_specific_description' => $validatedFields['model_specific_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'RMA Model Specific Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('RMA Model Specifics', $e->getMessage());
            return back()->with(['message' => 'RMA Model Specific Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'model_specific_code' => 'required|string|max:15',
            'model_specific_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $rma_model_specifics = RmaModelSpecifics::find($request->id);

            if (!$rma_model_specifics) {
                return back()->with(['message' => 'RMA Model Specific not found!', 'type' => 'error']);
            }

    
            $ModelSpecificCodeExist = RmaModelSpecifics::where('model_specific_code', $request->model_specific_code)->exists();
            $ModelSpecificDescriptionExist = RmaModelSpecifics::where('model_specific_description', $request->model_specific_description)->exists();


            if ($request->model_specific_code !== $rma_model_specifics->model_specific_code) {
                if (!$ModelSpecificCodeExist) {
                    $rma_model_specifics->model_specific_code = $validatedFields['model_specific_code'];
                } else {
                    return back()->with(['message' => 'Model Specific Code already exists!', 'type' => 'error']);
                }
            }

            if ($request->model_specific_description !== $rma_model_specifics->model_specific_description) {
                if (!$ModelSpecificDescriptionExist) {
                    $rma_model_specifics->model_specific_description = $validatedFields['model_specific_description'];
                } else {
                    return back()->with(['message' => 'Model Specific Description already exists!', 'type' => 'error']);
                }
            }
    
            $rma_model_specifics->status = $validatedFields['status'];
            $rma_model_specifics->updated_by = CommonHelpers::myId();
            $rma_model_specifics->updated_at = now();
    
            $rma_model_specifics->save();
    
            return back()->with(['message' => 'RMA Model Specific Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('RMA Model Specifics', $e->getMessage());
            return back()->with(['message' => 'RMA Model Specific Updating Failed!', 'type' => 'error']);
        }
    }
}
