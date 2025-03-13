<?php

namespace App\Http\Controllers\ModuleHeaders;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdmModels\AdmModules;
use App\Models\GashaponItemMaster;
use App\Models\ItemMaster;
use App\Models\ItemMasterAccountingApproval;
use App\Models\ModuleHeaders;
use App\Models\RmaItemMaster;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class ModuleHeadersController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'module_headers.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = ModuleHeaders::query()->with(['getCreatedBy', 'getUpdatedBy', 'getModule']);
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
        $data['tableName'] = 'module_headers';
        $data['page_title'] = 'Module Headers';
        $data['module_headers'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_modules'] = AdmModules::select('id', 'name', 'is_active as status')
            ->where('is_active', 1)
            ->whereIn('name', ['Item Master', 'Gashapon Item Masters', 'RMA Item Master', 'Item Master Approval (Accounting)'])
            ->get()
            ->map(function ($module) {
                $module->status = $module->status === 1 ? 'ACTIVE' : 'INACTIVE';
                return $module;
            });

        $data['all_modules'] = AdmModules::select('id', 'name', 'is_active as status')    
            ->whereIn('name', ['Item Master', 'Gashapon Item Masters', 'RMA Item Master', 'Item Master Approval (Accounting)'])
            ->get()
            ->map(function ($module) {
                $module->status = $module->status === 1 ? 'ACTIVE' : 'INACTIVE';
                return $module;
            });

        $data['item_master_columns'] = array_values(array_map(
            function ($column) {
                return ['id' => $column, 'name' => $column];
            },
            array_filter(
                Schema::getColumnListing((new ItemMaster())->getTable()), 
                function ($column) {
                    return !ModuleHeaders::where('name', $column)->where('module_id', '38')->exists();
                }
            )
        ));

        $data['gashapon_item_master_columns'] = array_values(array_map(
            function ($column) {
                return [
                    'id' => $column,
                    'name' => $column
                ];
            },
            array_filter(
                Schema::getColumnListing((new GashaponItemMaster())->getTable()),
                function ($column) {
                    return !ModuleHeaders::where('name', $column)->where('module_id', '28')->exists();
                }
            )
        ));

        $data['rma_item_master_columns'] = array_values(array_map(
            function ($column) {
                return [
                    'id' => $column,
                    'name' => $column
                ];
            },
            array_filter(
                Schema::getColumnListing((new RmaItemMaster())->getTable()),
                function ($column) {
                    return !ModuleHeaders::where('name', $column)->where('module_id', '79')->exists();
                }
            )
        ));

        $data['item_master_accounting_columns'] = array_values(array_map(
            function ($column) {
                return [
                    'id' => $column,
                    'name' => $column
                ];
            },
            array_filter(
                Schema::getColumnListing((new ItemMasterAccountingApproval())->getTable()),
                function ($column) {
                    return !ModuleHeaders::where('name', $column)->where('module_id', '82')->exists();
                }
            )
        ));

        $databaseName = config('database.connections.mysql.database');
        $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = ?", [$databaseName]);

        $data['database_tables_and_columns'] = [];
        foreach ($tables as $table) {
            $tableName = $table->TABLE_NAME;

            $columns = Schema::getColumnListing($tableName);

            $data['database_tables_and_columns'][] = [
                'table_name' => $tableName,
                'columns' => $columns
            ];
        }
        
        

        return Inertia::render("ModuleHeaders/ModuleHeaders", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'module_id' => 'required|int',
            'name' => 'required|string|max:50',
            'header_name' => 'required|string|max:255',
            'validation' => 'required|string|max:255',
            'width' => 'required|string|max:10',
            'type' => 'required|string|max:10',
        ]);

        if ($request->type == 'select'){
            $request->validate([
                'table' => 'required|string|max:255',
                'table_join' => 'required|string|max:255',
                'table_select_value' => 'required|string|max:255',
                'table_select_label' => 'required|string|max:255',
            ]);
        }

        $isHeaderExist = ModuleHeaders::where('module_id', $request->module_id)
            ->where('name', $request->name )
            ->exists();

        if ($isHeaderExist){

            return back()->with(['message' => 'Module Header already exists!', 'type' => 'error']);
        }

        try {

            ModuleHeaders::create([
                'module_id' => $validatedFields['module_id'],   
                'name' => $validatedFields['name'],   
                'width' => $validatedFields['width'],   
                'header_name' => $validatedFields['header_name'],
                'validation' => $validatedFields['validation'],
                'type' => $validatedFields['type'],
                'table' => $request->table ?? null,
                'table_join' => $request->table_join ?? null,
                'table_select_value' => $request->table_select_value ?? null,
                'table_select_label' => $request->table_select_label ?? null,
            ]);
    
            return back()->with(['message' => 'Module Header Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Module Headers', $e->getMessage());
            return back()->with(['message' => 'Module Header Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'module_id' => 'required|int',
            'name' => 'required|string|max:50',
            'header_name' => 'required|string|max:255',
            'validation' => 'required|string|max:255',
            'width' => 'required|string|max:10',
            'type' => 'required|string|max:10',
            'status' => 'required|string',
        ]);

        try {
    
            $module_headers = ModuleHeaders::find($request->id);

            if (!$module_headers) {
                return back()->with(['message' => 'Module Header not found!', 'type' => 'error']);
            }

            $isHeaderExist = ModuleHeaders::where('module_id', $request->module_id)
            ->where('name', $request->name )
            ->exists();

            if ($request->module_id !== $module_headers->module_id || $request->name !== $module_headers->name) {
                if (!$isHeaderExist) {
                    $module_headers->module_id = $validatedFields['module_id'];
                    $module_headers->name = $validatedFields['name'];

                } else {
                    return back()->with(['message' => 'Module Header already exists!', 'type' => 'error']);
                }
            }

            $module_headers->header_name = $validatedFields['header_name'];
            $module_headers->width = $validatedFields['width'];
            $module_headers->validation = $validatedFields['validation'];
            $module_headers->type = $validatedFields['type'];
            $module_headers->table = $request->table ?? null;
            $module_headers->table_join = $request->table_join ?? null;
            $module_headers->table_select_value = $request->table_select_value ?? null;
            $module_headers->table_select_label = $request->table_select_label ?? null;
            $module_headers->status = $validatedFields['status'];
            $module_headers->updated_at = now();
    
            $module_headers->save();
    
            return back()->with(['message' => 'Module Header Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Module Headers', $e->getMessage());
            return back()->with(['message' => 'Module Header Updating Failed!', 'type' => 'error']);
        }
    }

    public function sortView(){
        $data = [];

        return Inertia::render("ModuleHeaders/ModuleHeadersSort", $data);
    }

    public function getHeader($header_name)
    {
        $headerName = ModuleHeaders::where('module_id', $header_name)
            ->where('status', 'ACTIVE')
            ->orderBy('sorting', 'asc') 
            ->pluck('header_name');


        return response()->json($headerName);
      
    }

    public function sort(Request $request){

        $request->validate([
            'module_name' => 'required',
        ]);

        try {

            $module_headers = ModuleHeaders::where('module_id', $request->module_id)->get();

            if (empty($request->items)){
                return back()->with(['message' => 'The Module has no headers', 'type' => 'error']);
            }

            if ($module_headers->isEmpty()) { // Use isEmpty() instead of empty()
                return back()->with(['message' => 'Module Not Found', 'type' => 'error']);
            }

            $sorting = 1;

            foreach($request->items as $item){
                $header = ModuleHeaders::where('header_name', $item)
                ->where('module_id', $request->module_id)->first();
                $header->sorting = $sorting;
                $header->updated_at = now();
                $header->save();
                
                $sorting++;
            }
            
    
            return redirect('module_headers')->with(['message' => 'Module Header Sorting Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Module Headers', $e->getMessage());
            return back()->with(['message' => 'Module Header Updating Failed!', 'type' => 'error']);
        }
    }

    public function export(Request $request)
    {

        try {

            $headers = [
                'Header Name',
                'Name',
                'Module Name',
                'Validation',
                'Width',
                'Type',
                'Table',
                'Join',
                'Table Select Value',
                'Table Select Label',
                'Status',
                'Created By',
                'Updated By',
                'Created At',
                'Updated At',
            ];
    
            $columns = [
                'header_name',
                'name',
                'getModule.name',
                'validation',
                'width',
                'type',
                'table',
                'table_join',
                'table_select_value',
                'table_select_label',
                'status',
                'getCreatedBy.name',
                'getUpdatedBy.name',
                'created_at',
                'updated_at',
            ];
    
            $filename = "Module Headers - " . date ('Y-m-d H:i:s');
            $query = self::getAllData();
            return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Module Headers', $e->getMessage());
        }

    }
}
