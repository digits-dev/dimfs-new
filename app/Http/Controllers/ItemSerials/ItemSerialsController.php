<?php

namespace App\Http\Controllers\ItemSerials;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ItemSerials;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class ItemSerialsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'item_serials.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = ItemSerials::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'item_serials';
        $data['page_title'] = 'Item Serials';
        $data['item_serials'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("ItemSerials/ItemSerials", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'item_masters_id' => 'required|string|max:3|unique:item_serials,item_masters_id',
        ]);

        try {

            ItemSerials::create([
                'item_masters_id' => $validatedFields['item_masters_id'],    
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Item Serial Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('ItemSerials', $e->getMessage());
            return back()->with(['message' => 'Item Serial Creation Failed!', 'type' => 'error']);
        }
        
       
    }


    public function update(Request $request){

        $validatedFields = $request->validate([
            'item_masters_id' => 'required|string|max:3',
        ]);

        try {
    
            $item_serials = ItemSerials::find($request->id);

            if (!$item_serials) {
                return back()->with(['message' => 'Item Serial not found!', 'type' => 'error']);
            }
    
            $ItemMastersIdExist = ItemSerials::where('item_masters_id', $request->item_masters_id)->exists();

            if ($request->item_masters_id !== $item_serials->item_masters_id) {
                if (!$ItemMastersIdExist) {
                    $item_serials->item_masters_id = $validatedFields['item_masters_id'];
                } else {
                    return back()->with(['message' => 'Item Masters ID already exists!', 'type' => 'error']);
                }
            }
    
            $item_serials->updated_by = CommonHelpers::myId();
            $item_serials->updated_at = now();
    
            $item_serials->save();
    
            return back()->with(['message' => 'Item Serial Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('ItemSerials', $e->getMessage());
            return back()->with(['message' => 'Item Serial Updating Failed!', 'type' => 'error']);
        }
    }
}