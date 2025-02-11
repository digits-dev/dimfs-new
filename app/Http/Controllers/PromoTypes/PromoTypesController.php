<?php

namespace App\Http\Controllers\PromoTypes;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\PromoTypes;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubmasterExport;

class PromoTypesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'promo_types.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = PromoTypes::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'promo_types';
        $data['page_title'] = 'Promo Types';
        $data['promo_types'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("PromoTypes/PromoTypes", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'promo_type_column' => 'required|string|max:50',
            'promo_type_description' => 'required|string|max:255',
        ]);

        try {

            PromoTypes::create([
                'promo_type_column' => $validatedFields['promo_type_column'],
                'promo_type_description' => $validatedFields['promo_type_description'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Promo Type Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Promo Types', $e->getMessage());
            return back()->with(['message' => 'Promo Type Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'promo_type_column' => 'required|string|max:50',
            'promo_type_description' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $promo_types = PromoTypes::find($request->id);

            if (!$promo_types) {
                return back()->with(['message' => 'Promo Type not found!', 'type' => 'error']);
            }
    
            $promo_types->promo_type_column = $validatedFields['promo_type_column'];
            $promo_types->promo_type_description = $validatedFields['promo_type_description'];
            $promo_types->status = $validatedFields['status'];
            $promo_types->updated_by = CommonHelpers::myId();
            $promo_types->updated_at = now();
    
            $promo_types->save();
    
            return back()->with(['message' => 'Promo Type Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('PromoTypes', $e->getMessage());
            return back()->with(['message' => 'Promo Type Updating Failed!', 'type' => 'error']);
        }
    }
    public function export(Request $request)
    {

        $headers = [
            'Promo Type Column',
            'Promo Type Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'promo_type_column',
            'promo_type_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Promo Types - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}