<?php

namespace App\Http\Controllers\ActionTypes;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ActionTypes;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class ActionTypesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'action_types.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = ActionTypes::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'action_types';
        $data['page_title'] = 'Action Types';
        $data['action_types'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("ActionTypes/ActionTypes", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'action_type_description' => 'required|string|max:30|unique:action_types,action_type_description',
        ]);

        try {

            ActionTypes::create([
                'action_type_description' => $validatedFields['action_type_description'],   
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Action Type Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Action Types', $e->getMessage());
            return back()->with(['message' => 'Action Type Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'action_type_description' => 'required|string|max:30',
            'status' => 'required|string',
        ]);

        try {
    
            $action_types = ActionTypes::find($request->id);

            if (!$action_types) {
                return back()->with(['message' => 'Action Type not found!', 'type' => 'error']);
            }
    
            $actionTypeDescriptionExist = ActionTypes::where('action_type_description', $request->action_type_description)->exists();


            if ($request->action_type_description !== $action_types->action_type_description) {
                if (!$actionTypeDescriptionExist) {
                    $action_types->action_type_description = $validatedFields['action_type_description'];
                } else {
                    return back()->with(['message' => 'Action Type Description already exists!', 'type' => 'error']);
                }
            }
    
            $action_types->status = $validatedFields['status'];
            $action_types->updated_at = now();
    
            $action_types->save();
    
            return back()->with(['message' => 'Action Type Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Action Types', $e->getMessage());
            return back()->with(['message' => 'Action Type Updating Failed!', 'type' => 'error']);
        }
    }

    public function export(Request $request)
    {

        try {

            $headers = [
                'Action Type Description',
                'Status',
                'Created By',
                'Updated By',
                'Created At',
                'Updated At',
            ];
    
            $columns = [
                'action_type_description',
                'status',
                'getCreatedBy.name',
                'getUpdatedBy.name',
                'created_at',
                'updated_at',
            ];
    
            $filename = "Action Types - " . date ('Y-m-d H:i:s');
            $query = self::getAllData();
            return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Action Types', $e->getMessage());
        }

    }
}
