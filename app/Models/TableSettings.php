<?php

namespace App\Models;

use App\Models\AdmModels\AdmPrivileges;
use App\Models\AdmModels\AdmModules;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableSettings extends Model
{
    use HasFactory;

    protected $table = 'table_settings';

    protected $fillable = [
        'id',
        'adm_privileges_id',
        'adm_moduls_id',
        'action_types_id',
        'table_name',
        'report_header',
        'report_query',
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
        'adm_privileges_id',
        'adm_moduls_id',
        'action_types_id',
        'table_name',
        'report_header',
        'report_query',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public static function getActiveHeaders($moduleId, $actionTypeId, $privilegeId)
    {
        return explode(',', self::where('adm_moduls_id', $moduleId)
            ->where('action_types_id', $actionTypeId)
            ->where('adm_privileges_id', $privilegeId)
            ->where('status', 'ACTIVE')
            ->pluck('report_header')
            ->first());
    }

    public function scopeSearchAndFilter($query, $request){
        $filter_column = $request['filter_column'] ?? [];

        if ($request['search']) {
            $search = $request['search'];
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
        else {
            //filter function
            $query->where(function($w) use ($filter_column) {
                if(is_array($filter_column)){
                    foreach((array)$filter_column as $key=>$fc) {

                        $value = @$fc['value'];
                        $type  = @$fc['type'];

                        if($type == 'empty') {
                            $w->whereNull($key)->orWhere($key,'');
                            continue;
                        }

                        if($value=='' || $type=='') continue;

                        if($type == 'between') continue;

                        switch($type) {
                            default:
                                if($key && $type && $value) $w->where($key,$type,$value);
                            break;
                            case 'like':
                            case 'not like':
                                $value = '%'.$value.'%';
                                if($key && $type && $value) $w->where($key,$type,$value);
                            break;
                            case 'in':
                            case 'not in':
                                if($value) {
                                    $value = explode(',',$value);
                                    if($key && $value) $w->whereIn($key,$value);
                                }
                            break;
                        }
                    }
                }
            });
            if(is_array($filter_column)){
                foreach((array)$filter_column as $key=>$fc) {
                    $value = @$fc['value'];
                    $type  = @$fc['type'];
                    $sorting = @$fc['sort'];

                    if($sorting!='') {
                        if($key) {
                            $query->orderby($key,$sorting);
                            $filter_is_orderby = true;
                        }
                    }

                    if ($type=='between') {
                        if($key && $value) $query->whereBetween($key,$value);
                    }

                    else {
                        continue;
                    }
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

    public function getPrivilegeName() {
        return $this->belongsTo(AdmPrivileges::class, 'adm_privileges_id', 'id');
    }

    public function getModuleName() {
        return $this->belongsTo(AdmModules::class, 'adm_moduls_id', 'id');
    }
    public function getActionTypes() {
        return $this->belongsTo(ActionTypes::class, 'action_types_id', 'id');
    }
    

}