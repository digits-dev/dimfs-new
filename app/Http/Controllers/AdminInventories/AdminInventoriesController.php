<?php

namespace App\Http\Controllers\AdminInventories;

use App\Exports\SubmasterExport;
use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdminInventory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminInventoriesController extends Controller
{
    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'admin_inventories.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = AdminInventory::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'admin_inventories';
        $data['page_title'] = 'Admin Inventories';
        $data['admin_inventories'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("AdminInventories/AdminInventories", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'inventory_code' => 'required|string|max:5|unique:admin_inventories,inventory_code',
            'inventory_description' => 'required|string|max:30|unique:admin_inventories,inventory_description',
        ]);

        try {

            AdminInventory::create([
                'inventory_code' => $validatedFields['inventory_code'], 
                'inventory_description' => $validatedFields['inventory_description'],     
                'status' => 'ACTIVE',
            ]);
    
            return back()->with(['message' => 'Inventory Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Admin Inventories', $e->getMessage());
            return back()->with(['message' => 'Inventory Creation Failed!', 'type' => 'error']);
        }
    
    }

    public function export()
    {

        $headers = [
            'Inventory Code',
            'Inventory Description',
            'Status',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];

        $columns = [
            'inventory_code',
            'inventory_description',
            'status',
            'getCreatedBy.name',
            'getUpdatedBy.name',
            'created_at',
            'updated_at',
        ];

        $filename = "Admin Inventories - " . date ('Y-m-d H:i:s');
        $query = self::getAllData();
        return Excel::download(new SubmasterExport($query, $headers, $columns), $filename . '.xlsx');

    }
}
