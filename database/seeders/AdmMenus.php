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
                'sorting'           => 5
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
        
        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Colors',
            ],
            [
                'name'              => 'Colors',
                'type'              => 'Route',
                'path'              => 'Colors\ColorsControllerGetIndex',
                'slug'              => 'colors',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 7
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Gashapon Submaster',
            ],
            [
                'name'              => 'Gashapon Submaster',
                'type'              => 'URL',
                'path'              => '##',
                'slug'              =>  NULL,
                'color'             => NULL,
                'icon'              => 'fa-solid fa-bars',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 6
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Counters',
            ],
            [
                'name'              => 'Counters',
                'type'              => 'Route',
                'path'              => 'Counters\CountersControllerGetIndex',
                'slug'              => 'counters',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 8
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Currencies',
            ],
            [
                'name'              => 'Currencies',
                'type'              => 'Route',
                'path'              => 'Currencies\CurrenciesControllerGetIndex',
                'slug'              => 'currencies',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 9
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Gashapon Brands',
            ],
            [
                'name'              => 'Gashapon Brands',
                'type'              => 'Route',
                'path'              => 'GashaponBrands\GashaponBrandsControllerGetIndex',
                'slug'              => 'gashapon_brands',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 10,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 1
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Gashapon Categories',
            ],
            [
                'name'              => 'Gashapon Categories',
                'type'              => 'Route',
                'path'              => 'GashaponCategories\GashaponCategoriesControllerGetIndex',
                'slug'              => 'gashapon_categories',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 10,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 2
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Gashapon Countries',
            ],
            [
                'name'              => 'Gashapon Countries',
                'type'              => 'Route',
                'path'              => 'GashaponCountries\GashaponCountriesControllerGetIndex',
                'slug'              => 'gashapon_countries',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 10,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 3
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Gashapon Incoterms',
            ],
            [
                'name'              => 'Gashapon Incoterms',
                'type'              => 'Route',
                'path'              => 'GashaponIncoterms\GashaponIncotermsControllerGetIndex',
                'slug'              => 'gashapon_incoterms',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 10,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 4
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Gashapon Inventory Types',
            ],
            [
                'name'              => 'Gashapon Inventory Types',
                'type'              => 'Route',
                'path'              => 'GashaponInventoryTypes\GashaponInventoryTypesControllerGetIndex',
                'slug'              => 'gashapon_inventory_types',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 10,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 5
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Gashapon Models',
            ],
            [
                'name'              => 'Gashapon Models',
                'type'              => 'Route',
                'path'              => 'GashaponModels\GashaponModelsControllerGetIndex',
                'slug'              => 'gashapon_models',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 10,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 6
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Gashapon Item Master',
            ],
            [
                'name'              => 'Gashapon Item Master',
                'type'              => 'Route',
                'path'              => 'GashaponItemMasters\GashaponItemMastersControllerGetIndex',
                'slug'              => 'gashapon_item_masters',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 3
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Gashapon Product Types',
            ],
            [
                'name'              => 'Gashapon Product Types',
                'type'              => 'Route',
                'path'              => 'GashaponProductTypes\GashaponProductTypesControllerGetIndex',
                'slug'              => 'gashapon_product_types',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 10,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 7
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Gashapon SKU Statuses',
            ],
            [
                'name'              => 'Gashapon SKU Statuses',
                'type'              => 'Route',
                'path'              => 'GashaponSkuStatuses\GashaponSkuStatusesControllerGetIndex',
                'slug'              => 'gashapon_sku_statuses',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 10,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 8
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Gashapon UOMs',
            ],
            [
                'name'              => 'Gashapon UOMs',
                'type'              => 'Route',
                'path'              => 'GashaponUoms\GashaponUomsControllerGetIndex',
                'slug'              => 'gashapon_uoms',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 10,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 9
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Gashapon Vendor Groups',
            ],
            [
                'name'              => 'Gashapon Vendor Groups',
                'type'              => 'Route',
                'path'              => 'GashaponVendorGroups\GashaponVendorGroupsControllerGetIndex',
                'slug'              => 'gashapon_vendor_groups',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 10,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 10
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Gashapon Vendor Types',
            ],
            [
                'name'              => 'Gashapon Vendor Types',
                'type'              => 'Route',
                'path'              => 'GashaponVendorTypes\GashaponVendorTypesControllerGetIndex',
                'slug'              => 'gashapon_vendor_groups',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 10,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 11
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Gashapon Warehouse Categories',
            ],
            [
                'name'              => 'Gashapon Warehouse Categories',
                'type'              => 'Route',
                'path'              => 'GashaponWarehouseCategories\GashaponWarehouseCategoriesControllerGetIndex',
                'slug'              => 'gashapon_warehouse_categories',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 10,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 12
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Identifiers',
            ],
            [
                'name'              => 'Identifiers',
                'type'              => 'Route',
                'path'              => 'Identifiers\IdentifiersControllerGetIndex',
                'slug'              => 'identifiers',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 10
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Incoterms',
            ],
            [
                'name'              => 'Incoterms',
                'type'              => 'Route',
                'path'              => 'Incoterms\IncotermsControllerGetIndex',
                'slug'              => 'incoterms',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 11
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Inventory Types',
            ],
            [
                'name'              => 'Inventory Types',
                'type'              => 'Route',
                'path'              => 'InventoryTypes\InventoryTypesControllerGetIndex',
                'slug'              => 'inventory_types',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 12
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Item Master',
            ],
            [
                'name'              => 'Item Master',
                'type'              => 'Route',
                'path'              => 'ItemMasters\ItemMastersControllerGetIndex',
                'slug'              => 'item_masters',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 1
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Item Platforms',
            ],
            [
                'name'              => 'Item Platforms',
                'type'              => 'Route',
                'path'              => 'ItemPlatforms\ItemPlatformsControllerGetIndex',
                'slug'              => 'item_platforms',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 13
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Item Promo Types',
            ],
            [
                'name'              => 'Item Promo Types',
                'type'              => 'Route',
                'path'              => 'ItemPromoTypes\ItemPromoTypesControllerGetIndex',
                'slug'              => 'item_promo_types',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 14
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Item Segmentations',
            ],
            [
                'name'              => 'Item Segmentations',
                'type'              => 'Route',
                'path'              => 'ItemSegmentations\ItemSegmentationsControllerGetIndex',
                'slug'              => 'item_segmentations',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 15
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Item Serials',
            ],
            [
                'name'              => 'Item Serials',
                'type'              => 'Route',
                'path'              => 'ItemSerials\ItemSerialsControllerGetIndex',
                'slug'              => 'item_serials',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 16
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Margin Categories',
            ],
            [
                'name'              => 'Margin Categories',
                'type'              => 'Route',
                'path'              => 'MarginCategories\MarginCategoriesControllerGetIndex',
                'slug'              => 'margin_categories',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 17
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Model Specifics',
            ],
            [
                'name'              => 'Model Specifics',
                'type'              => 'Route',
                'path'              => 'ModelSpecifics\ModelSpecificsControllerGetIndex',
                'slug'              => 'model_specifics',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 18
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Platforms',
            ],
            [
                'name'              => 'Platforms',
                'type'              => 'Route',
                'path'              => 'Platforms\PlatformsControllerGetIndex',
                'slug'              => 'platforms',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 19
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Promo Types',
            ],
            [
                'name'              => 'Promo Types',
                'type'              => 'Route',
                'path'              => 'PromoTypes\PromoTypesControllerGetIndex',
                'slug'              => 'promo_types',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 20
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'RMA Submaster',
            ],
            [
                'name'              => 'RMA Submaster',
                'type'              => 'URL',
                'path'              => '###',
                'slug'              => NULL,
                'color'             => NULL,
                'icon'              => 'fa-solid fa-bars',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 7
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'RMA Categories',
            ],
            [
                'name'              => 'RMA Categories',
                'type'              => 'Route',
                'path'              => 'RmaCategories\RmaCategoriesControllerGetIndex',
                'slug'              => 'rma_categories',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 38,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 1
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'RMA Classifications',
            ],
            [
                'name'              => 'RMA Classifications',
                'type'              => 'Route',
                'path'              => 'RmaClassifications\RmaClassificationsControllerGetIndex',
                'slug'              => 'rma_classifications',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 38,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 2
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'RMA Margin Categories',
            ],
            [
                'name'              => 'RMA Margin Categories',
                'type'              => 'Route',
                'path'              => 'RmaMarginCategories\RmaMarginCategoriesControllerGetIndex',
                'slug'              => 'rma_margin_categories',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 38,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 3
            ]
        );


        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'RMA Model Specifics',
            ],
            [
                'name'              => 'RMA Model Specifics',
                'type'              => 'Route',
                'path'              => 'RmaModelSpecifics\RmaModelSpecificsControllerGetIndex',
                'slug'              => 'rma_model_specifics',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 38,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 4
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'RMA Store Categories',
            ],
            [
                'name'              => 'RMA Store Categories',
                'type'              => 'Route',
                'path'              => 'RmaStoreCategories\RmaStoreCategoriesControllerGetIndex',
                'slug'              => 'rma_store_categories',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 38,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 5
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'RMA Sub Classifications',
            ],
            [
                'name'              => 'RMA Sub Classifications',
                'type'              => 'Route',
                'path'              => 'RmaSubClassifications\RmaSubClassificationsControllerGetIndex',
                'slug'              => 'rma_sub_classifications',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 38,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 6
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'RMA UOMs',
            ],
            [
                'name'              => 'RMA UOMs',
                'type'              => 'Route',
                'path'              => 'RmaUoms\RmaUomsControllerGetIndex',
                'slug'              => 'rma_uoms',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 38,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 7
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Segmentations',
            ],
            [
                'name'              => 'Segmentations',
                'type'              => 'Route',
                'path'              => 'Segmentations\SegmentationsControllerGetIndex',
                'slug'              => 'segmentations',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 21
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Sizes',
            ],
            [
                'name'              => 'Sizes',
                'type'              => 'Route',
                'path'              => 'Sizes\SizesControllerGetIndex',
                'slug'              => 'sizes',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 22
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'SKU Classifications',
            ],
            [
                'name'              => 'SKU Classifications',
                'type'              => 'Route',
                'path'              => 'SkuClassifications\SkuClassificationsControllerGetIndex',
                'slug'              => 'sku_classifications',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 23
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'SKU Legends',
            ],
            [
                'name'              => 'SKU Legends',
                'type'              => 'Route',
                'path'              => 'SkuLegends\SkuLegendsControllerGetIndex',
                'slug'              => 'sku_legends',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 24
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'SKU Statuses',
            ],
            [
                'name'              => 'SKU Statuses',
                'type'              => 'Route',
                'path'              => 'SkuStatuses\SkuStatusesControllerGetIndex',
                'slug'              => 'sku_statuses',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 25
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Store Categories',
            ],
            [
                'name'              => 'Store Categories',
                'type'              => 'Route',
                'path'              => 'StoreCategories\StoreCategoriesControllerGetIndex',
                'slug'              => 'store_categories',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 26
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Sub Categories',
            ],
            [
                'name'              => 'Sub Categories',
                'type'              => 'Route',
                'path'              => 'SubCategories\SubCategoriesControllerGetIndex',
                'slug'              => 'sub_categories',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 27
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Sub Classifications',
            ],
            [
                'name'              => 'Sub Classifications',
                'type'              => 'Route',
                'path'              => 'SubClassifications\SubClassificationsControllerGetIndex',
                'slug'              => 'sub_classifications',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 28
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Support Types',
            ],
            [
                'name'              => 'Support Types',
                'type'              => 'Route',
                'path'              => 'SupportTypes\SupportTypesControllerGetIndex',
                'slug'              => 'support_types',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 29
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'UOMs',
            ],
            [
                'name'              => 'UOMs',
                'type'              => 'Route',
                'path'              => 'Uoms\UomsControllerGetIndex',
                'slug'              => 'uoms',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 30
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Vendors',
            ],
            [
                'name'              => 'Vendors',
                'type'              => 'Route',
                'path'              => 'Vendors\VendorsControllerGetIndex',
                'slug'              => 'vendors',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 31
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Vendor Groups',
            ],
            [
                'name'              => 'Vendor Groups',
                'type'              => 'Route',
                'path'              => 'VendorGroups\VendorGroupsControllerGetIndex',
                'slug'              => 'vendor_groups',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 32
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Vendor Types',
            ],
            [
                'name'              => 'Vendor Types',
                'type'              => 'Route',
                'path'              => 'VendorTypes\VendorTypesControllerGetIndex',
                'slug'              => 'vendor_types',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 33
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Warehouse Categories',
            ],
            [
                'name'              => 'Warehouse Categories',
                'type'              => 'Route',
                'path'              => 'WarehouseCategories\WarehouseCategoriesControllerGetIndex',
                'slug'              => 'warehouse_categories',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 34
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Warranties',
            ],
            [
                'name'              => 'Warranties',
                'type'              => 'Route',
                'path'              => 'Warranties\WarrantiesControllerGetIndex',
                'slug'              => 'warranties',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 35
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Apple LOBs',
            ],
            [
                'name'              => 'Apple LOBs',
                'type'              => 'Route',
                'path'              => 'AppleLobs\AppleLobsControllerGetIndex',
                'slug'              => 'apple_lobs',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 36
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Settings',
            ],
            [
                'name'              => 'Settings',
                'type'              => 'URL',
                'path'              => '####',
                'slug'              => NULL,
                'color'             => NULL,
                'icon'              => 'fa-solid fa-bars',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 8
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Action Types',
            ],
            [
                'name'              => 'Action Types',
                'type'              => 'Route',
                'path'              => 'ActionTypes\ActionTypesControllerGetIndex',
                'slug'              => 'action_types',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 62,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 1
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Table Setting',
            ],
            [
                'name'              => 'Table Setting',
                'type'              => 'Route',
                'path'              => 'TableSettings\TableSettingsControllerGetIndex',
                'slug'              => 'table_settings',
                'color'             => NULL,
                'icon'              => 'fa-solid fa-sliders',
                'parent_id'         => 62,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 3
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Module Headers',
            ],
            [
                'name'              => 'Module Headers',
                'type'              => 'Route',
                'path'              => 'ModuleHeaders\ModuleHeadersControllerGetIndex',
                'slug'              => 'module_headers',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 62,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 2
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Item Master Approval',
            ],
            [
                'name'              => 'Item Master Approval',
                'type'              => 'Route',
                'path'              => 'ItemMasterApprovals\ItemMasterApprovalsControllerGetIndex',
                'slug'              => 'item_master_approvals',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 2
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Gashapon Item Master Approval',
            ],
            [
                'name'              => 'Gashapon Item Master Approval',
                'type'              => 'Route',
                'path'              => 'GashaponItemMasterApprovals\GashaponItemMasterApprovalsControllerGetIndex',
                'slug'              => 'gashapon_item_master_approvals',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 4
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'History',
            ],
            [
                'name'              => 'History',
                'type'              => 'URL',
                'path'              => '######',
                'slug'              => NULL,
                'color'             => NULL,
                'icon'              => 'fa-solid fa-bars',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 9
            ]
        );

        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Item Master History',
            ],
            [
                'name'              => 'Item Master History',
                'type'              => 'Route',
                'path'              => 'ItemMasterHistories\ItemMasterHistoriesControllerGetIndex',
                'slug'              => 'item_master_histories',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 68,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 1
            ]
        );
        DB::table('adm_menuses')->updateOrInsert(
            [
                'name'              => 'Gashapon Item Master History',
            ],
            [
                'name'              => 'Gashapon Item Master History',
                'type'              => 'Route',
                'path'              => 'GashaponItemMasterHistories\GashaponItemMasterHistoriesControllerGetIndex',
                'slug'              => 'gashapon_item_master_histories',
                'color'             => NULL,
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 68,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges'  => 1,
                'sorting'           => 1
            ]
        );
    }

}