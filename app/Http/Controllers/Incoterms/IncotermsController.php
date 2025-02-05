<?php

namespace App\Http\Controllers\Incoterms;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\Incoterms;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class IncotermsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'incoterms.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = Incoterms::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'incoterms';
        $data['page_title'] = 'Incoterms';
        $data['incoterms'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();
     
        return Inertia::render("Incoterms/Incoterms", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'incoterms_code' => 'required|string|max:3|unique:incoterms,incoterms_code',
            'incoterms_description' => 'required|string|max:30|unique:incoterms,incoterms_description',
        ]);

        try {

            Incoterms::create([
                'incoterms_code' => $validatedFields['incoterms_code'], 
                'incoterms_description' => $validatedFields['incoterms_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Incoterms Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Incoterms', $e->getMessage());
            return back()->with(['message' => 'Incoterms Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'incoterms_code' => 'required|string|max:3',
            'incoterms_description' => 'required|string|max:30',
            'status' => 'required|string',
        ]);

        try {
    
            $incoterms = Incoterms::find($request->id);

            if (!$incoterms) {
                return back()->with(['message' => 'Incoterms not found!', 'type' => 'error']);
            }
    
            $incotermsCodeExist = Incoterms::where('incoterms_code', $request->incoterms_code)->exists();
            $incotermsDescriptionExist = Incoterms::where('incoterms_description', $request->incoterms_description)->exists();

            if ($request->incoterms_code !== $incoterms->incoterms_code) {
                if (!$incotermsCodeExist) {
                    $incoterms->incoterms_code = $validatedFields['incoterms_code'];
                } else {
                    return back()->with(['message' => 'Incoterms code already exists!', 'type' => 'error']);
                }
            }
            if ($request->incoterms_description !== $incoterms->incoterms_description) {
                if (!$incotermsDescriptionExist) {
                    $incoterms->incoterms_description = $validatedFields['incoterms_description'];
                } else {
                    return back()->with(['message' => 'Incoterms Description already exists!', 'type' => 'error']);
                }
            }
    
            $incoterms->status = $validatedFields['status'];
            $incoterms->updated_by = CommonHelpers::myId();
            $incoterms->updated_at = now();
    
            $incoterms->save();
    
            return back()->with(['message' => 'Incoterms Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Incoterms', $e->getMessage());
            return back()->with(['message' => 'Incoterms Updating Failed!', 'type' => 'error']);
        }
    }
}
