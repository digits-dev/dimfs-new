<?php

namespace App\Models;

use app\Helpers\CommonHelpers;
use App\Models\AdmModels\AdmModules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counters extends Model
{
    use HasFactory;

    protected $table = 'counters';

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
        'adm_module_id',
        'module_name',
        'counter_code',
        'code_identifier',
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
        'adm_module_id',
        'module_name',
        'counter_code',
        'code_identifier',
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
                if ($field === 'status') {
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

    public function getModule() {
        return $this->belongsTo(AdmModules::class, 'adm_module_id', 'id');
    }

    public function scopeGetCode($query, $module_name, $code_identifier) {
        return $query->where('adm_module_id', self::getItemModuleId($module_name))->where('code_identifier', $code_identifier)
        ->select('counter_code', 'code_identifier')
        ->first();
    }
    
    public static function getItemModuleId($module_name) {
        return AdmModules::where('table_name', $module_name)->value('id');
    }
    
    public static function incrementCode($module_name, $code_identifier) {
        return self::where('adm_module_id', self::getItemModuleId($module_name))
            ->where('code_identifier', $code_identifier)
            ->increment('counter_code');
    }
    
    
}