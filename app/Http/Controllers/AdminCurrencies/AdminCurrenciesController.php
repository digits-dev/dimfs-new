<?php

namespace App\Http\Controllers\AdminCurrencies;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminColor;
use App\Models\AdminCurrency;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class AdminCurrenciesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_currencies.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminCurrency::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'admin_currencies';
        $data['page_title'] = 'Admin Currencies';
        $data['admin_currencies'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("AdminCurrencies/AdminCurrencies", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'currency_code' => 'required|string|max:5|unique:admin_currencies,currency_code',
            'currency_description' => 'required|string|max:20|unique:admin_currencies,currency_description',
        ]);

        try {

            AdminCurrency::create([
                'currency_code' => $validatedFields['currency_code'], 
                'currency_description' => $validatedFields['currency_description'],     
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Currency Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Currencies', $e->getMessage());
            return back()->with(['message' => 'Currency Creation Failed!', 'type' => 'error']);
        }
    
    }
}
