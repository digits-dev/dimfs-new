<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GashaponItemMaster extends Model
{
    use HasFactory;

    
    protected $table = 'gashapon_item_masters';

    protected $fillable = [

        'id',
        'for_approval',
        'approval_status',
        'acctg_approval_status',
        'jan_no',
        'digits_code',
        'item_no',
        'sap_no',
        'initial_wrr_date',
        'latest_wrr_date',
        'item_description',
        'model_description',
        'gashapon_brands_id',
        'gashapon_categories_id',
        'gashapon_product_types_id',
        'gashapon_incoterms_id',
        'gashapon_uoms_id',
        'gashapon_warehouse_categories_id',
        'gashapon_inventory_types_id',
        'gashapon_vendor_types_id',
        'gashapon_vendor_groups_id',
        'gashapon_countries_id',
        'gashapon_sku_statuses_id',
        'msrp',
        'current_srp',
        'no_of_tokens',
        'store_cost',
        'sc_margin',
        'lc_per_pc',
        'lc_margin_per_pc',
        'lc_per_carton',
        'lc_margin_per_carton',
        'dp_ctn',
        'pcs_dp',
        'moq',
        'pcs_ctn',
        'no_of_ctn',
        'no_of_assort',
        'currencies_id',
        'supplier_cost',
        'status',
        'age_grade',
        'battery',
        'approved_by',
        'approved_at',
        'approved_by_acctg',
        'approved_at_acctg',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at',
       
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $filterable = [
 
        'for_approval',
        'approval_status',
        'acctg_approval_status',
        'jan_no',
        'digits_code',
        'item_no',
        'sap_no',
        'initial_wrr_date',
        'latest_wrr_date',
        'item_description',
        'model_description',
        'gashapon_brands_id',
        'gashapon_categories_id',
        'gashapon_product_types_id',
        'gashapon_incoterms_id',
        'gashapon_uoms_id',
        'gashapon_warehouse_categories_id',
        'gashapon_inventory_types_id',
        'gashapon_vendor_types_id',
        'gashapon_vendor_groups_id',
        'gashapon_countries_id',
        'gashapon_sku_statuses_id',
        'msrp',
        'current_srp',
        'no_of_tokens',
        'store_cost',
        'sc_margin',
        'lc_per_pc',
        'lc_margin_per_pc',
        'lc_per_carton',
        'lc_margin_per_carton',
        'dp_ctn',
        'pcs_dp',
        'moq',
        'pcs_ctn',
        'no_of_ctn',
        'no_of_assort',
        'currencies_id',
        'supplier_cost',
        'status',
        'age_grade',
        'battery',
        'approved_by',
        'approved_at',
        'approved_by_acctg',
        'approved_at_acctg',
        'created_by',
        'updated_by',
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
                    elseif ($field === 'updated_by')  {
                        $query->orWhereHas('getUpdatedBy', function ($query) use ($search) {
                            $query->where('name', 'LIKE', "%$search%");
                        });
                    } elseif (in_array($field, ['created_at', 'updated_at'])) {
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
                $query->where($field, 'LIKE', "%$value%");
            }
        }
    
        return $query;
        
    }

    public function getCreatedBy() {
        return $this->belongsTo(AdmUser::class, 'created_by', 'id');
    }
    
    public function getUpdatedBy() {
        return $this->belongsTo(AdmUser::class, 'updated_by', 'id');
    }
}
