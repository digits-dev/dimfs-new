<?php

namespace App\Http\Controllers\GashaponModels;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\GashaponModels;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class GashaponModelsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'gashapon_models.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = GashaponModels::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'gashapon_models';
        $data['page_title'] = 'Gashapon Models';
        $data['gashapon_models'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("GashaponModels/GashaponModels", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'model_description' => 'required|string|max:50|unique:gashapon_models,model_description',
        ]);

        try {

            GashaponModels::create([
                'model_description' => $validatedFields['model_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Gashapon Model Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon Models', $e->getMessage());
            return back()->with(['message' => 'Gashapon Model Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'model_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $gashapon_models = GashaponModels::find($request->id);

            if (!$gashapon_models) {
                return back()->with(['message' => 'Gashapon Model not found!', 'type' => 'error']);
            }
    
            $gashaponModelDescriptionExist = GashaponModels::where('model_description', $request->model_description)->exists();


            if ($request->model_description !== $gashapon_models->model_description) {
                if (!$gashaponModelDescriptionExist) {
                    $gashapon_models->model_description = $validatedFields['model_description'];
                } else {
                    return back()->with(['message' => 'Gashapon Model Description already exists!', 'type' => 'error']);
                }
            }
    
            $gashapon_models->status = $validatedFields['status'];
            $gashapon_models->updated_by = CommonHelpers::myId();
            $gashapon_models->updated_at = now();
    
            $gashapon_models->save();
    
            return back()->with(['message' => 'Gashapon Model Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Gashapon Models', $e->getMessage());
            return back()->with(['message' => 'Gashapon Model Updating Failed!', 'type' => 'error']);
        }
    }

    public function export(Request $request)
    {

        try {

            $headers = [
                'Model Description',
                'Status',
                'Created By',
                'Updated By',
                'Created At',
                'Updated At',
            ];
    
            $columns = [
                'model_description',
                'status',
                'getCreatedBy.name',
                'getUpdatedBy.name',
                'created_at',
                'updated_at',
            ];
    
            $filename = "Gashapon Models - " . date ('Y-m-d H:i:s');
            $query = self::getAllData();
            return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon Models', $e->getMessage());
        }

    }
}
