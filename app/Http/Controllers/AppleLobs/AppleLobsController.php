<?php

namespace App\Http\Controllers\AppleLobs;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AppleLobs;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class AppleLobsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'apple_lobs.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AppleLobs::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'apple_lobs';
        $data['page_title'] = 'Apple LOBs';
        $data['apple_lobs'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("AppleLobs/AppleLobs", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'apple_lob_description' => 'required|string|max:50|unique:apple_lobs,apple_lob_description',
        ]);

        try {

            AppleLobs::create([
                'apple_lob_description' => $validatedFields['apple_lob_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Apple LOB Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Apple LOBs', $e->getMessage());
            return back()->with(['message' => 'Apple LOB Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'apple_lob_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $apple_lobs = AppleLobs::find($request->id);

            if (!$apple_lobs) {
                return back()->with(['message' => 'Apple LOB not found!', 'type' => 'error']);
            }
    
            $appleLobDescriptionExist = AppleLobs::where('apple_lob_description', $request->apple_lob_description)->exists();


            if ($request->apple_lob_description !== $apple_lobs->apple_lob_description) {
                if (!$appleLobDescriptionExist) {
                    $apple_lobs->apple_lob_description = $validatedFields['apple_lob_description'];
                } else {
                    return back()->with(['message' => 'Apple LOB Description already exists!', 'type' => 'error']);
                }
            }
    
            $apple_lobs->status = $validatedFields['status'];
            $apple_lobs->updated_by = CommonHelpers::myId();
            $apple_lobs->updated_at = now();
    
            $apple_lobs->save();
    
            return back()->with(['message' => 'Apple LOB Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Apple LOBs', $e->getMessage());
            return back()->with(['message' => 'Apple LOB Updating Failed!', 'type' => 'error']);
        }
    }

    public function export(Request $request)
    {

        try {

            $headers = [
                'Apple LOB Description',
                'Status',
                'Created By',
                'Updated By',
                'Created At',
                'Updated At',
            ];
    
            $columns = [
                'apple_lob_description',
                'status',
                'getCreatedBy.name',
                'getUpdatedBy.name',
                'created_at',
                'updated_at',
            ];
    
            $filename = "Apple LOBs - " . date ('Y-m-d H:i:s');
            $query = self::getAllData();
            return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Apple LOBs', $e->getMessage());
        }

    }
}
