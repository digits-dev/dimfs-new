<?php

namespace Database\Seeders;

use App\Models\AdmModels\admMenusPrivileges;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmMenuPrivilegesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            [
                'id_adm_menus' => 1,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 2,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 3,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 4,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 5,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 6,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 7,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 8,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 9,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 10,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 11,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 12,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 13,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 14,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 15,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 16,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 17,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 18,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 19,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 20,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 21,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 22,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 23,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 24,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 25,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 26,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 27,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 28,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 29,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 30,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 31,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 32,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 33,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 34,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 35,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 36,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 37,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 38,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 39,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 40,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 41,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 42,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 43,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 44,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 45,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 46,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 47,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 48,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 49,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 50,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 51,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 52,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 53,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 54,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 55,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 56,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 57,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 58,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 59,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 60,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 61,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 62,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 63,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 64,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 65,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 66,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 67,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 68,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 69,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 70,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 71,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 72,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 73,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 74,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 75,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 76,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 77,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 78,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 79,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 80,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 81,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 82,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 84,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 85,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 86,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 87,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 88,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 89,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 90,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 91,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 92,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 93,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 94,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 95,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 96,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 97,
                'id_adm_privileges' => 1
            ],
            [
                'id_adm_menus' => 98,
                'id_adm_privileges' => 1
            ],
        ];

        foreach ($menus as $menu) {
            admMenusPrivileges::updateOrCreate(
                [
                    'id_adm_menus' => $menu['id_adm_menus'],
                    'id_adm_privileges' => $menu['id_adm_privileges'],
                ]
            );
        }
    }
}