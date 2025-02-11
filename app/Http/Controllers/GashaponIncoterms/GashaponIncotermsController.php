<?php

namespace App\Http\Controllers\GashaponIncoterms;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\GashaponIncoterms;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class GashaponIncotermsController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'gashapon_incoterms.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = GashaponIncoterms::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'gashapon_incoterms';
        $data['page_title'] = 'Gashapon Incoterms';
        $data['gashapon_incoterms'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia("GashaponIncoterms/GashaponIncoterms", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'incoterm_description' => 'required|string|max:50|unique:gashapon_incoterms,incoterm_description',
        ]);

        try {

            GashaponIncoterms::create([
                'incoterm_description' => $validatedFields['incoterm_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Gashapon Incoterm Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon Incoterms', $e->getMessage());
            return back()->with(['message' => 'Gashapon Incoterm Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'incoterm_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $gashapon_incoterms = GashaponIncoterms::find($request->id);

            if (!$gashapon_incoterms) {
                return back()->with(['message' => 'Incoterm not found!', 'type' => 'error']);
            }
    
            $gashaponIncotermDescriptionExist = GashaponIncoterms::where('incoterm_description', $request->incoterm_description)->exists();


            if ($request->incoterm_description !== $gashapon_incoterms->incoterm_description) {
                if (!$gashaponIncotermDescriptionExist) {
                    $gashapon_incoterms->incoterm_description = $validatedFields['incoterm_description'];
                } else {
                    return back()->with(['message' => 'Incoterm Description already exists!', 'type' => 'error']);
                }
            }
    
            $gashapon_incoterms->status = $validatedFields['status'];
            $gashapon_incoterms->updated_by = CommonHelpers::myId();
            $gashapon_incoterms->updated_at = now();
    
            $gashapon_incoterms->save();
    
            return back()->with(['message' => 'Incoterm Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Incoterms', $e->getMessage());
            return back()->with(['message' => 'Incoterm Updating Failed!', 'type' => 'error']);
        }
    }

    public function export(Request $request)
    {

        try {

            $headers = [
                'Incoterm Description',
                'Status',
                'Created By',
                'Updated By',
                'Created At',
                'Updated At',
            ];
    
            $columns = [
                'incoterm_description',
                'status',
                'getCreatedBy.name',
                'getUpdatedBy.name',
                'created_at',
                'updated_at',
            ];
    
            $filename = "Gashapon Incoterms - " . date ('Y-m-d H:i:s');
            $query = self::getAllData();
            return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Incoterms', $e->getMessage());
        }

    }
}
