<?php

namespace App\Http\Controllers\Brands;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\Brands;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class BrandsController extends Controller
{
    private $table_name;
    private $primary_key;
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->table_name  =  'brands';
        $this->primary_key = 'id';
        $this->sortBy = request()->get('sortBy', 'brands.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = Brands::query();
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
        $data['tableName'] = 'brands';
        $data['page_title'] = 'Brands';
        $data['brands'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();
        return Inertia::render("Brands/Brands", $data);
        
    }
}
