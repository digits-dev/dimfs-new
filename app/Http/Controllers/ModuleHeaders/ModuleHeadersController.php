<?php

namespace App\Http\Controllers\ModuleHeaders;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdmModels\AdmModules;
use App\Models\ModuleHeaders;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
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
            ->get()
            ->map(function ($module) {
                $module->status = $module->status === 1 ? 'ACTIVE' : 'INACTIVE';
                return $module;
            });

        $data['all_modules'] = AdmModules::select('id', 'name', 'is_active as status')     
            ->get()
            ->map(function ($module) {
                $module->status = $module->status === 1 ? 'ACTIVE' : 'INACTIVE';
                return $module;
            });

        return Inertia::render("ModuleHeaders/ModuleHeaders", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'module_id' => 'required|int',
            'name' => 'required|string|max:50',
            'header_name' => 'required|string|max:255',
            'width' => 'required|string|max:10',
        ]);

        try {

            ModuleHeaders::create([
                'module_id' => $validatedFields['module_id'],   
                'name' => $validatedFields['name'],   
                'width' => $validatedFields['width'],   
                'header_name' => $validatedFields['header_name'],   
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
            'width' => 'required|string|max:10',
            'status' => 'required|string',
        ]);

        try {
    
            $module_headers = ModuleHeaders::find($request->id);

            if (!$module_headers) {
                return back()->with(['message' => 'Module Header not found!', 'type' => 'error']);
            }
    
            $module_headers->module_id = $validatedFields['module_id'];
            $module_headers->name = $validatedFields['name'];
            $module_headers->header_name = $validatedFields['header_name'];
            $module_headers->width = $validatedFields['width'];
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

    public function export(Request $request)
    {

        try {

            $headers = [
                'Header Name',
                'Name',
                'Module Name',
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
