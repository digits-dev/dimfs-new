<?php

namespace App\Models;

use app\Helpers\CommonHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMasterAccountingApproval extends Model
{
    use HasFactory;

	public static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {
            $model->created_by = CommonHelpers::myId();
            $model->updated_at = null;
        });
        static::updating(function($model)
        {
            $model->updated_by = CommonHelpers::myId();
        });
    }

	protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'effective_date' => 'datetime:Y-m-d',
    ];
	
    protected $fillable = [

        'id',
        'status',
        'item_masters_id',
        'brands_id',
        'categories_id',
        'margin_categories_id',
        'support_types_id',
        'current_srp',
        'promo_srp',
        'store_cost',
        'store_cost_percentage',
        'ecom_store_cost',
        'ecom_store_cost_percentage',
        'landed_cost',
        'landed_cost_sea',
        'actual_landed_cost',
        'working_store_cost',
        'working_store_cost_percentage',
        'ecom_working_store_cost',
        'ecom_working_store_cost_percentage',
        'working_landed_cost',
        'effective_date',
        'duration_from',
        'duration_to',
        'encoder_privileges_id',
        'approver_privileges_id',
        'created_by',
        'updated_by',
        'approved_by',
        'rejected_by',
        'approved_at',
        'rejected_at',
        'created_at',
        'updated_at',
    
    ];


	protected $filterable = [

        'status',
        'item_masters_id',
        'brands_id',
        'categories_id',
        'margin_categories_id',
        'support_types_id',
        'current_srp',
        'promo_srp',
        'store_cost',
        'store_cost_percentage',
        'ecom_store_cost',
        'ecom_store_cost_percentage',
        'landed_cost',
        'landed_cost_sea',
        'actual_landed_cost',
        'working_store_cost',
        'working_store_cost_percentage',
        'ecom_working_store_cost',
        'ecom_working_store_cost_percentage',
        'working_landed_cost',
        'effective_date',
        'duration_from',
        'duration_to',
        'encoder_privileges_id',
        'approver_privileges_id',
        'created_by',
        'updated_by',
        'approved_by',
        'rejected_by',
        'approved_at',
        'rejected_at',
        'created_at',
        'updated_at',
    
    ];


	public function scopeSearchAndFilter($query, $request){

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                foreach ($this->filterable as $field) {
                    if ($field === 'created_by') {
                        $query->orWhereHas('getCreatedBy', function ($query) use ($search) {
                            $query->where('name', 'LIKE', "%$search%");
                        });
                    }
                    if ($field === 'item_masters_id') {
                        $query->orWhereHas('getItem', function ($query) use ($search) {
                            $query->where('digits_code', 'LIKE', "%$search%");
                        });
                    }
                    if ($field === 'brands_id') {
                        $query->orWhereHas('getBrand', function ($query) use ($search) {
                            $query->where('brand_description', 'LIKE', "%$search%");
                        });
                    }
                    if ($field === 'categories_id') {
                        $query->orWhereHas('getCategory', function ($query) use ($search) {
                            $query->where('category_description', 'LIKE', "%$search%");
                        });
                    }
                    if ($field === 'margin_categories_id') {
                        $query->orWhereHas('getMarginCategory', function ($query) use ($search) {
                            $query->where('margin_category_description', 'LIKE', "%$search%");
                        });
                    }
                    if ($field === 'support_types_id') {
                        $query->orWhereHas('getSupportType', function ($query) use ($search) {
                            $query->where('support_type_description', 'LIKE', "%$search%");
                        });
                    }
                    if ($field === 'approved_by') {
                        $query->orWhereHas('getApprovedBy', function ($query) use ($search) {
                            $query->where('name', 'LIKE', "%$search%");
                        });
                    }
                    if ($field === 'rejected_by') {
                        $query->orWhereHas('getRejectedBy', function ($query) use ($search) {
                            $query->where('name', 'LIKE', "%$search%");
                        });
                    }
                    else if ($field === 'status') {
                        $query->orWhere($field, '=', $search);
                    }
                    elseif ($field === 'updated_by')  {
                        $query->orWhereHas('getUpdatedBy', function ($query) use ($search) {
                            $query->where('name', 'LIKE', "%$search%");
                        });
                    }
                    
                    elseif (in_array($field, ['created_at', 'updated_at'])) {
                        $query->orWhereDate($field, $search);
                    }
                    else {
                        $query->orWhere($field, 'LIKE', "%$search%");
                    }
                }
            });
        }

        foreach ($this->filterable as $field) {
            if ($request->filled($field)) {
                $value = $request->input($field);
                if ($field === 'status') {
                    $query->where($field, '=', $value);
                }
                if ($field === 'item_masters_id') {
                    $query->orWhereHas('getItem', function ($query) use ($value) {
                        $query->where('digits_code', 'LIKE', "%$value%");
                    });
                }
                else{
                    $query->where($field, 'LIKE', "%$value%");
                }
            }
        }
    
        return $query;

    }

    public function getItem() {
        return $this->belongsTo(ItemMaster::class, 'item_masters_id', 'id');
    }

	public function getBrand() {
		return $this->belongsTo(Brands::class, 'brands_id', 'id');
	}

	public function getCategory() {
		return $this->belongsTo(Categories::class, 'categories_id', 'id');
	}

	public function getMarginCategory() {
		return $this->belongsTo(MarginCategories::class, 'margin_categories_id', 'id');
	}

	public function getSupportType() {
		return $this->belongsTo(SupportTypes::class, 'support_types_id', 'id');
	}

    public function getCreatedBy() {
        return $this->belongsTo(AdmUser::class, 'created_by', 'id');
    }
    public function getApprovedBy() {
        return $this->belongsTo(AdmUser::class, 'approved_by', 'id');
    }
    public function getRejectedBy() {
        return $this->belongsTo(AdmUser::class, 'rejected_by', 'id');
    }
    
    public function getUpdatedBy() {
        return $this->belongsTo(AdmUser::class, 'updated_by', 'id');
    }
	

    // ------------------------------------- FOR ACCOUNTING IMPORT --------------------------------------------------------//

    public static function calculateCostPercentage($cost, $item_details) {
        
        $value = 0.0000;
        
        $selling_price = (!empty($item_details->promo_srp) && $item_details->promo_srp !== '0.00' && $item_details->promo_srp !== 0.00)
            ? $item_details->promo_srp
            : $item_details->current_srp;
    
        if ($selling_price > 0) {
            $value = ($selling_price - $cost) / $selling_price;
        }
    
        return number_format($value, 4, '.', '');
    }

    public static function checkLocalStoreCost($upload_values, $item_details){
	    
	    if(empty($item_details->promo_srp) || is_null($item_details->promo_srp) || $item_details->promo_srp ==='0.00' || $item_details->promo_srp === 0.00){

	        $srplc = (floatval($item_details->current_srp) - floatval($upload_values['landed_cost']));
			$csm = $srplc/floatval($item_details->current_srp);
            $ccsm = self::getComputedLocalMarginPercentage(number_format($csm,4, '.', ''), $item_details->vendor_types_id, $item_details->brands_id);
            if(number_format($csm,7, '.', '') < 0){
				return 1;
			}
            $addToLC = 1+(number_format($ccsm->store_margin_percentage, 4, '.', ''));
            $com_sc = $addToLC * floatval($upload_values['landed_cost']);
			if(number_format($com_sc,2, '.', '') != number_format($upload_values['store_cost'], 2, '.', '')){
			    return 1;
			}
		}
		else{

		    $srplc = (floatval($item_details->promo_srp) - floatval($upload_values['landed_cost']));
			$csm = $srplc / floatval($item_details->promo_srp);
			$ccsm = self::getComputedLocalMarginPercentage(number_format($csm,4, '.', ''), $item_details->vendor_types_id,$item_details->brands_id);

            if(number_format($csm,7, '.', '') < 0){
				return 1;
			}

            $addToLC = 1+(number_format($ccsm->store_margin_percentage,4, '.', ''));
            $com_sc = $addToLC * floatval($upload_values['landed_cost']);
            
			if(number_format($com_sc,2, '.', '') != number_format($upload_values['store_cost'],2, '.', '')){
			    return 1;
			}
		}
		return 0;
	}

    public static function getComputedLocalMarginPercentage($margin_percentage, $vendor_type_id, $brands){

        
        $matrix = MarginMatrix::whereRaw($margin_percentage.' between `min` and `max`')
            ->where('matrix_type','ADD TO LC')
            ->where('vendor_types_id', $vendor_type_id)
            ->where('brands_id',$brands)
            ->where('status','ACTIVE')->first();   

       
            if(empty($matrix)){

            $matrix = MarginMatrix::whereRaw($margin_percentage.' between `min` and `max`')
            ->where('matrix_type','ADD TO LC')
            ->where('vendor_types_id', $vendor_type_id)
            ->whereNull('brands_id')
            ->where('status','ACTIVE')->first();

     
            return $matrix;
            
        }
     
        return $matrix;
    
    }

    public static function checkUnitStoreCost($upload_values, $item_details){
	    
	    if(empty($item_details->promo_srp) || $item_details->promo_srp ==='0.00' || $item_details->promo_srp === 0.00){
			$csm = ($item_details->current_srp - $upload_values['store_cost']) / $item_details->current_srp;
            if(number_format($csm,7, '.', '') < 0){
				return 1;
			}

		}
		else{
			$csm = ($item_details->promo_srp - $upload_values['store_cost']) / $item_details->promo_srp; 
            if(number_format($csm,7, '.', '') < 0){
				return 1;
			}

		}
		return 0;
	}

    public static function checkAccStoreCost($upload_values, $item_details, $store_cost_percentage){
	    
	    if(empty($item_details->promo_srp) || $item_details->promo_srp ==='0.00' || $item_details->promo_srp === 0.00){
		
			$csm = ($item_details->current_srp - $upload_values['landed_cost']) / $item_details->current_srp;
			$ccsm = self::getComputedMarginPercentage(number_format($csm,4, '.', ''), "ACCESSORIES", $item_details->brands_id);
			
			switch($ccsm->matrix_type){
			    case 'BASED ON MATRIX': {
			        if(number_format($ccsm->store_margin_percentage,4, '.', '') != $store_cost_percentage || number_format($csm,7, '.', '') < 0) {
        				return 1;
        			}
			    }
			    break;
			    
			    case 'ADD TO LC':{
			        $ccsm = self::getComputedUploadMarginPercentage(number_format($csm,4, '.', ''), $item_details->margin_categories_id, "ACCESSORIES", $item_details->brands_id);
            
        			$com_sc = (1 + (number_format($ccsm->store_margin_percentage,4, '.', '')) * $upload_values['landed_cost']);
        			if(number_format($com_sc,2, '.', '') != number_format($upload_values['store_cost'], 2, '.', '')){
        			    return 1;
        			}
			    }
			    break;
			}
		}

		else{
		    
			$csm = ($item_details->promo_srp - $upload_values['landed_cost']) / $item_details->promo_srp;
			$ccsm = self::getComputedMarginPercentage(number_format($csm,4, '.', ''), "ACCESSORIES", $item_details->brands_id);
			
			switch($ccsm->matrix_type){
			    case 'BASED ON MATRIX': {
			        if(number_format($ccsm->store_margin_percentage,4, '.', '') != $store_cost_percentage || number_format($csm,7, '.', '') < 0) {
        				return 1;
        			}
			    }
			    break;
			    
			    case 'ADD TO LC':{
			        $ccsm = self::getComputedUploadMarginPercentage(number_format($csm,4, '.', ''), $item_details->margin_categories_id, "ACCESSORIES", $item_details->brands_id);
            
        			$com_sc = (1 + (number_format($ccsm->store_margin_percentage,4, '.', '')) * $upload_values['landed_cost']);
        			if(number_format($com_sc,2, '.', '') != number_format($upload_values['store_cost'],2, '.', '')){
        			    return 1;
        			}
			    }
			    break;
			}
		}
		return 0;
	}

    public static function getComputedMarginPercentage($margin_percentage, $margin_category, $brand){
	    
	    $marginMatrix = MarginMatrix::where('margin_category',$margin_category)
	        ->whereRaw($margin_percentage.' between `min` and `max`')
            ->where('brands_id', $brand)->first();
        
        if(empty($marginMatrix)){
            return MarginMatrix::where('margin_category',$margin_category)
            ->whereRaw($margin_percentage.' between `min` and `max`')
            ->whereNull('brands_id')->first();
        }
        else{
            return $marginMatrix;
        }
		
	}

    public static function getComputedUploadMarginPercentage($margin_percentage, $margin_categories_id, $margin_category, $brand){
    
        $marginMatrix = MarginMatrix::where('margin_category',$margin_category)
            ->whereRaw($margin_percentage.' between `min` and `max`')
            ->where('matrix_type','ADD TO LC')
            ->where('brands_id', $brand)
            ->where('status','ACTIVE')
            ->where('margin_categories_id','LIKE', '%'.$margin_categories_id.'%')->first();

        if(empty($marginMatrix)){
            return MarginMatrix::where('margin_category',$margin_category)
            ->where('matrix_type','ADD TO LC')
            ->where('status','ACTIVE')
            ->whereNull('brands_id')
            ->where('margin_categories_id','LIKE', '%'.$margin_categories_id.'%')->first();
        }
        else{
            return $marginMatrix;
        }
    
    }

    public static function checkUntWorkingStoreCost($upload_values, $item_details){

        $vendor_type = ["LOC-CON","LOC-OUT","LR-CON","LR-OUT"];

	    if(in_array($item_details->getVendorType->vendor_type_code, $vendor_type)){
            return 0;
        }

	    if(empty($item_details->promo_srp) || $item_details->promo_srp ==='0.00' || $item_details->promo_srp === 0.00)
        {
			$csm = ($item_details->current_srp - $upload_values['working_store_cost']) / $item_details->current_srp;

            if(number_format($csm,7, '.', '') < 0){
				return 1;
			}

		}else{
			$csm = ($item_details->promo_srp - $upload_values['working_store_cost']) / $item_details->promo_srp;

            if(number_format($csm,7, '.', '') < 0){
				return 1;
			}

		}
		return 0;
	}

    public static function checkAccWorkingStoreCost($upload_values, $item_details, $store_cost_percentage){

        $vendor_type = ["LOC-CON","LOC-OUT","LR-CON","LR-OUT"];

	    if(in_array($item_details->getVendorType->vendor_type_code, $vendor_type)){
            return 0;
        }

	    if(empty($item_details->promo_srp) || $item_details->promo_srp ==='0.00' || $item_details->promo_srp === 0.00){
		
			$csm = ($item_details->current_srp - $upload_values['working_landed_cost']) / $item_details->current_srp;
			$ccsm = self::getComputedMarginPercentage(number_format($csm,4, '.', ''), $item_details->margin_categories_id, "ACCESSORIES", $item_details->brands_id);
			
			if(number_format($ccsm->store_margin_percentage,4, '.', '') != $store_cost_percentage || number_format($csm,7, '.', '') < 0) {
				return 1;
			}
		}

		else{
		    
			$csm = ($item_details->promo_srp - $upload_values['working_landed_cost']) / $item_details->promo_srp;
			$ccsm = self::getComputedMarginPercentage(number_format($csm,4, '.', ''), $item_details->margin_categories_id, "ACCESSORIES", $item_details->brands_id);
			
			if(number_format($ccsm->store_margin_percentage,4, '.', '') != $store_cost_percentage || number_format($csm,7, '.', '') < 0) {
				return 1;
			}
		}
		return 0;
	}
    
    public static function checkAccEcomWorkingStoreCost($upload_values, $item_details, $ecomm_store_cost_percentage){
        
        $vendor_type = ["LOC-CON","LOC-OUT","LR-CON","LR-OUT"];

	    if(in_array($item_details->getVendorType->vendor_type_code, $vendor_type)){
            return 0;
        }

	    if(empty($item_details->promo_srp) || $item_details->promo_srp ==='0.00' || $item_details->promo_srp === 0.00){
		
			$csm = ($item_details->current_srp - $upload_values['working_landed_cost']) / $item_details->current_srp;
			$ccsm = self::getComputedEcomMarginPercentage(number_format($csm,4, '.', ''), "ACCESSORIES", $item_details->brands_id);
			
			if(number_format($ccsm->ecom_store_margin_percentage,4, '.', '') != $ecomm_store_cost_percentage || number_format($csm,7, '.', '') < 0) {
				return 1;
			}
		}

		else{
		    
			$csm = ($item_details->promo_srp - $upload_values['working_landed_cost']) / $item_details->promo_srp;
			$ccsm = self::getComputedEcomMarginPercentage(number_format($csm,4, '.', ''), "ACCESSORIES", $item_details->brands_id);
			
			if(number_format($ccsm->ecom_store_margin_percentage,4, '.', '') != $ecomm_store_cost_percentage || number_format($csm,7, '.', '') < 0) {
				return 1;
			}
		}

		return 0;
	}

    public static function getComputedEcomMarginPercentage($margin_percentage, $margin_category, $brand){
	    
	    $ecommMarginMatrix = EcommMarginMatrix::whereRaw($margin_percentage.' between `min` and `max`')
            ->where('margin_category',$margin_category)
            ->where('brands_id', $brand)->first();
        
        if(empty($ecommMarginMatrix)){
            return EcommMarginMatrix::whereRaw($margin_percentage.' between `min` and `max`')
            ->where('margin_category',$margin_category)
            ->whereNull('brands_id')->first();
        }
        else{
            return $ecommMarginMatrix;
        }
	}

    
}
