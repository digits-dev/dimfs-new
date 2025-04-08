<?php

namespace App\Models\AdmModels;

use app\Helpers\CommonHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmPrivileges extends Model
{
    use HasFactory;

    public const SUPERADMIN = 1;
    public const MCBTM = 2;
    public const MCBTL = 3;
    public const ECOMMSTOREMKTGTM = 4;
    public const COSTACCTG = 5;
    public const ACCTGHEAD = 6;
    public const SALESACCTG = 7;
    public const WIMSTM = 8;
    public const WHSTL = 9;
    public const RTLSTOREMDSGTL = 10;
    public const RMATM = 11;
    public const ECOMMSTOREMDSGTL = 12;
    public const ECOMMHEAD = 13;
    public const ECOMMSTOREOPSTL = 14;
    public const ECOMMSTOREMKTGTL = 15;
    public const ECOMMSTOREMDSGTM = 16;
    public const ECOMMSTOREOPSTM = 17;
    public const WIMSTL = 18;
    public const RTLSTOREMDSGTM = 19;
    public const BRANDMKTGTM = 20;
    public const BRANDHEAD = 21;
    public const FRAHEAD = 22;
    public const FRATL = 23;
    public const RTLSTOREOPSTM = 24;
    public const DISTRITL2 = 25;
    public const RTLSTOREOPSTL = 26;
    public const TRAINING = 27;
    public const WHSTM = 28;
    public const RTLSTOREMKTGTL = 29;
    public const DISTRIAVP = 30;
    public const FRANCHISEE = 31;
    public const ICTM = 32;
    public const ADVANCED = 33;
    public const ICTL = 34;
    public const REPORTS = 35;
    public const AUD = 36;
    public const RTLHEAD = 37;
    public const RTLSTOREMKTGTM = 38;
    public const ASDHEAD = 39;
    public const DISTRITM = 40;
    public const BRANDMKTGTL = 41;
    public const RMATL = 42;
    public const RTLSTOREDA = 43;
    public const SVCTL = 44;
    public const ECOMMSTOREMDSGTL2 = 45;
    public const RTLSTOREMDSGTL2 = 46;
    public const DISTRITL = 47;
    public const BRANDMDSGTM = 48;
    public const BRANDMKTGASST = 49;
    public const ARTL = 50;
    public const MDSG = 51;
    public const CHANNELMDSGTM = 52;
    public const MDSGASST = 53;
    public const ECOMMPRODUCTTL = 54;
    public const ECOMMPRODUCTTM = 55;
    public const BRANDMKTGAVP = 56;
    public const CONCEPTMKTGTL = 57;
    public const CONCEPTMKTGTM = 58;
    public const CONCEPTMKTGASST = 59;
    public const RTLAVP = 60;


    protected $guarded = [];

    public function scopeGetData($query){
        return $query;
    }
    
    protected $fillable = [
        'id',
        'name',
        'is_superadmin',
        'theme_color',
        'created_at',
        'updated_at'
    ];

    protected $filterable = [
        'name',
        'is_superadmin',
        'theme_color',
        'created_at',
        'updated_at'
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
                    $query->where($field, '=', $value);
                }
                else{
                    $query->where($field, 'LIKE', "%$value%");
                }
            }
        }
    
        return $query;

    }
}
