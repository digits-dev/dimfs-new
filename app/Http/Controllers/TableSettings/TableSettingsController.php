<?php

namespace App\Http\Controllers\TableSettings;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\TableSettings;
use App\Models\ModuleHeaders;
use App\Models\AdmModels\AdmPrivileges;
use App\Models\ActionTypes;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class TableSettingsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'table_settings.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = TableSettings::query()->with(['getCreatedBy', 'getUpdatedBy', 'getPrivilegeName', 'getModuleName', 'getActionTypes']);
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
        $data['tableName'] = 'table_settings';
        $data['page_title'] = 'Table Settings';
        $data['table_settings'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("TableSettings/TableSettings", $data);
    }
    
    public function createView()
    {
        if (!CommonHelpers::isCreate()) {
            return Inertia::render('Errors/RestrictionPage');
        }
    
        $data = [];
        $data['privileges'] = AdmPrivileges::getData()->get();
        $data['action_types'] = ActionTypes::select('id', 'action_type_description as name', 'status')     
            ->get();
    
        return Inertia::render('TableSettings/CreateView',$data);
    }

    public function create(Request $request){
        
        $existing = TableSettings::where('adm_privileges_id', $request->privilege_id)
            ->where('action_types_id', $request->action_type_id)
            ->where('table_name', $request->module_name)
            ->first();

        if ($existing) {
            return back()->with(['message' => 'Table Setting already exists!', 'type' => 'error']);
        }

        if ($request->checked_items == null) {
            return back()->with(['message' => 'Please select at least one header!', 'type' => 'error']);
        }

        $selected = ModuleHeaders::whereIn('header_name', $request->checked_items)->where('module_id', $request->module_id)->get();
        $headerName = $selected->pluck('header_name')->implode(',');
        $headerQuery = $selected->pluck('name')->implode(',');
        
        $validatedFields = $request->validate([
                'privilege_id' => 'required|integer',
                'module_id' => 'required|string|max:3',
                'action_type_id' => 'required|integer',
                'module_name' => 'required|string|max:255',
            ]);
            
        try {

            TableSettings::create([
                'adm_privileges_id' => $request->privilege_id,
                'adm_moduls_id' => $request->module_id,
                'action_types_id' =>$request->action_type_id,
                'table_name' => $request->module_name,
                'report_header' => $headerName,
                'report_query' => $headerQuery,
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return redirect('/table_settings')->with(['message' => 'Table Setting Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Table Settings', $e->getMessage());
            return back()->with(['message' => 'Table Setting Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function EditView($id)
    {
        $data = [];
        $data['table_settings'] = TableSettings::find($id);
        $data['module_headers'] = ModuleHeaders::where('module_id', $data['table_settings']->adm_moduls_id)->pluck('header_name');
        $data['privileges'] = AdmPrivileges::getData()->get();
        $data['action_types'] = ActionTypes::select('id', 'action_type_description as name', 'status')     
        ->get();
    
        return Inertia::render('TableSettings/EditView',$data);
    }


    public function update(Request $request){
        
        $existing = TableSettings::where('adm_privileges_id', $request->privilege_id)
        ->where('action_types_id', $request->action_type_id)
        ->where('table_name', $request->module_name)
        ->first();

        if ($existing && $existing->id != $request->id) {
            return back()->with(['message' => 'Table Setting already exists!', 'type' => 'error']);
        }

        if ($request->checked_items == null) {
            return back()->with(['message' => 'Please select at least one header!', 'type' => 'error']);
        }
        
        $validatedFields = $request->validate([
            'privilege_id' => 'required|integer',
            'module_id' => 'required|integer',
            'action_type_id' => 'required|integer',
            'module_name' => 'required|string|max:255',
            'status' => 'required|string',
        ]);
        try {
    
            $table_settings = TableSettings::find($request->id);
            if (!$table_settings) {
                return back()->with(['message' => 'Table Setting not found!', 'type' => 'error']);
            }
            
            $selected = ModuleHeaders::whereIn('header_name', $request->checked_items)->where('module_id', $request->module_id)->get();

            $headerName = $selected->pluck('header_name')->implode(',');
            $headerQuery = $selected->pluck('name')->implode(',');
            
            $table_settings->adm_privileges_id = $validatedFields['privilege_id'];
            $table_settings->adm_moduls_id = $validatedFields['module_id'];
            $table_settings->action_types_id = $validatedFields['action_type_id'];
            $table_settings->table_name = $validatedFields['module_name'];
            $table_settings->report_header = $headerName;
            $table_settings->report_query = $headerQuery;
            $table_settings->status = $validatedFields['status'];
            $table_settings->updated_by = CommonHelpers::myId();
            $table_settings->updated_at = now();
    
            $table_settings->save();
    
            return redirect('/table_settings')->with(['message' => 'Table Setting Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Table Settings', $e->getMessage());
            return back()->with(['message' => 'Table Setting Updating Failed!', 'type' => 'error']);
        }
    }

    public function getHeader($header_name)
    {

        $headerName = ModuleHeaders::where('module_id', $header_name)->where('status', 'ACTIVE')->pluck('header_name');

        return response()->json($headerName);
      
    }
}