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
                $relationMappings = [
                    'created_by' => ['getCreatedBy', 'name'],
                    'updated_by' => ['getUpdatedBy', 'name'],
                    'approved_by' => ['getApprovedBy', 'name'],
                    'approved_by_acctg' => ['getApprovedByAcctg', 'name'],
                    'gashapon_brands_id' => ['getGashaponBrand', 'brand_description'],
                    'gashapon_categories_id' => ['getGashaponCategory', 'category_description'],
                    'gashapon_product_types_id' => ['getGashaponProductType', 'product_type_description'],
                    'gashapon_incoterms_id' => ['getGashaponIncoterm', 'incoterm_description'],
                    'gashapon_uoms_id' => ['getGashaponUoms', 'uom_description'],
                    'gashapon_warehouse_categories_id' => ['getGashaponWarehouseCategory', 'warehouse_category_description'],
                    'gashapon_inventory_types_id' => ['getGashaponInventoryType', 'inventory_type_description'],
                    'gashapon_vendor_types_id' => ['getGashaponVendorType', 'vendor_type_description'],
                    'gashapon_vendor_groups_id' => ['getGashaponVendorGroup', 'vendor_group_description'],
                    'gashapon_countries_id' => ['getGashaponCountry', 'country_description'],
                    'gashapon_sku_statuses_id' => ['getGashaponSkuStatus', 'status_description'],
                    'currencies_id' => ['getCurrency', 'currency_description'],
                  
                ];
                
            
                foreach ($this->filterable as $field) {
                    if (isset($relationMappings[$field])) {
                        [$relation, $column] = $relationMappings[$field];
                        $query->orWhereHas($relation, function ($query) use ($search, $column) {
                            $query->where($column, 'LIKE', "%$search%");
                        });
                    } elseif (in_array($field, ['created_at', 'updated_at'])) {
                        $query->orWhereDate($field, $search);
                    } else {
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

    public function getApprovedBy() {
        return $this->belongsTo(AdmUser::class, 'approved_by', 'id');
    }

    public function getApprovedByAcctg() {
        return $this->belongsTo(AdmUser::class, 'approved_by_acctg', 'id');
    }

    public function getGashaponBrand() {
        return $this->belongsTo(GashaponBrands::class, 'gashapon_brands_id', 'id');
    }

    public function getGashaponCategory() {
        return $this->belongsTo(GashaponCategories::class, 'gashapon_categories_id', 'id');
    }

    public function getGashaponProductType() {
        return $this->belongsTo(GashaponProductTypes::class, 'gashapon_product_types_id', 'id');
    }

    public function getGashaponIncoterm() {
        return $this->belongsTo(GashaponIncoterms::class, 'gashapon_incoterms_id', 'id');
    }

    public function getGashaponUoms() {
        return $this->belongsTo(GashaponUoms::class, 'gashapon_uoms_id', 'id');
    }

    public function getGashaponWarehouseCategory() {
        return $this->belongsTo(GashaponWarehouseCategories::class, 'gashapon_warehouse_categories_id', 'id');
    }

    public function getGashaponInventoryType() {
        return $this->belongsTo(GashaponInventoryTypes::class, 'gashapon_inventory_types_id', 'id');
    }

    public function getGashaponVendorType() {
        return $this->belongsTo(GashaponVendorTypes::class, 'gashapon_vendor_types_id', 'id');
    }

    public function getGashaponVendorGroup() {
        return $this->belongsTo(GashaponVendorGroups::class, 'gashapon_vendor_groups_id', 'id');
    }

    public function getGashaponCountry() {
        return $this->belongsTo(GashaponCountries::class, 'gashapon_countries_id', 'id');
    }

    public function getGashaponSkuStatus() {
        return $this->belongsTo(GashaponSkuStatuses::class, 'gashapon_sku_statuses_id', 'id');
    }

    public function getCurrency() {
        return $this->belongsTo(Currencies::class, 'currencies_id', 'id');
    }

}
