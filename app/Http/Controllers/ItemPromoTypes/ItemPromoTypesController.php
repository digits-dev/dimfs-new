<?php

namespace App\Http\Controllers\ItemPromoTypes;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ItemPromoTypes;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class ItemPromoTypesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'item_promo_types.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = ItemPromoTypes::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'item_promo_types';
        $data['page_title'] = 'Item Promo Types';
        $data['item_promo_types'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("ItemPromoTypes/ItemPromoTypes", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'item_masters_id' => 'required|string|max:3|unique:item_promo_types,item_masters_id',
        ]);

        try {

            ItemPromoTypes::create([
                'item_masters_id' => $validatedFields['item_masters_id'],    
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Item Promo Type Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Item Promo Types', $e->getMessage());
            return back()->with(['message' => 'Item Promo Type Creation Failed!', 'type' => 'error']);
        }
        
       
    }


    public function update(Request $request){

        $validatedFields = $request->validate([
            'item_masters_id' => 'required|string|max:3',
        ]);

        try {
    
            $item_promo_types = ItemPromoTypes::find($request->id);

            if (!$item_promo_types) {
                return back()->with(['message' => 'Item Promo Type not found!', 'type' => 'error']);
            }
    
            $ItemMastersIdExist = ItemPromoTypes::where('item_masters_id', $request->item_masters_id)->exists();

            if ($request->item_masters_id !== $item_promo_types->item_masters_id) {
                if (!$ItemMastersIdExist) {
                    $item_promo_types->item_masters_id = $validatedFields['item_masters_id'];
                } else {
                    return back()->with(['message' => 'Item Masters ID already exists!', 'type' => 'error']);
                }
            }
    
            $item_promo_types->updated_by = CommonHelpers::myId();
            $item_promo_types->updated_at = now();
    
            $item_promo_types->save();
    
            return back()->with(['message' => 'Item Promo Type Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('ItemPromoTypes', $e->getMessage());
            return back()->with(['message' => 'Item Promo Type Updating Failed!', 'type' => 'error']);
        }
    }
}