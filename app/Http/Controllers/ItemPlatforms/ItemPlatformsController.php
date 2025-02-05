<?php

namespace App\Http\Controllers\ItemPlatforms;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ItemPlatforms;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class ItemPlatformsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'item_platforms.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = ItemPlatforms::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'item_platforms';
        $data['page_title'] = 'Item Platforms';
        $data['item_platforms'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("ItemPlatforms/ItemPlatforms", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'item_masters_id' => 'required|string|max:3|unique:item_platforms,item_masters_id',
        ]);

        try {

            ItemPlatforms::create([
                'item_masters_id' => $validatedFields['item_masters_id'],    
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Item Platform Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('ItemPlatforms', $e->getMessage());
            return back()->with(['message' => 'Item Platform Creation Failed!', 'type' => 'error']);
        }
        
       
    }


    public function update(Request $request){

        $validatedFields = $request->validate([
            'item_masters_id' => 'required|string|max:3',
        ]);

        try {
    
            $item_platforms = ItemPlatforms::find($request->id);

            if (!$item_platforms) {
                return back()->with(['message' => 'Item Platform not found!', 'type' => 'error']);
            }
    
            $ItemMastersIdExist = ItemPlatforms::where('item_masters_id', $request->item_masters_id)->exists();

            if ($request->item_masters_id !== $item_platforms->item_masters_id) {
                if (!$ItemMastersIdExist) {
                    $item_platforms->item_masters_id = $validatedFields['item_masters_id'];
                } else {
                    return back()->with(['message' => 'Item Masters ID already exists!', 'type' => 'error']);
                }
            }
    
            $item_platforms->updated_by = CommonHelpers::myId();
            $item_platforms->updated_at = now();
    
            $item_platforms->save();
    
            return back()->with(['message' => 'Item Platform Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('ItemPlatforms', $e->getMessage());
            return back()->with(['message' => 'Item Platform Updating Failed!', 'type' => 'error']);
        }
    }
}