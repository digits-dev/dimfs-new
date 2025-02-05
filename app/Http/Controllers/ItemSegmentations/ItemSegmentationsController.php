<?php

namespace App\Http\Controllers\ItemSegmentations;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\ItemSegmentations;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class ItemSegmentationsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'item_segmentations.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = ItemSegmentations::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'item_segmentations';
        $data['page_title'] = 'Item Segmentations';
        $data['item_segmentations'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("ItemSegmentations/ItemSegmentations", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'item_masters_id' => 'required|string|max:3|unique:item_segmentations,item_masters_id',
        ]);

        try {

            ItemSegmentations::create([
                'item_masters_id' => $validatedFields['item_masters_id'],    
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Item Segmentation Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('ItemSegmentations', $e->getMessage());
            return back()->with(['message' => 'Item Segmentation Creation Failed!', 'type' => 'error']);
        }
        
       
    }


    public function update(Request $request){

        $validatedFields = $request->validate([
            'item_masters_id' => 'required|string|max:3',
        ]);

        try {
    
            $item_segmentations = ItemSegmentations::find($request->id);

            if (!$item_segmentations) {
                return back()->with(['message' => 'Item Segmentation not found!', 'type' => 'error']);
            }
    
            $ItemMastersIdExist = ItemSegmentations::where('item_masters_id', $request->item_masters_id)->exists();

            if ($request->item_masters_id !== $item_segmentations->item_masters_id) {
                if (!$ItemMastersIdExist) {
                    $item_segmentations->item_masters_id = $validatedFields['item_masters_id'];
                } else {
                    return back()->with(['message' => 'Item Masters ID already exists!', 'type' => 'error']);
                }
            }
    
            $item_segmentations->updated_by = CommonHelpers::myId();
            $item_segmentations->updated_at = now();
    
            $item_segmentations->save();
    
            return back()->with(['message' => 'Item Segmentation Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('ItemSegmentations', $e->getMessage());
            return back()->with(['message' => 'Item Segmentation Updating Failed!', 'type' => 'error']);
        }
    }
}