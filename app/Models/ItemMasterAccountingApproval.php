<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMasterAccountingApproval extends Model
{
    use HasFactory;

    // CALCULATE STORE COST PERCENTAGES
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


    // CHECK LOCAL STORE COST
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


    // CHECK UNIT STORE COST
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


    // CHECK ACCESSORRIES STORE COST

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
