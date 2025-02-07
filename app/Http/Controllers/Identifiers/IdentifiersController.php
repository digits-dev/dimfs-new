<?php

namespace App\Http\Controllers\Identifiers;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\Identifiers;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class IdentifiersController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'identifiers.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = Identifiers::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'identifiers';
        $data['page_title'] = 'Identifiers';
        $data['identifiers'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("Identifiers/Identifiers", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'identifier_column' => 'required|string|max:30|unique:identifiers,identifier_column',
            'identifier_description' => 'required|string|max:30|unique:identifiers,identifier_description',
        ]);

        try {

            Identifiers::create([
                'identifier_column' => $validatedFields['identifier_column'], 
                'identifier_description' => $validatedFields['identifier_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Identifier Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Identifiers', $e->getMessage());
            return back()->with(['message' => 'Identifier Creation Failed!', 'type' => 'error']);
        }
        
       
    }


    public function update(Request $request){

        $validatedFields = $request->validate([
            'identifier_column' => 'required|string|max:30',
            'identifier_description' => 'required|string|max:30',
            'status' => 'required|string',
        ]);

        try {
    
            $identifiers = Identifiers::find($request->id);

            if (!$identifiers) {
                return back()->with(['message' => 'Identifier not found!', 'type' => 'error']);
            }
    
            $IdentifierCodeExist = Identifiers::where('identifier_column', $request->identifier_column)->exists();
            $IdentifierDescriptionExist = Identifiers::where('identifier_description', $request->identifier_description)->exists();

            if ($request->identifier_column !== $identifiers->identifier_column) {
                if (!$IdentifierCodeExist) {
                    $identifiers->identifier_column = $validatedFields['identifier_column'];
                } else {
                    return back()->with(['message' => 'Identifier code already exists!', 'type' => 'error']);
                }
            }
            if ($request->identifier_description !== $identifiers->identifier_description) {
                if (!$IdentifierDescriptionExist) {
                    $identifiers->identifier_description = $validatedFields['identifier_description'];
                } else {
                    return back()->with(['message' => 'Identifier Description already exists!', 'type' => 'error']);
                }
            }
    
            $identifiers->status = $validatedFields['status'];
            $identifiers->updated_by = CommonHelpers::myId();
            $identifiers->updated_at = now();
    
            $identifiers->save();
    
            return back()->with(['message' => 'Identifier Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Identifiers', $e->getMessage());
            return back()->with(['message' => 'Identifier Updating Failed!', 'type' => 'error']);
        }
    }
}