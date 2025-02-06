<?php

namespace App\Http\Controllers\Segmentations;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\Segmentations;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use DB;

class SegmentationsController extends Controller
{

    private $sortBy;
    private $sortDir;
    private $perPage;

    public function __construct() {
        $this->sortBy = request()->get('sortBy', 'segmentations.created_at');
        $this->sortDir = request()->get('sortDir', 'desc');
        $this->perPage = request()->get('perPage', 10);
    }

    public function getAllData(){
        $query = Segmentations::query()->with(['getCreatedBy', 'getUpdatedBy']);
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
        $data['tableName'] = 'segmentations';
        $data['page_title'] = 'Segmentations';
        $data['segmentations'] = self::getAllData()->paginate($this->perPage)->withQueryString();
        $data['queryParams'] = request()->query();

        return Inertia::render("Segmentations/Segmentations", $data);
    }

    public function create(Request $request){

        $validatedFields = $request->validate([
            'segmentation_column' => 'required|string|max:50',
            'segmentation_code' => 'required|string|max:10|unique:segmentations,segmentation_code',
            'segmentation_description' => 'required|string|max:255',
            'import_header_name' => 'required|string|max:255',
        ]);

        try {

            Segmentations::create([
                'segmentation_column' => $validatedFields['segmentation_column'],
                'segmentation_code' => $validatedFields['segmentation_code'],
                'segmentation_description' => $validatedFields['segmentation_description'],
                'import_header_name' => $validatedFields['import_header_name'],
                'status' => 'ACTIVE',
                'created_by' => CommonHelpers::myId(),
            ]);
    
            return back()->with(['message' => 'Segmentation Creation Success!', 'type' => 'success']);

        }

        catch (\Exception $e) {
            CommonHelpers::LogSystemError('Segmentations', $e->getMessage());
            return back()->with(['message' => 'Segmentation Creation Failed!', 'type' => 'error']);
        }
        
    }

    public function update(Request $request){

        $validatedFields = $request->validate([
            'segmentation_column' => 'required|string|max:50',
            'segmentation_code' => 'required|string|max:10',
            'segmentation_description' => 'required|string|max:255',
            'import_header_name' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        try {
    
            $segmentations = Segmentations::find($request->id);

            if (!$segmentations) {
                return back()->with(['message' => 'Segmentation not found!', 'type' => 'error']);
            }
    
            $SegmentationCodeExist = Segmentations::where('segmentation_code', $request->segmentation_code)->exists();

            if ($request->segmentation_code !== $segmentations->segmentation_code) {
                if (!$SegmentationCodeExist) {
                    $segmentations->segmentation_code = $validatedFields['segmentation_code'];
                } else {
                    return back()->with(['message' => 'Segmentation Code already exists!', 'type' => 'error']);
                }
            }
    
            $segmentations->segmentation_column = $validatedFields['segmentation_column'];
            $segmentations->segmentation_description = $validatedFields['segmentation_description'];
            $segmentations->import_header_name = $validatedFields['import_header_name'];
            $segmentations->status = $validatedFields['status'];
            $segmentations->updated_by = CommonHelpers::myId();
            $segmentations->updated_at = now();
    
            $segmentations->save();
    
            return back()->with(['message' => 'Segmentation Updating Success!', 'type' => 'success']);
        }  

        catch (\Exception $e) {

            CommonHelpers::LogSystemError('Segmentations', $e->getMessage());
            return back()->with(['message' => 'Segmentation Updating Failed!', 'type' => 'error']);
        }
    }
}