<?php

namespace App\Http\Controllers\Platforms;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\Platforms;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubmasterExport;

class PlatformsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'platforms.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = Platforms::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'platforms';
        $data['page_title'] = 'Platforms';
        $data['platforms'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("Platforms/Platforms", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'platform_description' => 'required|string|max:255',
            'platform_column' => 'required|string|max:50',
        ]);

        try {

            Platforms::create([
                'platform_description' => $validatedFields['platform_description'],
                'platform_column' => $validatedFields['platform_column'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Platform Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Platforms', $e->getMessage());
            return back()->with(['message' => 'Platform Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'platform_description' => 'required|string|max:255',
            'platform_column' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $platforms = Platforms::find($request->id);

            if (!$platforms) {
                return back()->with(['message' => 'Platform not found!', 'type' => 'error']);
            }
    
            $platforms->platform_description = $validatedFields['platform_description'];
            $platforms->platform_column = $validatedFields['platform_column'];
            $platforms->status = $validatedFields['status'];
            $platforms->updated_by = CommonHelpers::myId();
            $platforms->updated_at = now();
    
            $platforms->save();
    
            return back()->with(['message' => 'Platform Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Platforms', $e->getMessage());
            return back()->with(['message' => 'Platform Updating Failed!', 'type' => 'error']);
        }
    }
    public function export(Request $request)
    {

        $headers = [
            'Platform Description',
            'Platform Column',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'platform_description',
            'platform_column',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Platforms - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}