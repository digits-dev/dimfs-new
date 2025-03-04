<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'table_name',
        'fields',
        'relations',
        'rules',
        'endpoint',
        'method',
        'auth_type',
        'enable_logging',
        'rate_limit',
        'status'
    ];

    // Statuses
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    // API Methods
    const METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'ALL'];

    // Authentication Types
    const AUTH_TYPES = ['X-API-KEY', 'jwt', 'api_key', 'oauth', 'hmac'];

    // Cast JSON fields to array
    protected $casts = [
        'fields' => 'array',
        'relations' => 'array',
        'rules' => 'array',
    ];

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public static function getActiveApis()
    {
        return self::where('status', self::STATUS_ACTIVE)->get();
    }
}
