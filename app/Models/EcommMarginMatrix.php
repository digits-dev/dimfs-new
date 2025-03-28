<?php

namespace App\Models;

use app\Helpers\CommonHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommMarginMatrix extends Model
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


    protected $fillable = [
        'id',
        'brands_id',
        'margin_category',
        'margin_categories_id',
        'vendor_types_id',
        'matrix_type',
        'max',
        'min',
        'store_margin_percentage',
        'status',
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
        'brands_id',
        'margin_category',
        'margin_categories_id',
        'vendor_types_id',
        'matrix_type',
        'max',
        'min',
        'store_margin_percentage',
        'status',
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
                    else if ($field === 'status') {
                        $query->orWhere($field, '=', $search);
                    }
                    elseif ($field === 'updated_by')  {
                        $query->orWhereHas('getUpdatedBy', function ($query) use ($search) {
                            $query->where('name', 'LIKE', "%$search%");
                        });
                    }
                    elseif ($field === 'brands_id')  {
                        $query->orWhereHas('getBrand', function ($query) use ($search) {
                            $query->where('brand_description', 'LIKE', "%$search%");
                        });
                    }
                    elseif ($field === 'vendor_types_id')  {
                        $query->orWhereHas('getVendorType', function ($query) use ($search) {
                            $query->where('vendor_type_description', 'LIKE', "%$search%");
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
                if ($field === 'brands_id') {
                    $query->where($field, '=', $value);
                }
                if ($field === 'margin_category') {
                    $query->where($field, '=', $value);
                }
                else{
                    $query->where($field, 'LIKE', "%$value%");
                }
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

    public function getBrand() {
        return $this->belongsTo(Brands::class, 'brands_id', 'id');
    }

    public function getVendorType() {
        return $this->belongsTo(VendorTypes::class, 'vendor_types_id', 'id');
    }
}
