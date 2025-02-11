<?php

namespace App\Http\Controllers\SupportTypes;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\SupportTypes;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubmasterExport;

class SupportTypesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'support_types.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = SupportTypes::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'support_types';
        $data['page_title'] = 'Support Types';
        $data['support_types'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("SupportTypes/SupportTypes", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'support_type_description' => 'required|string|max:255',
        ]);

        try {

            SupportTypes::create([
                'support_type_description' => $validatedFields['support_type_description'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Support Type Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('SupportTypes', $e->getMessage());
            return back()->with(['message' => 'Support Type Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'support_type_description' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $support_types = SupportTypes::find($request->id);

            if (!$support_types) {
                return back()->with(['message' => 'Support Type not found!', 'type' => 'error']);
            }
    
            $support_types->support_type_description = $validatedFields['support_type_description'];
            $support_types->status = $validatedFields['status'];
            $support_types->updated_by = CommonHelpers::myId();
            $support_types->updated_at = now();
    
            $support_types->save();
    
            return back()->with(['message' => 'Support Type Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('SupportTypes', $e->getMessage());
            return back()->with(['message' => 'Support Type Updating Failed!', 'type' => 'error']);
        }
    }
    public function export(Request $request)
    {

        $headers = [
            'Support Type Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'support_type_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Support Types - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}