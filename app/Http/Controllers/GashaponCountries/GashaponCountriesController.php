<?php

namespace App\Http\Controllers\GashaponCountries;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\GashaponCountries;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class GashaponCountriesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'gashapon_countries.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = GashaponCountries::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'gashapon_countries';
        $data['page_title'] = 'Gashapon Countries';
        $data['gashapon_countries'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia("GashaponCountries/GashaponCountries", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'country_code' => 'required|string|max:3|unique:gashapon_countries,country_code',
            'country_description' => 'required|string|max:50|unique:gashapon_countries,country_description',
        ]);

        try {

            GashaponCountries::create([
                'country_code' => $validatedFields['country_code'],   
                'country_description' => $validatedFields['country_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Gashapon Country Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Gashapon Countries', $e->getMessage());
            return back()->with(['message' => 'Gashapon Country Creation Failed!', 'type' => 'error']);
        }
        
       
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'country_code' => 'required|string|max:3',
            'country_description' => 'required|string|max:50',
            'status' => 'required|string',
        ]);

        try {
    
            $gashapon_country = GashaponCountries::find($request->id);

            if (!$gashapon_country) {
                return back()->with(['message' => 'Gashapon Country not found!', 'type' => 'error']);
            }
    
            $gashaponCountryCodeExist = GashaponCountries::where('country_code', $request->country_code)->exists();
            $gashaponCountryDescriptionExist = GashaponCountries::where('country_description', $request->country_description)->exists();


            if ($request->country_code !== $gashapon_country->country_code) {
                if (!$gashaponCountryCodeExist) {
                    $gashapon_country->country_code = $validatedFields['country_code'];
                } else {
                    return back()->with(['message' => 'Gashapon Country Code already exists!', 'type' => 'error']);
                }
            }

            if ($request->country_description !== $gashapon_country->country_description) {
                if (!$gashaponCountryDescriptionExist) {
                    $gashapon_country->country_description = $validatedFields['country_description'];
                } else {
                    return back()->with(['message' => 'Gashapon Country Description already exists!', 'type' => 'error']);
                }
            }
    
            $gashapon_country->status = $validatedFields['status'];
            $gashapon_country->updated_by = CommonHelpers::myId();
            $gashapon_country->updated_at = now();
    
            $gashapon_country->save();
    
            return back()->with(['message' => 'Gashapon Country Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Gashapon Countries', $e->getMessage());
            return back()->with(['message' => 'Gashapon Country Updating Failed!', 'type' => 'error']);
        }
    }
}
