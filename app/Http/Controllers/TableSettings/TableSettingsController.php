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
        $query = TableSettings::query()->with(['getCreatedBy', 'getUpdatedBy']);
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

        dd($request->all());

        $validatedFields = $request->validate([
            'adm_privileges_id' => 'required|string|max:3',
            'adm_moduls_id' => 'required|string|max:3',
            'action_types_id' => 'required|string|max:3',
            'table_name' => 'required|string|max:255',
            'report_header' => 'required|string|max:255',
            'report_query' => 'required|string',
        ]);

        try {

            TableSettings::create([
                'adm_privileges_id' => $validatedFields['adm_privileges_id'],
                'adm_moduls_id' => $validatedFields['adm_moduls_id'],
                'action_types_id' => $validatedFields['action_types_id'],
                'table_name' => $validatedFields['table_name'],
                'report_header' => $validatedFields['report_header'],
                'report_query' => $validatedFields['report_query'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Table Setting Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('TableSettings', $e->getMessage());
            return back()->with(['message' => 'Table Setting Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'adm_privileges_id' => 'required|string|max:3',
            'adm_moduls_id' => 'required|string|max:3',
            'action_types_id' => 'required|string|max:3',
            'table_name' => 'required|string|max:255',
            'report_header' => 'required|string|max:255',
            'report_query' => 'required|string',
            'status' => 'required|string',
        ]);

        try {
    
            $table_settings = TableSettings::find($request->id);

            if (!$table_settings) {
                return back()->with(['message' => 'Table Setting not found!', 'type' => 'error']);
            }
    
            $table_settings->adm_privileges_id = $validatedFields['adm_privileges_id'];
            $table_settings->adm_moduls_id = $validatedFields['adm_moduls_id'];
            $table_settings->action_types_id = $validatedFields['action_types_id'];
            $table_settings->table_name = $validatedFields['table_name'];
            $table_settings->report_header = $validatedFields['report_header'];
            $table_settings->report_query = $validatedFields['report_query'];
            $table_settings->status = $validatedFields['status'];
            $table_settings->updated_by = CommonHelpers::myId();
            $table_settings->updated_at = now();
    
            $table_settings->save();
    
            return back()->with(['message' => 'Table Setting Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('TableSettings', $e->getMessage());
            return back()->with(['message' => 'Table Setting Updating Failed!', 'type' => 'error']);
        }
    }

    public function getHeader($header_name)
    {

        $headerName = ModuleHeaders::where('module_id', $header_name)->pluck('header_name');

        return response()->json($headerName);
      
    }
}