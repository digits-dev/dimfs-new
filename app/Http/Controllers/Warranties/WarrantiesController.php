<?php

namespace App\Http\Controllers\Warranties;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\Warranties;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubmasterExport;

class WarrantiesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'warranties.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = Warranties::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'warranties';
        $data['page_title'] = 'Warranties';
        $data['warranties'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("Warranties/Warranties", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'warranty_code' => 'required|string|max:10|unique:warranties,warranty_code',
            'warranty_description' => 'required|string|max:255',
        ]);

        try {

            Warranties::create([
                'warranty_code' => $validatedFields['warranty_code'],
                'warranty_description' => $validatedFields['warranty_description'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Warranty Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Warranties', $e->getMessage());
            return back()->with(['message' => 'Warranty Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'warranty_code' => 'required|string|max:10',
            'warranty_description' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $warranties = Warranties::find($request->id);

            if (!$warranties) {
                return back()->with(['message' => 'Warranty not found!', 'type' => 'error']);
            }
    
            $WarrantyCodeExist = Warranties::where('warranty_code', $request->warranty_code)->exists();

            if ($request->warranty_code !== $warranties->warranty_code) {
                if (!$WarrantyCodeExist) {
                    $warranties->warranty_code = $validatedFields['warranty_code'];
                } else {
                    return back()->with(['message' => 'Warranty Code already exists!', 'type' => 'error']);
                }
            }
    
            $warranties->warranty_description = $validatedFields['warranty_description'];
            $warranties->status = $validatedFields['status'];
            $warranties->updated_by = CommonHelpers::myId();
            $warranties->updated_at = now();
    
            $warranties->save();
    
            return back()->with(['message' => 'Warranty Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Warranties', $e->getMessage());
            return back()->with(['message' => 'Warranty Updating Failed!', 'type' => 'error']);
        }
    }
    public function export(Request $request)
    {

        $headers = [
            'Warranty Code',
            'Warranty Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'warranty_code',
            'warranty_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Warranties - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}