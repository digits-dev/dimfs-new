<?php

namespace App\Http\Controllers\Uoms;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\Uoms;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubmasterExport;

class UomsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'uoms.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = Uoms::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'uoms';
        $data['page_title'] = 'UOMs';
        $data['uoms'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("Uoms/Uoms", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'uom_code' => 'required|string|max:10|unique:uoms,uom_code',
            'uom_description' => 'required|string|max:255',
        ]);

        try {

            Uoms::create([
                'uom_code' => $validatedFields['uom_code'],
                'uom_description' => $validatedFields['uom_description'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Oum Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Uoms', $e->getMessage());
            return back()->with(['message' => 'Oum Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'uom_code' => 'required|string|max:10',
            'uom_description' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $uoms = Uoms::find($request->id);

            if (!$uoms) {
                return back()->with(['message' => 'Oum not found!', 'type' => 'error']);
            }
    
            $UomCodeExist = Uoms::where('uom_code', $request->uom_code)->exists();

            if ($request->uom_code !== $uoms->uom_code) {
                if (!$UomCodeExist) {
                    $uoms->uom_code = $validatedFields['uom_code'];
                } else {
                    return back()->with(['message' => 'UOM Code already exists!', 'type' => 'error']);
                }
            }
    
            $uoms->uom_description = $validatedFields['uom_description'];
            $uoms->status = $validatedFields['status'];
            $uoms->updated_by = CommonHelpers::myId();
            $uoms->updated_at = now();
    
            $uoms->save();
    
            return back()->with(['message' => 'Oum Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Uoms', $e->getMessage());
            return back()->with(['message' => 'Oum Updating Failed!', 'type' => 'error']);
        }
    }
    public function export(Request $request)
    {

        $headers = [
            'Uom Code',
            'Uom Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'uom_code',
            'uom_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "UOMs - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}