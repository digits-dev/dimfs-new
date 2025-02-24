<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmModules extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('cms_moduls')->where('id', '>=', 12)->delete();
        // DB::statement('ALTER TABLE cms_moduls AUTO_INCREMENT = 12');
        $data = [
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Notifications',
                'icon' => 'fa fa-cog',
                'path' => 'notifications',
                'table_name' => 'adm_notifications',
                'controller' => 'NotificationsController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Privileges',
                'icon' => 'fa fa-crown',
                'path' => 'privileges',
                'table_name' => 'adm_privileges',
                'controller' => 'PrivilegesController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Users Management',
                'icon' => 'fa fa-users',
                'path' => 'users',
                'table_name' => 'adm_users',
                'controller' => 'AdminUsersController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Settings',
                'icon' => 'fa fa-cog',
                'path' => 'settings',
                'table_name' => 'adm_settings',
                'controller' => 'SettingsController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Module Generator',
                'icon' => 'fa fa-th',
                'path' => 'module_generator',
                'table_name' => 'adm_moduls',
                'controller' => 'ModulsController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Menu Management',
                'icon' => 'fa fa-bars',
                'path' => 'menu_management',
                'table_name' => 'adm_menus',
                'controller' => 'MenusController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Email Templates',
                'icon' => 'fa fa-envelope-o',
                'path' => 'email_templates',
                'table_name' => 'adm_email_templates',
                'controller' => 'EmailTemplatesController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Statistic Builder',
                'icon' => 'fa fa-dashboard',
                'path' => 'statistic_builder',
                'table_name' => 'adm_statistics',
                'controller' => 'StatisticBuilderController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'API Generator',
                'icon' => 'fa fa-cloud-download',
                'path' => 'api_generator',
                'table_name' => '',
                'controller' => 'ApiCustomController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Announcements',
                'icon' => 'fa fa-info-circle',
                'path' => 'announcements',
                'table_name' => 'announcements',
                'controller' => 'AnnouncementsController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Log User Access',
                'icon' => 'fa fa-history',
                'path' => 'logs',
                'table_name' => 'adm_logs',
                'controller' => 'LogsController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Dashboard',
                'icon' => 'images/navigation/dashboard-icon.png',
                'path' => 'dashboard',
                'table_name' => 'dashboard',
                'controller' => 'Dashboard\DashboardController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            // FOR NEW MODULES 

            [
                'name' => 'Brands',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'brands',
                'table_name' => 'brands',
                'controller' => 'Brands\BrandsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Brand Directions',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'brand_directions',
                'table_name' => 'brand_directions',
                'controller' => 'BrandDirections\BrandDirectionsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Brand Groups',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'brand_groups',
                'table_name' => 'brand_groups',
                'controller' => 'BrandGroups\BrandGroupsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Brand Marketings',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'brand_marketings',
                'table_name' => 'brand_marketings',
                'controller' => 'BrandMarketings\BrandMarketingsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Categories',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'categories',
                'table_name' => 'categories',
                'controller' => 'Categories\CategoriesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Classifications',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'classifications',
                'table_name' => 'classifications',
                'controller' => 'Classifications\ClassificationsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Colors',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'colors',
                'table_name' => 'colors',
                'controller' => 'Colors\ColorsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Counters',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'counters',
                'table_name' => 'counters',
                'controller' => 'Counters\CountersController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Currencies',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'currencies',
                'table_name' => 'currencies',
                'controller' => 'Currencies\CurrenciesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Gashapon Brands',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'gashapon_brands',
                'table_name' => 'gashapon_brands',
                'controller' => 'GashaponBrands\GashaponBrandsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Gashapon Categories',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'gashapon_categories',
                'table_name' => 'gashapon_categories',
                'controller' => 'GashaponCategories\GashaponCategoriesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Gashapon Countries',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'gashapon_countries',
                'table_name' => 'gashapon_countries',
                'controller' => 'GashaponCountries\GashaponCountriesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Gashapon Incoterms',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'gashapon_incoterms',
                'table_name' => 'gashapon_incoterms',
                'controller' => 'GashaponIncoterms\GashaponIncotermsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Gashapon Inventory Types',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'gashapon_inventory_types',
                'table_name' => 'gashapon_inventory_types',
                'controller' => 'GashaponInventoryTypes\GashaponInventoryTypesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Gashapon Models',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'gashapon_models',
                'table_name' => 'gashapon_models',
                'controller' => 'GashaponModels\GashaponModelsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Gashapon Item Masters',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'gashapon_item_masters',
                'table_name' => 'gashapon_item_masters',
                'controller' => 'GashaponItemMasters\GashaponItemMastersController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Gashapon Product Types',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'gashapon_product_types',
                'table_name' => 'gashapon_product_types',
                'controller' => 'GashaponProductTypes\GashaponProductTypesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Gashapon SKU Statuses',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'gashapon_sku_statuses',
                'table_name' => 'gashapon_sku_statuses',
                'controller' => 'GashaponSkuStatuses\GashaponSkuStatusesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Gashapon UOMs',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'gashapon_uoms',
                'table_name' => 'gashapon_uoms',
                'controller' => 'GashaponUoms\GashaponUomsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Gashapon Vendor Groups',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'gashapon_vendor_groups',
                'table_name' => 'gashapon_vendor_groups',
                'controller' => 'GashaponVendorGroups\GashaponVendorGroupsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Gashapon Vendor Types',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'gashapon_vendor_types',
                'table_name' => 'gashapon_vendor_types',
                'controller' => 'GashaponVendorTypes\GashaponVendorTypesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Gashapon Warehouse Categories',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'gashapon_warehouse_categories',
                'table_name' => 'gashapon_warehouse_categories',
                'controller' => 'GashaponWarehouseCategories\GashaponWarehouseCategoriesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Identifiers',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'identifiers',
                'table_name' => 'identifiers',
                'controller' => 'Identifiers\IdentifiersController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Incoterms',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'incoterms',
                'table_name' => 'incoterms',
                'controller' => 'Incoterms\IncotermsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Inventory Types',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'inventory_types',
                'table_name' => 'inventory_types',
                'controller' => 'InventoryTypes\InventoryTypesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Item Master',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'item_masters',
                'table_name' => 'item_masters',
                'controller' => 'ItemMasters\ItemMastersController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Item Platforms',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'item_platforms',
                'table_name' => 'item_platforms',
                'controller' => 'ItemPlatforms\ItemPlatformsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Item Promo Types',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'item_promo_types',
                'table_name' => 'item_promo_types',
                'controller' => 'ItemPromoTypes\ItemPromoTypesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Item Segmentations',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'item_segmentations',
                'table_name' => 'item_segmentations',
                'controller' => 'ItemSegmentations\ItemSegmentationsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Item Serials',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'item_serials',
                'table_name' => 'item_serials',
                'controller' => 'ItemSerials\ItemSerialsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Margin Categories',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'margin_categories',
                'table_name' => 'margin_categories',
                'controller' => 'MarginCategories\MarginCategoriesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Model Specifics',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'model_specifics',
                'table_name' => 'model_specifics',
                'controller' => 'ModelSpecifics\ModelSpecificsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Platforms',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'platforms',
                'table_name' => 'platforms',
                'controller' => 'Platforms\PlatformsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Promo Types',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'promo_types',
                'table_name' => 'promo_types',
                'controller' => 'PromoTypes\PromoTypesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'RMA Categories',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'rma_categories',
                'table_name' => 'rma_categories',
                'controller' => 'RmaCategories\RmaCategoriesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'RMA Classifications',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'rma_classifications',
                'table_name' => 'rma_classifications',
                'controller' => 'RmaClassifications\RmaClassificationsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'RMA Margin Categories',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'rma_margin_categories',
                'table_name' => 'rma_margin_categories',
                'controller' => 'RmaMarginCategories\RmaMarginCategoriesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'RMA Model Specifics',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'rma_model_specifics',
                'table_name' => 'rma_model_specifics',
                'controller' => 'RmaModelSpecifics\RmaModelSpecificsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'RMA Store Categories',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'rma_store_categories',
                'table_name' => 'rma_store_categories',
                'controller' => 'RmaStoreCategories\RmaStoreCategoriesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'RMA Sub Classifications',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'rma_sub_classifications',
                'table_name' => 'rma_sub_classifications',
                'controller' => 'RmaSubClassifications\RmaSubClassificationsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'RMA UOMs',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'rma_uoms',
                'table_name' => 'rma_uoms',
                'controller' => 'RmaUoms\RmaUomsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Segmentations',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'segmentations',
                'table_name' => 'segmentations',
                'controller' => 'Segmentations\SegmentationsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Sizes',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'sizes',
                'table_name' => 'sizes',
                'controller' => 'Sizes\SizesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'SKU Classifications',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'sku_classifications',
                'table_name' => 'sku_classifications',
                'controller' => 'SkuClassifications\SkuClassificationsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'SKU Legends',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'sku_legends',
                'table_name' => 'sku_legends',
                'controller' => 'SkuLegends\SkuLegendsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'SKU Statuses',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'sku_statuses',
                'table_name' => 'sku_statuses',
                'controller' => 'SkuStatuses\SkuStatusesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Store Categories',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'store_categories',
                'table_name' => 'store_categories',
                'controller' => 'StoreCategories\StoreCategoriesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Sub Categories',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'sub_categories',
                'table_name' => 'sub_categories',
                'controller' => 'SubCategories\SubCategoriesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Sub Classifications',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'sub_classifications',
                'table_name' => 'sub_classifications',
                'controller' => 'SubClassifications\SubClassificationsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Support Types',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'support_types',
                'table_name' => 'support_types',
                'controller' => 'SupportTypes\SupportTypesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'UOMs',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'uoms',
                'table_name' => 'uoms',
                'controller' => 'Uoms\UomsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Vendors',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'vendors',
                'table_name' => 'vendors',
                'controller' => 'Vendors\VendorsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],
            
            [
                'name' => 'Vendor Groups',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'vendor_groups',
                'table_name' => 'vendor_groups',
                'controller' => 'VendorGroups\VendorGroupsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Vendor Types',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'vendor_types',
                'table_name' => 'vendor_types',
                'controller' => 'VendorTypes\VendorTypesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Warehouse Categories',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'warehouse_categories',
                'table_name' => 'warehouse_categories',
                'controller' => 'WarehouseCategories\WarehouseCategoriesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Warranties',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'warranties',
                'table_name' => 'warranties',
                'controller' => 'Warranties\WarrantiesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Apple LOBs',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'apple_lobs',
                'table_name' => 'apple_lobs',
                'controller' => 'AppleLobs\AppleLobsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'System Error Logs',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa fa-history',
                'path' => 'system_error_logs',
                'table_name' => 'log_system_errors',
                'controller' => 'SystemErrorLogsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Action Types',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'action_types',
                'table_name' => 'action_types',
                'controller' => 'ActionTypes\ActionTypesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Table Setting',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-solid fa-sliders',
                'path' => 'table_settings',
                'table_name' => 'table_settings',
                'controller' => 'TableSettings\TableSettingsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Module Headers',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'module_headers',
                'table_name' => 'module_headers',
                'controller' => 'ModuleHeaders\ModuleHeadersController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Item Master Approval',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'item_master_approvals',
                'table_name' => 'item_master_approvals',
                'controller' => 'ItemMasterApprovals\ItemMasterApprovalsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Gashapon Item Master Approval',
                'created_at' => date('Y-m-d H:i:s'),
                'icon' => 'fa-regular fa-circle',
                'path' => 'gashapon_item_master_approvals',
                'table_name' => 'gashapon_item_master_approvals',
                'controller' => 'GashaponItemMasterApprovals\GashaponItemMasterApprovalsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],

            [
                'name' => 'Item Master History',
                'icon' => 'fa-regular fa-circle',
                'path' => 'item_master_histories',
                'table_name' => 'item_master_histories',
                'controller' => 'ItemMasterHistories\ItemMasterHistoriesController',
                'is_protected' => 0,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],

            [
                'name' => 'Gashapon Item Master Approval',
                'icon' => 'fa-regular fa-circle',
                'path' => 'gashapon_item_master_approvals',
                'table_name' => 'gashapon_item_master_approvals',
                'controller' => 'GashaponItemMasterApprovals\GashaponItemMasterApprovalsController',
                'is_protected' => 0,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($data as $module) {
            DB::table('adm_modules')->updateOrInsert(['name' => $module['name']], $module);
        }

    }
}