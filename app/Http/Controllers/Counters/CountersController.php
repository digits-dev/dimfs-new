<?php

namespace App\Http\Controllers\Counters;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdmModels\AdmModules;
use App\Models\Counters;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class CountersController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'counters.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = Counters::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'counters';
        $data['page_title'] = 'Counters';
        $data['counters'] = self::getAllData()->paginate($this->perPage)->withQueryString();
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

        return Inertia::render("Counters/Counters", $data);
    }

    public function create(Request $request){

        // dd($request->all());

        $validatedFields = $request->validate([
            'cms_modules_id' => 'required',
            'module_name' => 'required|string|unique:counters,module_name',
            'code_1' => 'required|integer',
            'code_2' => 'required|integer',
            'code_3' => 'required|integer',
            'code_4' => 'required|integer',
            'code_5' => 'required|integer',
            'code_6' => 'required|integer',
            'code_7' => 'required|integer',
            'code_8' => 'required|integer',
            'code_9' => 'required|integer',
        ]);

        try {

            Counters::create([
                'cms_moduls_id' => $validatedFields['cms_modules_id'], 
                'module_name' => $validatedFields['module_name'],   
                'code_1' => $validatedFields['code_1'],   
                'code_2' => $validatedFields['code_2'],   
                'code_3' => $validatedFields['code_3'],   
                'code_4' => $validatedFields['code_4'],   
                'code_5' => $validatedFields['code_5'],   
                'code_6' => $validatedFields['code_6'],   
                'code_7' => $validatedFields['code_7'],   
                'code_8' => $validatedFields['code_8'],   
                'code_9' => $validatedFields['code_9'],
                'status' => $validatedFields['status'] ?? 'ACTIVE',   
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Counter Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Counters', $e->getMessage());
            return back()->with(['message' => 'Counter Creation Failed!', 'type' => 'error']);
        }
          
    }

    public function update(Request $request){

        dd($request->all());
        
        $validatedFields = $request->validate([
            'cms_modules_id' => 'required',
            'module_name' => 'required|string',
            'code_1' => 'required|integer',
            'code_2' => 'required|integer',
            'code_3' => 'required|integer',
            'code_4' => 'required|integer',
            'code_5' => 'required|integer',
            'code_6' => 'required|integer',
            'code_7' => 'required|integer',
            'code_8' => 'required|integer',
            'code_9' => 'required|integer',
            'status' => 'required',
        ]);

        try {
    
            $counters = Counters::find($request->id);

            if (!$counters) {
                return back()->with(['message' => 'Counter not found!', 'type' => 'error']);
            }

            $counters->cms_moduls_id = $validatedFields['cms_modules_id'];
    
            $moduleNameExist = Counters::where('module_name', $request->module_name)->exists();
          

            if ($request->module_name !== $counters->module_name) {
                if (!$moduleNameExist) {
                    $counters->module_name = $validatedFields['module_name'];
                } else {
                    return back()->with(['message' => 'Module Name already exists!', 'type' => 'error']);
                }
            }
    
            $counters->code_1 = $validatedFields['code_1'];
            $counters->code_2 = $validatedFields['code_2'];
            $counters->code_3 = $validatedFields['code_3'];
            $counters->code_4 = $validatedFields['code_4'];
            $counters->code_5 = $validatedFields['code_5'];
            $counters->code_6 = $validatedFields['code_6'];
            $counters->code_7 = $validatedFields['code_7'];
            $counters->code_8 = $validatedFields['code_8'];
            $counters->code_9 = $validatedFields['code_9'];
            $counters->status = $validatedFields['status'];
            $counters->updated_by = CommonHelpers::myId();
            $counters->updated_at = now();
    
            $counters->save();
    
            return back()->with(['message' => 'Classification Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Classifications', $e->getMessage());
            return back()->with(['message' => 'Classification Updating Failed!', 'type' => 'error']);
        }
    }
}
