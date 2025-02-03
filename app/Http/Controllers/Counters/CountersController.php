<?php

namespace App\Http\Controllers\Counters;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdmModels\AdmModules;
use App\Models\Counters;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class CountersController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'counters.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = Counters::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'counters';
        $data['page_title'] = 'Counters';
        $data['counters'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        $data['all_active_modules'] = AdmModules::select('id', 'name', 'status')
            ->where('status', 1)
            ->get();
        $data['all_modules'] = AdmModules::select('id', 'name', 'status')     
            ->get();
        return Inertia::render("Counters/Counters");
    }
}
