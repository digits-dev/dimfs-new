<?php

namespace App\Models\AdmModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmMenus extends Model
{
    use HasFactory;

    public function children(){
        return $this->hasMany(AdmMenus::class, 'parent_id', 'id');
    }
}
