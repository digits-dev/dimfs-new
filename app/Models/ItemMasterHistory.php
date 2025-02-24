<?php

namespace App\Models;

use app\Helpers\CommonHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMasterHistory extends Model
{
    use HasFactory;

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
        'item_values',
        'action',
        'item_master_id',
        'status',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
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
        'action',
        'status',
        'item_master_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'rejected_by',
        'rejected_at',
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
                    } 
                    elseif ($field === 'item_master_id')  {
                        $query->orWhereHas('getItem', function ($query) use ($search) {
                            $query->where('digits_code', 'LIKE', "%$search%");
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
  
    public function getRejectedBy() {
        return $this->belongsTo(AdmUser::class, 'rejected_by', 'id');
    }

    public function getItem() {
        return $this->belongsTo(ItemMaster::class, 'item_master_id', 'id');
    }
  
}