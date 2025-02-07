<?php

namespace App\Http\Controllers\SubClassifications;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\SubClassifications;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class SubClassificationsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'sub_classifications.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = SubClassifications::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'sub_classifications';
        $data['page_title'] = 'Sub Classifications';
        $data['sub_classifications'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("SubClassifications/SubClassifications", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'classifications_id' => 'required|string|max:3|unique:sub_classifications,classifications_id',
            'subclass_code' => 'required|string|max:10|unique:sub_classifications,subclass_code',
            'subclass_description' => 'required|string|max:255',
        ]);

        try {

            SubClassifications::create([
                'classifications_id' => $validatedFields['classifications_id'],
                'subclass_code' => $validatedFields['subclass_code'],
                'subclass_description' => $validatedFields['subclass_description'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Sub Classification Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('SubClassifications', $e->getMessage());
            return back()->with(['message' => 'Sub Classification Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'classifications_id' => 'required|string|max:3',
            'subclass_code' => 'required|string|max:10',
            'subclass_description' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $sub_classifications = SubClassifications::find($request->id);

            if (!$sub_classifications) {
                return back()->with(['message' => 'Sub Classification not found!', 'type' => 'error']);
            }
    
            $ClassificationsIdExist = SubClassifications::where('classifications_id', $request->classifications_id)->exists();
            $SubclassCodeExist = SubClassifications::where('subclass_code', $request->subclass_code)->exists();

            if ($request->classifications_id !== $sub_classifications->classifications_id) {
                if (!$ClassificationsIdExist) {
                    $sub_classifications->classifications_id = $validatedFields['classifications_id'];
                } else {
                    return back()->with(['message' => 'Classification ID already exists!', 'type' => 'error']);
                }
            }
            
            if ($request->subclass_code !== $sub_classifications->subclass_code) {
                if (!$SubclassCodeExist) {
                    $sub_classifications->subclass_code = $validatedFields['subclass_code'];
                } else {
                    return back()->with(['message' => 'Subclass Code already exists!', 'type' => 'error']);
                }
            }
    
            $sub_classifications->subclass_description = $validatedFields['subclass_description'];
            $sub_classifications->status = $validatedFields['status'];
            $sub_classifications->updated_by = CommonHelpers::myId();
            $sub_classifications->updated_at = now();
    
            $sub_classifications->save();
    
            return back()->with(['message' => 'Sub Classification Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('SubClassifications', $e->getMessage());
            return back()->with(['message' => 'Sub Classification Updating Failed!', 'type' => 'error']);
        }
    }
}