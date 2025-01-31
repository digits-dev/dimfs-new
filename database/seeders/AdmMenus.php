<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmMenus extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        self::submasterMenu();
        self::mainMenu();
    }

    public function submasterMenu() {
     
    }

    public function mainMenu() {
        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Dashboard',
            ],
            [
                'name'              => 'Dashboard',
                'type'              => 'Route',
                'path'              => 'Dashboard\DashboardControllerGetIndex',
                'slug'              => 'dashboard',
                'color'             => NULL,
                'icon'              => 'fa fa-dashboard',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 1,
                'id_adm_privileges'  => 1,
                'sorting'           => 1
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Submaster',
            ],
            [
                'name'              => 'Submaster',
                'type'              => 'URL',
                'path'              => '#',
                'slug'              => NULL,
                'color'             => NULL,
                'icon'              => 'fa-solid fa-bars',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 1
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Brands',
            ],
            [
                'name'              => 'Brands',
                'type'              => 'Route',
                'path'              => 'Brands\BrandsControllerGetIndex',
                'slug'              => 'brands',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 1
            ]
        );
        
        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Brand Directions',
            ],
            [
                'name'              => 'Brand Directions',
                'type'              => 'Route',
                'path'              => 'BrandDirections\BrandDirectionsControllerGetIndex',
                'slug'              => 'brand_directions',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 2
            ]
        );
        
        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Brand Groups',
            ],
            [
                'name'              => 'Brand Groups',
                'type'              => 'Route',
                'path'              => 'BrandGroups\BrandGroupsControllerGetIndex',
                'slug'              => 'brand_groups',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 3
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Brand Marketings',
            ],
            [
                'name'              => 'Brand Marketings',
                'type'              => 'Route',
                'path'              => 'BrandMarketings\BrandMarketingsControllerGetIndex',
                'slug'              => 'brand_marketings',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 4
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Categories',
            ],
            [
                'name'              => 'Categories',
                'type'              => 'Route',
                'path'              => 'Categories\CategoriesControllerGetIndex',
                'slug'              => 'categories',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 5
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Classifications',
            ],
            [
                'name'              => 'Classifications',
                'type'              => 'Route',
                'path'              => 'Classifications\ClassificationsControllerGetIndex',
                'slug'              => 'classifications',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 6
            ]
        );

    }

}