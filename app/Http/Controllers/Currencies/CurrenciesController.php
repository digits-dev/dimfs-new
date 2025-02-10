<?php

namespace App\Http\Controllers\Currencies;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\Currencies;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubmasterExport;


class CurrenciesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'currencies.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = Currencies::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'currencies';
        $data['page_title'] = 'Currencies';
        $data['currencies'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("Currencies/Currencies", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'currency_code' => 'required|string|max:3|unique:currencies,currency_code',
            'currency_description' => 'required|string|max:30|unique:currencies,currency_description',
        ]);

        try {

            Currencies::create([
                'currency_code' => $validatedFields['currency_code'], 
                'currency_description' => $validatedFields['currency_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Currency Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Currencies', $e->getMessage());
            return back()->with(['message' => 'Currency Creation Failed!', 'type' => 'error']);
        }
        
       
    }


    public function update(Request $request){

        $validatedFields = $request->validate([
            'currency_code' => 'required|string|max:3',
            'currency_description' => 'required|string|max:30',
            'status' => 'required|string',
        ]);

        try {
    
            $currencies = Currencies::find($request->id);

            if (!$currencies) {
                return back()->with(['message' => 'Currency not found!', 'type' => 'error']);
            }
    
            $CurrencyCodeExist = Currencies::where('currency_code', $request->currency_code)->exists();
            $CurrencyDescriptionExist = Currencies::where('currency_description', $request->currency_description)->exists();

            if ($request->currency_code !== $currencies->currency_code) {
                if (!$CurrencyCodeExist) {
                    $currencies->currency_code = $validatedFields['currency_code'];
                } else {
                    return back()->with(['message' => 'Currency code already exists!', 'type' => 'error']);
                }
            }
            if ($request->currency_description !== $currencies->currency_description) {
                if (!$CurrencyDescriptionExist) {
                    $currencies->currency_description = $validatedFields['currency_description'];
                } else {
                    return back()->with(['message' => 'Currency Description already exists!', 'type' => 'error']);
                }
            }
    
            $currencies->status = $validatedFields['status'];
            $currencies->updated_by = CommonHelpers::myId();
            $currencies->updated_at = now();
    
            $currencies->save();
    
            return back()->with(['message' => 'Currency Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Currencies', $e->getMessage());
            return back()->with(['message' => 'Currency Updating Failed!', 'type' => 'error']);
        }
    }

    public function export(Request $request)
    {

        $headers = [
            'Currency Code',
            'Currency Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'currency_code',
            'currency_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Currencies - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}