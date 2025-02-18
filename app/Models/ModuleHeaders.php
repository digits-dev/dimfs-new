<?php

namespace App\Models;

use app\Helpers\CommonHelpers;
use App\Models\AdmModels\AdmModules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleHeaders extends Model
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
        'module_id',
        'name',
        'width',
        'header_name',
        'validation',
        'type',
        'table',
        'table_join',
        'table_select_value',
        'table_select_label',
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
        'module_id',
        'name',
        'header_name',
        'validation',
        'width',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static function getModuleHeaders()
    {
        return self::query()
            ->whereNotNull('table_select_label')
            ->select('name', 'table', 'table_select_label')
            ->get()
            ->keyBy('name');
    }

    public function scopeSearchAndFilter($query, $request){

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                foreach ($this->filterable as $field) {
                    if ($field === 'module_id') {
                        $query->orWhereHas('getModule', function ($query) use ($search) {
                            $query->where('name', 'LIKE', "%$search%");
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

    public function getModule() {
        return $this->belongsTo(AdmModules::class, 'module_id', 'id');
    }
}