<?php

namespace App\Http\Controllers\ModelSpecifics;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ModelSpecifics;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubmasterExport;

class ModelSpecificsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'model_specifics.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = ModelSpecifics::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'model_specifics';
        $data['page_title'] = 'Model Specifics';
        $data['model_specifics'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("ModelSpecifics/ModelSpecifics", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'model_specific_code' => 'required|string|max:10|unique:model_specifics,model_specific_code',
            'model_specific_description' => 'required|string|max:255',
        ]);

        try {

            ModelSpecifics::create([
                'model_specific_code' => $validatedFields['model_specific_code'],
                'model_specific_description' => $validatedFields['model_specific_description'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Model Specific Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('ModelSpecifics', $e->getMessage());
            return back()->with(['message' => 'Model Specific Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'model_specific_code' => 'required|string|max:10',
            'model_specific_description' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $model_specifics = ModelSpecifics::find($request->id);

            if (!$model_specifics) {
                return back()->with(['message' => 'Model Specific not found!', 'type' => 'error']);
            }
    
            $ModelSpecificCodeExist = ModelSpecifics::where('model_specific_code', $request->model_specific_code)->exists();

            if ($request->model_specific_code !== $model_specifics->model_specific_code) {
                if (!$ModelSpecificCodeExist) {
                    $model_specifics->model_specific_code = $validatedFields['model_specific_code'];
                } else {
                    return back()->with(['message' => 'Model Specific Code already exists!', 'type' => 'error']);
                }
            }
    
            $model_specifics->model_specific_description = $validatedFields['model_specific_description'];
            $model_specifics->status = $validatedFields['status'];
            $model_specifics->updated_by = CommonHelpers::myId();
            $model_specifics->updated_at = now();
    
            $model_specifics->save();
    
            return back()->with(['message' => 'Model Specific Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Model Specifics', $e->getMessage());
            return back()->with(['message' => 'Model Specific Updating Failed!', 'type' => 'error']);
        }
    }

    public function export(Request $request)
    {

        $headers = [
            'Model Specific Code',
            'Model Specific Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'model_specific_code',
            'model_specific_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Model Specifics - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}