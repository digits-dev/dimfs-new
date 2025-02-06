<?php

namespace App\Http\Controllers\Sizes;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\Sizes;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class SizesController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'sizes.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = Sizes::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'sizes';
        $data['page_title'] = 'Sizes';
        $data['sizes'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("Sizes/Sizes", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'size_code' => 'required|string|max:10|unique:sizes,size_code',
            'size_description' => 'required|string|max:255',
        ]);

        try {

            Sizes::create([
                'size_code' => $validatedFields['size_code'],
                'size_description' => $validatedFields['size_description'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Size Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Sizes', $e->getMessage());
            return back()->with(['message' => 'Size Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'size_code' => 'required|string|max:10',
            'size_description' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $sizes = Sizes::find($request->id);

            if (!$sizes) {
                return back()->with(['message' => 'Size not found!', 'type' => 'error']);
            }
    
            $SizeCodeExist = Sizes::where('size_code', $request->size_code)->exists();

            if ($request->size_code !== $sizes->size_code) {
                if (!$SizeCodeExist) {
                    $sizes->size_code = $validatedFields['size_code'];
                } else {
                    return back()->with(['message' => 'Size Code already exists!', 'type' => 'error']);
                }
            }
    
            $sizes->size_description = $validatedFields['size_description'];
            $sizes->status = $validatedFields['status'];
            $sizes->updated_by = CommonHelpers::myId();
            $sizes->updated_at = now();
    
            $sizes->save();
    
            return back()->with(['message' => 'Size Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Sizes', $e->getMessage());
            return back()->with(['message' => 'Size Updating Failed!', 'type' => 'error']);
        }
    }
}