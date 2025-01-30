<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSystemError extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_name', 
        'error_details',
        'created_by',
        'created_at',
    ];
}
