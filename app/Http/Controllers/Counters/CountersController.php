<?php

namespace App\Http\Controllers\Counters;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdmModels\AdmModules;
use App\Models\Counters;
use App\Rules\UniqueFirstDigit;
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
        $query = Counters::query()->with(['getCreatedBy', 'getUpdatedBy', 'getModule']);
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

        $validatedFields = $request->validate([
            'adm_module_id' => 'required',
            'counter_code' => [
                'required',
                'unique:counters,counter_code',
                new UniqueFirstDigit,
            ],
            'code_identifier' => 'required',
        ]);

        $CodeIdentifierExist = Counters::where('code_identifier', $request->code_identifier)->where('adm_module_id', $request->adm_module_id)->exists();

        if ($CodeIdentifierExist){
            return back()->withErrors(['code_identifier' => 'Code Identifier already exists']);
        }

        try {

            Counters::create([
                'adm_module_id' => $validatedFields['adm_module_id'], 
                'counter_code' => $validatedFields['counter_code'], 
                'code_identifier' => $validatedFields['code_identifier'], 
                'status' => $validatedFields['status'] ?? 'ACTIVE',   
            ]);
    
            return back()->with(['message' => 'Counter Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Counters', $e->getMessage());
            return back()->with(['message' => 'Counter Creation Failed!', 'type' => 'error']);
        }
          
    }

    public function update(Request $request){
        
        $validatedFields = $request->validate([
            'adm_module_id' => 'required',
            'counter_code' => 'required',
            'code_identifier' => 'required',
            'status' => 'required',
        ]);

        try {
    
            $counters = Counters::find($request->id);

            if (!$counters) {
                return back()->with(['message' => 'Counter not found!', 'type' => 'error']);
            }

            $counters->adm_module_id = $validatedFields['adm_module_id'];
    
            $CodeIdentifierExist = Counters::where('code_identifier', $request->code_identifier)->where('adm_module_id', $request->adm_module_id)->exists();

            $counterCodeExist = Counters::where('counter_code', $request->counter_code)->exists();
        
            if ($request->code_identifier !== $counters->code_identifier) {
                if (!$CodeIdentifierExist) {
                    $counters->code_identifier = $validatedFields['code_identifier'];
                } else {
                    return back()->withErrors(['code_identifier' => 'Code Identifier already exists']);
                }
            }

            if ($request->counter_code !== $counters->counter_code) {
                if (!$counterCodeExist) {
                    $counters->counter_code = $validatedFields['counter_code'];
                } else {
                    return back()->withErrors(['counter_code' => 'Counter Code already exists']);
                }
            }
    
            $counters->counter_code = $validatedFields['counter_code'];
            $counters->status = $validatedFields['status'];
            $counters->updated_at = now();
    
            $counters->save();
    
            return back()->with(['message' => 'Counter Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Counters', $e->getMessage());
            return back()->with(['message' => 'Counter Updating Failed!', 'type' => 'error']);
        }
    }
}
