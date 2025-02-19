<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendors extends Model
{
    use HasFactory;

    protected $table = 'vendors';

    protected $fillable = [
        'id',
        'brands_id',
        'vendor_name',
        'vendor_types_id',
        'incoterms_id',
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
        'vendor_name',
        'vendor_types_id',
        'incoterms_id',
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
                    if ($field === 'brands_id') {
                        $query->orWhereHas('getBrand', function ($query) use ($search) {
                            $query->where('brand_description', 'LIKE', "%$search%");
                        });
                    }
                    else if ($field === 'status') {
                        $query->orWhere($field, '=', $search);
                    }
                    if ($field === 'vendor_types_id') {
                        $query->orWhereHas('getVendorType', function ($query) use ($search) {
                            $query->where('vendor_type_description', 'LIKE', "%$search%");
                        });
                    }
                    if ($field === 'incoterms_id') {
                        $query->orWhereHas('getIncoterm', function ($query) use ($search) {
                            $query->where('incoterms_description', 'LIKE', "%$search%");
                        });
                    }
                    if ($field === 'created_by') {
                        $query->orWhereHas('getCreatedBy', function ($query) use ($search) {
                            $query->where('name', 'LIKE', "%$search%");
                        });
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
                    $query->orWhere($field, '=', $value);
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

    public function getIncoterm() {
        return $this->belongsTo(Incoterms::class, 'incoterms_id', 'id');
    }
    
}