<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SubmasterExport;
use app\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\LogSystemError;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ModuleActivityHistoryController extends Controller
{
    
    public function getIndex(){

        if(!CommonHelpers::isView()) {
            return Inertia::render('Errors/RestrictionPage');
        }
        $data = [];

        return Inertia::render('AdmVram/ModuleActivityHistory', $data );
    }


}
