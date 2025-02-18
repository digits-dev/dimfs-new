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
        'dtp_rf',
        'dtp_rf_percentage',
        'dtp_dcon',
        'dtp_dcon_percentage',
        'landed_cost',
        'working_landed_cost',
        'working_dtp_rf',
        'working_dtp_rf_percentage',
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
        'dtp_rf',
        'dtp_rf_percentage',
        'dtp_dcon',
        'dtp_dcon_percentage',
        'landed_cost',
        'working_landed_cost',
        'working_dtp_rf',
        'working_dtp_rf_percentage',
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
}
