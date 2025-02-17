<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMasterApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'item_values',
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
        'item_values',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
}
