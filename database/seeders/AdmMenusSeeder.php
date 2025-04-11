<?php

namespace Database\Seeders;

use App\Models\AdmModels\AdmMenus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdmMenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $menus = [
            [
                'name'              => 'Dashboard',
                'type'              => 'Route',
                'path'              => 'Dashboard\DashboardControllerGetIndex',
                'slug'              => 'dashboard',
                'icon'              => 'fa-solid fa-chart-simple',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 1,
                'id_adm_privileges' => 1,
                'sorting'           => 1
            ],
            [
                'name'              => 'Items',
                'type'              => 'URL',
                'path'              => '#',
                'slug'              => null,
                'icon'              => 'fa-solid fa-bars',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 2
            ],
            [
                'name'              => 'Approvals',
                'type'              => 'URL',
                'path'              => '#',
                'slug'              => null,
                'icon'              => 'fa-solid fa-bars',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 3
            ],
            [
                'name'              => 'Submaster',
                'type'              => 'URL',
                'path'              => '#',
                'slug'              => null,
                'icon'              => 'fa-solid fa-bars',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 4
            ],
            [
                'name'              => 'Gashapon Submaster',
                'type'              => 'URL',
                'path'              => '#',
                'slug'              => null,
                'icon'              => 'fa-solid fa-bars',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 5
            ],
            [
                'name'              => 'RMA Submaster',
                'type'              => 'URL',
                'path'              => '#',
                'slug'              => null,
                'icon'              => 'fa-solid fa-bars',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 6
            ],
            [
                'name'              => 'Admin Submaster',
                'type'              => 'URL',
                'path'              => '#',
                'slug'              => null,
                'icon'              => 'fa-solid fa-bars',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 7
            ],
            [
                'name'              => 'Settings',
                'type'              => 'URL',
                'path'              => '#',
                'slug'              => null,
                'icon'              => 'fa-solid fa-bars',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 8
            ],
            [
                'name'              => 'History',
                'type'              => 'URL',
                'path'              => '#',
                'slug'              => null,
                'icon'              => 'fa-solid fa-bars',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 9
            ],

            // ITEMS CHILDREN

            [
                'name'              => 'Item Master',
                'type'              => 'Route',
                'path'              => 'ItemMasters\ItemMastersControllerGetIndex',
                'slug'              => 'item_masters',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 1
            ],
            [
                'name'              => 'Gashapon Item Master',
                'type'              => 'Route',
                'path'              => 'GashaponItemMasters\GashaponItemMastersControllerGetIndex',
                'slug'              => 'gashapon_item_masters',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 2
            ],
            [
                'name'              => 'RMA Item Master',
                'type'              => 'Route',
                'path'              => 'RmaItemMasters\RmaItemMastersControllerGetIndex',
                'slug'              => 'rma_item_masters',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 3
            ],
            [
                'name'              => 'Admin Item Master',
                'type'              => 'Route',
                'path'              => 'AdminItemMasters\AdminItemMastersControllerGetIndex',
                'slug'              => 'admin_item_masters',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 2,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 4
            ],
            
            // APPROVALS CHILDREN
            
            [
                'name'              => 'Item Master Approval',
                'type'              => 'Route',
                'path'              => 'ItemMasterApprovals\ItemMasterApprovalsControllerGetIndex',
                'slug'              => 'item_master_approvals',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 3,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 1
            ],
            [
                'name'              => 'Item Master Approval (Accounting)',
                'type'              => 'Route',
                'path'              => 'ItemMasterAccountingApprovals\ItemMasterAccountingApprovalsControllerGetIndex',
                'slug'              => 'item_master_accounting_approvals',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 3,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 2
            ],
            [
                'name'              => 'Gashapon Item Master Approval',
                'type'              => 'Route',
                'path'              => 'GashaponItemMasterApprovals\GashaponItemMasterApprovalsControllerGetIndex',
                'slug'              => 'gashapon_item_master_approvals',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 3,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 3
            ],
            [
                'name'              => 'RMA Item Master Approval',
                'type'              => 'Route',
                'path'              => 'RmaItemMasterApprovals\RmaItemMasterApprovalsControllerGetIndex',
                'slug'              => 'rma_item_master_approvals',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 3,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 4
            ],
            
            // SUBMASTER CHILDREN
            
            [
                'name'              => 'Apple LOBs',
                'type'              => 'Route',
                'path'              => 'AppleLobs\AppleLobsControllerGetIndex',
                'slug'              => 'apple_lobs',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 1
            ],
            [
                'name'              => 'Brands',
                'type'              => 'Route',
                'path'              => 'Brands\BrandsControllerGetIndex',
                'slug'              => 'brands',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 2
            ],
            [
                'name'              => 'Brand Directions',
                'type'              => 'Route',
                'path'              => 'BrandDirections\BrandDirectionsControllerGetIndex',
                'slug'              => 'brand_directions',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 3
            ],
            [
                'name'              => 'Brand Groups',
                'type'              => 'Route',
                'path'              => 'BrandGroups\BrandGroupsControllerGetIndex',
                'slug'              => 'brand_groups',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 4
            ],
            [
                'name'              => 'Brand Marketings',
                'type'              => 'Route',
                'path'              => 'BrandMarketings\BrandMarketingsControllerGetIndex',
                'slug'              => 'brand_marketings',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 5
            ],
            [
                'name'              => 'Categories',
                'type'              => 'Route',
                'path'              => 'Categories\CategoriesControllerGetIndex',
                'slug'              => 'categories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 6
            ],
            [
                'name'              => 'Classifications',
                'type'              => 'Route',
                'path'              => 'Classifications\ClassificationsControllerGetIndex',
                'slug'              => 'classifications',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 7
            ],
            [
                'name'              => 'Colors',
                'type'              => 'Route',
                'path'              => 'Colors\ColorsControllerGetIndex',
                'slug'              => 'colors',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 8
            ],
            [
                'name'              => 'Counters',
                'type'              => 'Route',
                'path'              => 'Counters\CountersControllerGetIndex',
                'slug'              => 'counters',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 9
            ],
            [
                'name'              => 'Currencies',
                'type'              => 'Route',
                'path'              => 'Currencies\CurrenciesControllerGetIndex',
                'slug'              => 'currencies',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 10
            ],
            [
                'name'              => 'Identifiers',
                'type'              => 'Route',
                'path'              => 'Identifiers\IdentifiersControllerGetIndex',
                'slug'              => 'identifiers',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 11
            ],
            [
                'name'              => 'Incoterms',
                'type'              => 'Route',
                'path'              => 'Incoterms\IncotermsControllerGetIndex',
                'slug'              => 'incoterms',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 12
            ],
            [
                'name'              => 'Inventory Types',
                'type'              => 'Route',
                'path'              => 'InventoryTypes\InventoryTypesControllerGetIndex',
                'slug'              => 'inventory_types',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 13
            ],
            [
                'name'              => 'Margin Categories',
                'type'              => 'Route',
                'path'              => 'MarginCategories\MarginCategoriesControllerGetIndex',
                'slug'              => 'margin_categories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 14
            ],
            [
                'name'              => 'Model Specifics',
                'type'              => 'Route',
                'path'              => 'ModelSpecifics\ModelSpecificsControllerGetIndex',
                'slug'              => 'model_specifics',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 15
            ],
            [
                'name'              => 'Platforms',
                'type'              => 'Route',
                'path'              => 'Platforms\PlatformsControllerGetIndex',
                'slug'              => 'platforms',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 16
            ],
            [
                'name'              => 'Promo Types',
                'type'              => 'Route',
                'path'              => 'PromoTypes\PromoTypesControllerGetIndex',
                'slug'              => 'promo_types',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 17
            ],
            [
                'name'              => 'Segmentations',
                'type'              => 'Route',
                'path'              => 'Segmentations\SegmentationsControllerGetIndex',
                'slug'              => 'segmentations',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 18
            ],
            [
                'name'              => 'Sizes',
                'type'              => 'Route',
                'path'              => 'Sizes\SizesControllerGetIndex',
                'slug'              => 'sizes',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 19
            ],
            [
                'name'              => 'SKU Classifications',
                'type'              => 'Route',
                'path'              => 'SkuClassifications\SkuClassificationsControllerGetIndex',
                'slug'              => 'sku_classifications',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 20
            ],
            [
                'name'              => 'SKU Legends',
                'type'              => 'Route',
                'path'              => 'SkuLegends\SkuLegendsControllerGetIndex',
                'slug'              => 'sku_legends',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 21
            ],
            [
                'name'              => 'SKU Statuses',
                'type'              => 'Route',
                'path'              => 'SkuStatuses\SkuStatusesControllerGetIndex',
                'slug'              => 'sku_statuses',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 22
            ],
            [
                'name'              => 'Store Categories',
                'type'              => 'Route',
                'path'              => 'StoreCategories\StoreCategoriesControllerGetIndex',
                'slug'              => 'store_categories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 23
            ],
            [
                'name'              => 'Sub Categories',
                'type'              => 'Route',
                'path'              => 'SubCategories\SubCategoriesControllerGetIndex',
                'slug'              => 'sub_categories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 24
            ],
            [
                'name'              => 'Sub Classifications',
                'type'              => 'Route',
                'path'              => 'SubClassifications\SubClassificationsControllerGetIndex',
                'slug'              => 'sub_classifications',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 25
            ],
            [
                'name'              => 'Support Types',
                'type'              => 'Route',
                'path'              => 'SupportTypes\SupportTypesControllerGetIndex',
                'slug'              => 'support_types',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 26
            ],
            [
                'name'              => 'UOMs',
                'type'              => 'Route',
                'path'              => 'Uoms\UomsControllerGetIndex',
                'slug'              => 'uoms',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 27
            ],
            [
                'name'              => 'Vendors',
                'type'              => 'Route',
                'path'              => 'Vendors\VendorsControllerGetIndex',
                'slug'              => 'vendors',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 28
            ],
            [
                'name'              => 'Vendor Groups',
                'type'              => 'Route',
                'path'              => 'VendorGroups\VendorGroupsControllerGetIndex',
                'slug'              => 'vendor_groups',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 29
            ],
            [
                'name'              => 'Vendor Types',
                'type'              => 'Route',
                'path'              => 'VendorTypes\VendorTypesControllerGetIndex',
                'slug'              => 'vendor_types',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 30
            ],
            [
                'name'              => 'Warehouse Categories',
                'type'              => 'Route',
                'path'              => 'WarehouseCategories\WarehouseCategoriesControllerGetIndex',
                'slug'              => 'warehouse_categories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 31
            ],
            [
                'name'              => 'Warranties',
                'type'              => 'Route',
                'path'              => 'Warranties\WarrantiesControllerGetIndex',
                'slug'              => 'warranties',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 32
            ],
            [
                'name'              => 'Margin Matrix',
                'type'              => 'Route',
                'path'              => 'MarginMatrices\MarginMatricesControllerGetIndex',
                'slug'              => 'margin_matrices',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 33
            ],
            [
                'name'              => 'Margin Matrix (ECOMM)',
                'type'              => 'Route',
                'path'              => 'EcommMarginMatrices\EcommMarginMatricesControllerGetIndex',
                'slug'              => 'ecomm_margin_matrices',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 4,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 34
            ],

            // GASHAPON SUBMASTER CHILDREN

            [
                'name'              => 'Gashapon Brands',
                'type'              => 'Route',
                'path'              => 'GashaponBrands\GashaponBrandsControllerGetIndex',
                'slug'              => 'gashapon_brands',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 1
            ],
            [
                'name'              => 'Gashapon Categories',
                'type'              => 'Route',
                'path'              => 'GashaponCategories\GashaponCategoriesControllerGetIndex',
                'slug'              => 'gashapon_categories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 2
            ],
            [
                'name'              => 'Gashapon Countries',
                'type'              => 'Route',
                'path'              => 'GashaponCountries\GashaponCountriesControllerGetIndex',
                'slug'              => 'gashapon_countries',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 3
            ],
            [
                'name'              => 'Gashapon Incoterms',
                'type'              => 'Route',
                'path'              => 'GashaponIncoterms\GashaponIncotermsControllerGetIndex',
                'slug'              => 'gashapon_incoterms',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 4
            ],
            [
                'name'              => 'Gashapon Inventory Types',
                'type'              => 'Route',
                'path'              => 'GashaponInventoryTypes\GashaponInventoryTypesControllerGetIndex',
                'slug'              => 'gashapon_inventory_types',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 5
            ],
            [
                'name'              => 'Gashapon Models',
                'type'              => 'Route',
                'path'              => 'GashaponModels\GashaponModelsControllerGetIndex',
                'slug'              => 'gashapon_models',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 6
            ],
            [
                'name'              => 'Gashapon Product Types',
                'type'              => 'Route',
                'path'              => 'GashaponProductTypes\GashaponProductTypesControllerGetIndex',
                'slug'              => 'gashapon_product_types',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 7
            ],
            [
                'name'              => 'Gashapon SKU Statuses',
                'type'              => 'Route',
                'path'              => 'GashaponSkuStatuses\GashaponSkuStatusesControllerGetIndex',
                'slug'              => 'gashapon_sku_statuses',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 8
            ],
            [
                'name'              => 'Gashapon UOMs',
                'type'              => 'Route',
                'path'              => 'GashaponUoms\GashaponUomsControllerGetIndex',
                'slug'              => 'gashapon_uoms',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 9
            ],
            [
                'name'              => 'Gashapon Vendor Groups',
                'type'              => 'Route',
                'path'              => 'GashaponVendorGroups\GashaponVendorGroupsControllerGetIndex',
                'slug'              => 'gashapon_vendor_groups',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 10
            ],
            [
                'name'              => 'Gashapon Vendor Types',
                'type'              => 'Route',
                'path'              => 'GashaponVendorTypes\GashaponVendorTypesControllerGetIndex',
                'slug'              => 'gashapon_vendor_types',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 11
            ],
            [
                'name'              => 'Gashapon Warehouse Categories',
                'type'              => 'Route',
                'path'              => 'GashaponWarehouseCategories\GashaponWarehouseCategoriesControllerGetIndex',
                'slug'              => 'gashapon_warehouse_categories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 5,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 12
            ],

            // RMA SUBMASTER CHILDREN

            [
                'name'              => 'RMA Categories',
                'type'              => 'Route',
                'path'              => 'RmaCategories\RmaCategoriesControllerGetIndex',
                'slug'              => 'rma_categories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 6,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 1
            ],
            [
                'name'              => 'RMA Classifications',
                'type'              => 'Route',
                'path'              => 'RmaClassifications\RmaClassificationsControllerGetIndex',
                'slug'              => 'rma_classifications',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 6,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 2
            ],
            [
                'name'              => 'RMA Margin Categories',
                'type'              => 'Route',
                'path'              => 'RmaMarginCategories\RmaMarginCategoriesControllerGetIndex',
                'slug'              => 'rma_margin_categories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 6,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 3
            ],
            [
                'name'              => 'RMA Model Specifics',
                'type'              => 'Route',
                'path'              => 'RmaModelSpecifics\RmaModelSpecificsControllerGetIndex',
                'slug'              => 'rma_model_specifics',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 6,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 4
            ],
            [
                'name'              => 'RMA Store Categories',
                'type'              => 'Route',
                'path'              => 'RmaStoreCategories\RmaStoreCategoriesControllerGetIndex',
                'slug'              => 'rma_store_categories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 6,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 5
            ],
            [
                'name'              => 'RMA Sub Classifications',
                'type'              => 'Route',
                'path'              => 'RmaSubClassifications\RmaSubClassificationsControllerGetIndex',
                'slug'              => 'rma_sub_classifications',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 6,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 6
            ],
            [
                'name'              => 'RMA UOMs',
                'type'              => 'Route',
                'path'              => 'RmaUoms\RmaUomsControllerGetIndex',
                'slug'              => 'rma_uoms',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 6,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 7
            ],


            // ADMIN SUBMASTER CHILDREN

            [
                'name'              => 'Admin Brands',
                'type'              => 'Route',
                'path'              => 'AdminBrands\AdminBrandsControllerGetIndex',
                'slug'              => 'admin_brands',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 1
            ],
            [
                'name'              => 'Admin Brand Types',
                'type'              => 'Route',
                'path'              => 'AdminBrandTypes\AdminBrandTypesControllerGetIndex',
                'slug'              => 'admin_brand_types',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 2
            ],
            [
                'name'              => 'Admin Categories',
                'type'              => 'Route',
                'path'              => 'AdminCategories\AdminCategoriesControllerGetIndex',
                'slug'              => 'admin_categories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 3
            ],
            [
                'name'              => 'Admin Classifications',
                'type'              => 'Route',
                'path'              => 'AdminClassifications\AdminClassificationsControllerGetIndex',
                'slug'              => 'admin_classifications',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 4
            ],
            [
                'name'              => 'Admin Colors',
                'type'              => 'Route',
                'path'              => 'AdminColors\AdminColorsControllerGetIndex',
                'slug'              => 'admin_colors',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 5
            ],
            [
                'name'              => 'Admin Currencies',
                'type'              => 'Route',
                'path'              => 'AdminCurrencies\AdminCurrenciesControllerGetIndex',
                'slug'              => 'admin_currencies',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 6
            ],
            [
                'name'              => 'Admin Incoterms',
                'type'              => 'Route',
                'path'              => 'AdminIncoterms\AdminIncotermsControllerGetIndex',
                'slug'              => 'admin_incoterms',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 7
            ],
            [
                'name'              => 'Admin Inventories',
                'type'              => 'Route',
                'path'              => 'AdminInventories\AdminInventoriesControllerGetIndex',
                'slug'              => 'admin_inventories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 8
            ],
            [
                'name'              => 'Admin Inventories',
                'type'              => 'Route',
                'path'              => 'AdminInventories\AdminInventoriesControllerGetIndex',
                'slug'              => 'admin_inventories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 8
            ],
            [
                'name'              => 'Admin Margin Categories',
                'type'              => 'Route',
                'path'              => 'AdminMarginCategories\AdminMarginCategoriesControllerGetIndex',
                'slug'              => 'admin_margin_categories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 9
            ],
            [
                'name'              => 'Admin Model Specifics',
                'type'              => 'Route',
                'path'              => 'AdminModelSpecifics\AdminModelSpecificsControllerGetIndex',
                'slug'              => 'admin_model_specifics',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 10
            ],
            [
                'name'              => 'Admin Sizes',
                'type'              => 'Route',
                'path'              => 'AdminSizes\AdminSizesControllerGetIndex',
                'slug'              => 'admin_sizes',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 11
            ],
            [
                'name'              => 'Admin SKU Legends',
                'type'              => 'Route',
                'path'              => 'AdminSkuLegends\AdminSkuLegendsControllerGetIndex',
                'slug'              => 'admin_sku_legends',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 12
            ],
            [
                'name'              => 'Admin SKU Statuses',
                'type'              => 'Route',
                'path'              => 'AdminSkuStatuses\AdminSkuStatusesControllerGetIndex',
                'slug'              => 'admin_sku_statuses',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 13
            ],
            [
                'name'              => 'Admin Store Categories',
                'type'              => 'Route',
                'path'              => 'AdminStoreCategories\AdminStoreCategoriesControllerGetIndex',
                'slug'              => 'admin_store_categories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 14
            ],
            [
                'name'              => 'Admin Sub Categories',
                'type'              => 'Route',
                'path'              => 'AdminSubCategories\AdminSubCategoriesControllerGetIndex',
                'slug'              => 'admin_sub_categories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 15
            ],
            [
                'name'              => 'Admin Sub Classifications',
                'type'              => 'Route',
                'path'              => 'AdminSubClassifications\AdminSubClassificationsControllerGetIndex',
                'slug'              => 'admin_sub_classifications',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 16
            ],
            [
                'name'              => 'Admin Suppliers',
                'type'              => 'Route',
                'path'              => 'AdminSuppliers\AdminSuppliersControllerGetIndex',
                'slug'              => 'admin_suppliers',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 17
            ],
            [
                'name'              => 'Admin UOMs',
                'type'              => 'Route',
                'path'              => 'AdminUoms\AdminUomsControllerGetIndex',
                'slug'              => 'admin_uoms',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 18
            ],
            [
                'name'              => 'Admin Vendors',
                'type'              => 'Route',
                'path'              => 'AdminVendors\AdminVendorsControllerGetIndex',
                'slug'              => 'admin_vendors',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 19
            ],
            [
                'name'              => 'Admin Vendor Types',
                'type'              => 'Route',
                'path'              => 'AdminVendorTypes\AdminVendorTypesControllerGetIndex',
                'slug'              => 'admin_vendor_types',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 20
            ],
            [
                'name'              => 'Admin Warehouse Categories',
                'type'              => 'Route',
                'path'              => 'AdminWarehouseCategories\AdminWarehouseCategoriesControllerGetIndex',
                'slug'              => 'admin_warehouse_categories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 21
            ],
            [
                'name'              => 'Admin Warranties',
                'type'              => 'Route',
                'path'              => 'AdminWarranties\AdminWarrantiesControllerGetIndex',
                'slug'              => 'admin_warranties',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 7,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 22
            ],



            // SETTINGS CHILDREN

            [
                'name'              => 'Action Types',
                'type'              => 'Route',
                'path'              => 'ActionTypes\ActionTypesControllerGetIndex',
                'slug'              => 'action_types',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 8,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 1
            ],
            [
                'name'              => 'Table Setting',
                'type'              => 'Route',
                'path'              => 'TableSettings\TableSettingsControllerGetIndex',
                'slug'              => 'table_settings',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 8,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 2
            ],
            [
                'name'              => 'Module Headers',
                'type'              => 'Route',
                'path'              => 'ModuleHeaders\ModuleHeadersControllerGetIndex',
                'slug'              => 'module_headers',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 8,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 3
            ],

            // HISTORY CHILDREN

            [
                'name'              => 'Item Master History',
                'type'              => 'Route',
                'path'              => 'ItemMasterHistories\ItemMasterHistoriesControllerGetIndex',
                'slug'              => 'item_master_histories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 9,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 1
            ],
            [
                'name'              => 'Gashapon Item Master History',
                'type'              => 'Route',
                'path'              => 'GashaponItemMasterHistories\GashaponItemMasterHistoriesControllerGetIndex',
                'slug'              => 'gashapon_item_master_histories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 9,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 2
            ],
            [
                'name'              => 'RMA Item Master History',
                'type'              => 'Route',
                'path'              => 'RmaItemMasterHistories\RmaItemMasterHistoriesControllerGetIndex',
                'slug'              => 'rma_item_master_histories',
                'icon'              => 'fa-regular fa-circle',
                'parent_id'         => 9,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_adm_privileges' => 1,
                'sorting'           => 3
            ],
        ];

        foreach ($menus as $menu) {
            AdmMenus::updateOrCreate(
                ['name' => $menu['name']],
                $menu
            );
        }


    }

   

}