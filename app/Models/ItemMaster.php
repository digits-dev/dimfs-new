<?php

namespace App\Models;

use app\Helpers\CommonHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMaster extends Model
{
    use HasFactory;

    protected $table = 'item_masters';

    
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


    protected $fillable = [

        'id',
        'initial_wrr_date',
        'latest_wrr_date',
        'digits_code',
        'upc_code',
        'upc_code2',
        'upc_code3',
        'upc_code4',
        'upc_code5',
        'supplier_item_code',
        'item_description',
        'brands_id',
        'brand_groups_id',
        'brand_directions_id',
        'brand_marketings_id',
        'categories_id',
        'classifications_id',
        'sub_classifications_id',
        'store_categories_id',
        'margin_categories_id',
        'warehouse_categories_id',
        'model',
        'year_launch',
        'model_specifics_id',
        'colors_id',
        'actual_color',
        'vendors_id',
        'vendor_types_id',
        'incoterms_id',
        'inventory_types_id',
        'sku_statuses_id',
        'sku_legends_id',
        'currencies_id',
        'warranties_id',
        'original_srp',
        'current_srp',
        'promo_srp',
        'price_change',
        'effective_date',
        'moq',
        'purchase_price',
        'store_cost',
        'store_cost_percentage',
        'consignment_store_cost',
        'consignment_store_cost_percentage',
        'landed_cost',
        'working_landed_cost',
        'working_store_cost',
        'working_store_cost_percentage',
        'approved_by',
        'approved_at',
        'approved_by_acctg',
        'approved_at_acctg',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at',

        'compatibility',
        'apple_report_inclusion',
        'apple_lobs_id',
        'size',
        'size_value',
        'sizes_id',
        'uoms_id',
        'device_uid',
        'product_type',
        'item_length',
        'item_width',
        'item_height',
        'item_weight',
       
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $filterable = [
        'initial_wrr_date',
        'latest_wrr_date',
        'digits_code',
        'upc_code',
        'upc_code2',
        'upc_code3',
        'upc_code4',
        'upc_code5',
        'supplier_item_code',
        'item_description',
        'brands_id',
        'brand_groups_id',
        'brand_directions_id',
        'brand_marketings_id',
        'categories_id',
        'classifications_id',
        'sub_classifications_id',
        'store_categories_id',
        'margin_categories_id',
        'warehouse_categories_id',
        'model',
        'year_launch',
        'model_specifics_id',
        'colors_id',
        'actual_color',
        'vendors_id',
        'vendor_types_id',
        'incoterms_id',
        'inventory_types_id',
        'sku_statuses_id',
        'sku_legends_id',
        'currencies_id',
        'warranties_id',
        'original_srp',
        'current_srp',
        'promo_srp',
        'price_change',
        'effective_date',
        'moq',
        'purchase_price',
        'store_cost',
        'store_cost_percentage',
        'consignment_store_cost',
        'consignment_store_cost_percentage',
        'landed_cost',
        'working_landed_cost',
        'working_store_cost',
        'working_store_cost_percentage',
        'approved_by',
        'approved_at',
        'approved_by_acctg',
        'approved_at_acctg',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at',

        'compatibility',
        'apple_report_inclusion',
        'apple_lobs_id',
        'size',
        'size_value',
        'sizes_id',
        'uoms_id',
        'device_uid',
        'product_type',
        'item_length',
        'item_width',
        'item_height',
        'item_weight',
    ];

    public function scopeSearchAndFilter($query, $request){

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                foreach ($this->filterable as $field) {

                    $relationMappings = [
                        'created_by' => ['getCreatedBy', 'name'],
                        'updated_by' => ['getUpdatedBy', 'name'],
                        'approved_by' => ['getApprovedBy', 'name'],
                        'deleted_by' => ['getDeletedBy', 'name'],
                        'approved_by_acctg' => ['getApprovedByAcctg', 'name'],
                        'brands_id' => ['getBrand', 'brand_description'],
                        'brand_groups_id' => ['getBrandGroup', 'brand_group_description'],
                        'brand_directions_id' => ['getBrandDirection', 'brand_direction_description'],
                        'brand_marketings_id' => ['getBrandMarketing', 'brand_marketing_description'],
                        'categories_id' => ['getCategory', 'category_description'],
                        'classifications_id' => ['getClassification', 'class_description'],
                        'sub_classifications_id' => ['getSubClassification', 'subclass_description'],
                        'store_categories_id' => ['getStoreCategory', 'store_category_description'],
                        'margin_categories_id' => ['getMarginCategory', 'margin_category_description'],
                        'warehouse_categories_id' => ['getWarehouseCategory', 'warehouse_category_description'],
                        'model_specifics_id' => ['getModelSpecific', 'model_specific_description'],
                        'colors_id' => ['getColor', 'color_description'],
                        'vendors_id' => ['getVendor', 'vendor_name'],
                        'vendor_types_id' => ['getVendorType', 'vendor_type_description'],
                        'incoterms_id' => ['getIncoterm', 'incoterms_description'],
                        'inventory_types_id' => ['getInventoryType', 'inventory_type_description'],
                        'sku_statuses_id' => ['getSkuStatus', 'sku_status_description'],
                        'sku_legends_id' => ['getSkuLegend', 'sku_legend_description'],
                        'currencies_id' => ['getCurrency', 'currency_description'],
                        'warranties_id' => ['getWarranty', 'warranty_description'],
                        'apple_lobs_id' => ['getAppleLob', 'apple_lob_description'],
                        'sizes_id' => ['getSize', 'size_code'],
                        'uoms_id' => ['getUom', 'uom_code'],
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

    public function getDeletedBy() {
        return $this->belongsTo(AdmUser::class, 'deleted_by', 'id');
    }

    public function getApprovedByAcctg() {
        return $this->belongsTo(AdmUser::class, 'approved_by_acctg', 'id');
    }

    public function getBrand() {
        return $this->belongsTo(Brands::class, 'brands_id', 'id');
    }

    public function getBrandGroup() {
        return $this->belongsTo(BrandGroups::class, 'brand_groups_id', 'id');
    }
    
    public function getBrandDirection() {
        return $this->belongsTo(BrandDirections::class, 'brand_directions_id', 'id');
    }
    
    public function getBrandMarketing() {
        return $this->belongsTo(BrandMarketings::class, 'brand_marketings_id', 'id');
    }

    public function getCategory() {
        return $this->belongsTo(Categories::class, 'categories_id', 'id');
    }

    public function getClassification() {
        return $this->belongsTo(Classifications::class, 'classifications_id', 'id');
    }

    public function getSubClassification() {
        return $this->belongsTo(SubClassifications::class, 'sub_classifications_id', 'id');
    }

    public function getStoreCategory() {
        return $this->belongsTo(StoreCategories::class, 'store_categories_id', 'id');
    }

    public function getMarginCategory() {
        return $this->belongsTo(MarginCategories::class, 'margin_categories_id', 'id');
    }

    public function getWarehouseCategory() {
        return $this->belongsTo(WarehouseCategories::class, 'warehouse_categories_id', 'id');
    }

    public function getModelSpecific() {
        return $this->belongsTo(ModelSpecifics::class, 'model_specifics_id', 'id');
    }

    public function getColor() {
        return $this->belongsTo(Colors::class, 'colors_id', 'id');
    }

    public function getVendor() {
        return $this->belongsTo(Vendors::class, 'vendors_id', 'id');
    }

    public function getVendorType() {
        return $this->belongsTo(VendorTypes::class, 'vendor_types_id', 'id');
    }

    public function getIncoterm() {
        return $this->belongsTo(Incoterms::class, 'incoterms_id', 'id');
    }

    public function getInventoryType() {
        return $this->belongsTo(InventoryTypes::class, 'inventory_types_id', 'id');
    }

    public function getSkuStatus() {
        return $this->belongsTo(SkuStatuses::class, 'sku_statuses_id', 'id');
    }

    public function getSkuLegend() {
        return $this->belongsTo(SkuLegends::class, 'sku_legends_id', 'id');
    }

    public function getCurrency() {
        return $this->belongsTo(Currencies::class, 'currencies_id', 'id');
    }

    public function getWarranty() {
        return $this->belongsTo(Warranties::class, 'warranties_id', 'id');
    }

    public function getAppleLob() {
        return $this->belongsTo(AppleLobs::class, 'apple_lobs_id', 'id');
    }

    public function getUom() {
        return $this->belongsTo(Uoms::class, 'uoms_id', 'id');
    }

    public function getSize() {
        return $this->belongsTo(Sizes::class, 'sizes_id', 'id');
    }

    public function getItemSegmentations() {
        return $this->hasMany(ItemSegmentations::class, 'item_masters_id', 'id');
    }
}
