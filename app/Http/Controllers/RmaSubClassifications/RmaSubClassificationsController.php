<?php

namespace App\Http\Controllers\RmaSubClassifications;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\RmaClassifications;
use App\Models\RmaSubClassifications;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class RmaSubClassificationsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'rma_sub_classifications.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = RmaSubClassifications::query()->with(['getCreatedBy', 'getUpdatedBy', 'getRmaClassification']);
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
        $data['tableName'] = 'rma_sub_classifications';
        $data['page_title'] = 'RMA Sub Classifications';
        $data['rma_sub_classifications'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_rma_classifications'] = RmaClassifications::select('id', 'class_description as name', 'status')
            ->where('status', 'ACTIVE')
            ->get();
        $data['all_rma_classifications'] = RmaClassifications::select('id', 'class_description as name', 'status')     
            ->get();

        return Inertia::render("RmaSubClassifications/RmaSubClassifications", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'rma_classifications_id' => 'required|integer',
            'sub_classification_description' => 'required|string|max:50|unique:rma_sub_classifications,sub_classification_description',
        ]);

        try {

            RmaSubClassifications::create([
                'rma_classifications_id' => $validatedFields['rma_classifications_id'],   
                'sub_classification_description' => $validatedFields['sub_classification_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'RMA Sub Classification Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('RMA Sub Classifications', $e->getMessage());
            return back()->with(['message' => 'RMA Sub Classification Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'rma_classifications_id' => 'required|integer',
            'sub_classification_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $rma_sub_classifications = RmaSubClassifications::find($request->id);

            if (!$rma_sub_classifications) {
                return back()->with(['message' => 'RMA Sub Classification not found!', 'type' => 'error']);
            }

            $rma_sub_classifications->rma_classifications_id = $validatedFields['rma_classifications_id'];
    
            $SubClassificationDescriptionExist = RmaSubClassifications::where('sub_classification_description', $request->sub_classification_description)->exists();

            if ($request->sub_classification_description !== $rma_sub_classifications->sub_classification_description) {
                if (!$SubClassificationDescriptionExist) {
                    $rma_sub_classifications->sub_classification_description = $validatedFields['sub_classification_description'];
                } else {
                    return back()->with(['message' => 'Sub Classification Description already exists!', 'type' => 'error']);
                }
            }
    
            $rma_sub_classifications->status = $validatedFields['status'];
            $rma_sub_classifications->updated_by = CommonHelpers::myId();
            $rma_sub_classifications->updated_at = now();
    
            $rma_sub_classifications->save();
    
            return back()->with(['message' => 'RMA Sub Classification Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('RMA Sub Classifications', $e->getMessage());
            return back()->with(['message' => 'RMA Sub Classification Updating Failed!', 'type' => 'error']);
        }
    }
}
