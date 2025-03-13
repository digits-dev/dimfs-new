<?php

namespace App\Models\AdmModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmModules extends Model
{
    use HasFactory;
    public function scopeGetData($query){
        return $query->where('is_protected', 0)->where('deleted_at', null);
    }

    public const ITEM_MASTER = 38;
    public const GASHAPON_ITEM_MASTER = 28;
    public const RMA_ITEM_MASTER = 79;
    public const ITEM_MASTER_APPROVAL_ACCOUNTING = 82;
}
