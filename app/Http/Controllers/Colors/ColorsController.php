<?php

namespace App\Http\Controllers\Colors;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\Colors;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class ColorsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'colors.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = Colors::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'colors';
        $data['page_title'] = 'Colors';
        $data['colors'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("Colors/Colors", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'color_code' => 'required|string|max:3|unique:colors,color_code',
            'color_description' => 'required|string|max:30|unique:colors,color_description',
        ]);

        try {

            Colors::create([
                'color_code' => $validatedFields['color_code'], 
                'color_description' => $validatedFields['color_description'],   
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Color Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Colors', $e->getMessage());
            return back()->with(['message' => 'Color Creation Failed!', 'type' => 'error']);
        }
        
       
    }


    public function update(Request $request){

        $validatedFields = $request->validate([
            'color_code' => 'required|string|max:3',
            'color_description' => 'required|string|max:30',
            'status' => 'required|string',
        ]);

        try {
    
            $colors = Colors::find($request->id);

            if (!$colors) {
                return back()->with(['message' => 'Color not found!', 'type' => 'error']);
            }
    
            $colorCodeExist = Colors::where('color_code', $request->color_code)->exists();
            $colorDescriptionExist = Colors::where('color_description', $request->color_description)->exists();

            if ($request->color_code !== $colors->color_code) {
                if (!$colorCodeExist) {
                    $colors->color_code = $validatedFields['color_code'];
                } else {
                    return back()->with(['message' => 'Color code already exists!', 'type' => 'error']);
                }
            }
            if ($request->color_description !== $colors->color_description) {
                if (!$colorDescriptionExist) {
                    $colors->color_description = $validatedFields['color_description'];
                } else {
                    return back()->with(['message' => 'Color Description already exists!', 'type' => 'error']);
                }
            }
    
            $colors->status = $validatedFields['status'];
            $colors->updated_by = CommonHelpers::myId();
            $colors->updated_at = now();
    
            $colors->save();
    
            return back()->with(['message' => 'Color Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Colors', $e->getMessage());
            return back()->with(['message' => 'Color Updating Failed!', 'type' => 'error']);
        }
    }
}