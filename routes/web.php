<?php

use App\Helpers\CommonHelpers;
use App\Http\Controllers\ActionTypes\ActionTypesController;
use App\Http\Controllers\Admin\AdminApiController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\MenusController;
use App\Http\Controllers\Admin\ModulsController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\PrivilegesController;
use App\Http\Controllers\Admin\AnnouncementsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Controllers\Admin\AdmRequestController;
use App\Http\Controllers\Admin\LogsController;
use App\Http\Controllers\Admin\SystemErrorLogsController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\AppleLobs\AppleLobsController;
use App\Http\Controllers\BrandDirections\BrandDirectionsController;
use App\Http\Controllers\BrandGroups\BrandGroupsController;
use App\Http\Controllers\BrandMarketings\BrandMarketingsController;
use App\Http\Controllers\Brands\BrandsController;
use App\Http\Controllers\Categories\CategoriesController;
use App\Http\Controllers\Classifications\ClassificationsController;
use App\Http\Controllers\Colors\ColorsController;
use App\Http\Controllers\Counters\CountersController;
use App\Http\Controllers\Currencies\CurrenciesController;
use App\Http\Controllers\Identifiers\IdentifiersController;
use App\Http\Controllers\InventoryTypes\InventoryTypesController;
use App\Http\Controllers\Incoterms\IncotermsController;
use App\Http\Controllers\ItemPlatforms\ItemPlatformsController;
use App\Http\Controllers\ItemPromoTypes\ItemPromoTypesController;
use App\Http\Controllers\ItemSegmentations\ItemSegmentationsController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\GashaponBrands\GashaponBrandsController;
use App\Http\Controllers\GashaponCategories\GashaponCategoriesController;
use App\Http\Controllers\GashaponCountries\GashaponCountriesController;
use App\Http\Controllers\GashaponIncoterms\GashaponIncotermsController;
use App\Http\Controllers\GashaponInventoryTypes\GashaponInventoryTypesController;
use App\Http\Controllers\GashaponItemMasters\GashaponItemMastersController;
use App\Http\Controllers\GashaponModels\GashaponModelsController;
use App\Http\Controllers\GashaponProductTypes\GashaponProductTypesController;
use App\Http\Controllers\GashaponSkuStatuses\GashaponSkuStatusesController;
use App\Http\Controllers\GashaponUoms\GashaponUomsController;
use App\Http\Controllers\GashaponVendorGroups\GashaponVendorGroupsController;
use App\Http\Controllers\GashaponVendorTypes\GashaponVendorTypesController;
use App\Http\Controllers\GashaponWarehouseCategories\GashaponWarehouseCategoriesController;
use App\Http\Controllers\ItemMasters\ItemMastersController;
use App\Http\Controllers\ItemMasterApprovals\ItemMasterApprovalsController;
use App\Http\Controllers\ItemMasterHistories\ItemMasterHistoriesController;
use App\Http\Controllers\GashaponItemMasterApprovals\GashaponItemMasterApprovalsController;
use App\Http\Controllers\GashaponItemMasterHistories\GashaponItemMasterHistoriesController;
use App\Http\Controllers\ItemMasterModuleImports\ItemMasterModuleImportsController;
use App\Http\Controllers\RmaItemMasters\RmaItemMastersController;
use App\Http\Controllers\RmaItemMasterApprovals\RmaItemMasterApprovalsController;
use App\Http\Controllers\RmaItemMasterHistories\RmaItemMasterHistoriesController;
use App\Http\Controllers\ItemSerials\ItemSerialsController;
use App\Http\Controllers\MarginCategories\MarginCategoriesController;
use App\Http\Controllers\ModelSpecifics\ModelSpecificsController;
use App\Http\Controllers\ModuleHeaders\ModuleHeadersController;
use App\Http\Controllers\Platforms\PlatformsController;
use App\Http\Controllers\PromoTypes\PromoTypesController;
use App\Http\Controllers\RmaCategories\RmaCategoriesController;
use App\Http\Controllers\RmaClassifications\RmaClassificationsController;
use App\Http\Controllers\RmaMarginCategories\RmaMarginCategoriesController;
use App\Http\Controllers\RmaModelSpecifics\RmaModelSpecificsController;
use App\Http\Controllers\RmaStoreCategories\RmaStoreCategoriesController;
use App\Http\Controllers\RmaSubClassifications\RmaSubClassificationsController;
use App\Http\Controllers\RmaUoms\RmaUomsController;
use App\Http\Controllers\Segmentations\SegmentationsController;
use App\Http\Controllers\Sizes\SizesController;
use App\Http\Controllers\SkuClassifications\SkuClassificationsController;
use App\Http\Controllers\SkuLegends\SkuLegendsController;
use App\Http\Controllers\SkuStatuses\SkuStatusesController;
use App\Http\Controllers\StoreCategories\StoreCategoriesController;
use App\Http\Controllers\SubCategories\SubCategoriesController;
use App\Http\Controllers\SubClassifications\SubClassificationsController;
use App\Http\Controllers\SupportTypes\SupportTypesController;
use App\Http\Controllers\TableSettings\TableSettingsController;
use App\Http\Controllers\Uoms\UomsController;
use App\Http\Controllers\Users\ChangePasswordController;
use App\Http\Controllers\Users\ProfilePageController;
use App\Http\Controllers\Users\ForceChangePasswordController;
use App\Http\Controllers\VendorGroups\VendorGroupsController;
use App\Http\Controllers\Vendors\VendorsController;
use App\Http\Controllers\VendorTypes\VendorTypesController;
use App\Http\Controllers\WarehouseCategories\WarehouseCategoriesController;
use App\Http\Controllers\Warranties\WarrantiesController;
use App\Models\GashaponItemMaster;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class, 'index']);
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::get('/reset_password', [ResetPasswordController::class, 'getIndex'])->name('reset_password');
Route::post('/send_resetpass_email', [ResetPasswordController::class, 'sendResetPasswordInstructions']);
Route::get('/reset_password_email/{email}', [ResetPasswordController::class, 'getResetIndex'])->name('reset_password_email');
Route::post('/send_resetpass_email/reset', [ResetPasswordController::class, 'resetPassword']);
Route::post('post_login', [LoginController::class, 'authenticate'])->name('post_login');
Route::get('/appname', [SettingsController::class, 'getAppname'])->name('app-name');
Route::get('/applogo', [SettingsController::class, 'getApplogo'])->name('app-logo');
Route::get('/login-details', [SettingsController::class, 'getLoginDetails'])->name('app-login-details');

Route::group(['middleware' => ['auth', 'web']], function () {
    Route::post('/check-password', [ForceChangePasswordController::class, 'checkPassword'])->name('check-current-password');
    Route::get('change-password', [ForceChangePasswordController::class, 'showChangeForcePasswordForm'])->name('show-change-force-password');
    Route::post('/save-change-password', [ForceChangePasswordController::class, 'postUpdatePassword'])->name('update_password');
    Route::post('/check-waive', [ForceChangePasswordController::class, 'checkWaive'])->name('check-waive-count');
    Route::post('/waive-change-password', [ForceChangePasswordController::class, 'waiveChangePassword'])->name('waive-change-password');

    //ANNOUNCEMENT
    Route::get('unread-announcement', [AnnouncementsController::class, 'getUnreadAnnouncements'])->name('show-announcement');
    Route::post('read-announcement', [AnnouncementsController::class, 'markAnnouncementAsRead'])->name('read-announcement');
    Route::get('announcement', [AnnouncementsController::class, 'getAnnouncements'])->name('announcement');
    Route::get('announcement/add-announcement', [AnnouncementsController::class, 'addAnnouncementForm'])->name('add-announcement');
    Route::post('announcement/SaveAnnouncement', [AnnouncementsController::class, 'saveAnnouncement'])->name('announcement/SaveAnnouncement');
    Route::get('announcement/edit-announcement/{id}', [AnnouncementsController::class, 'editAnnouncement'])->name('edit-announcement');
    Route::post('announcement/saveEditAnnouncement', [AnnouncementsController::class, 'saveEditAnnouncement'])->name('saveEditAnnouncement');
});

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/sidebar', [MenusController::class, 'sidebarMenu'])->name('sidebar');

    //USERS
    Route::prefix('users')->group(function () {
        Route::post('/bulk_action', [AdminUsersController::class, 'bulkActions']);
        Route::post('/create', [AdminUsersController::class, 'create']);
        Route::post('/update', [AdminUsersController::class, 'update']);
        Route::get('/export', [AdminUsersController::class, 'export']);
    });


    //PROFILE PAGE
    Route::get('/profile', [ProfilePageController::class, 'getIndex'])->name('profile_page');
    Route::get('/edit_profile', [ProfilePageController::class, 'getEditProfile']);
    Route::post('/update_profile', [ProfilePageController::class, 'updateProfile']);
    Route::post('/update-theme', [ProfilePageController::class, 'updateTheme'])->name('update-theme');

    //CHANGE PASSWORD

    Route::prefix('change_password')->group(function () {
        Route::get('/', [ChangePasswordController::class, 'getIndex'])->name('change_password');
        Route::post('/update', [ChangePasswordController::class, 'changePassword'])->name('changePassword');
    });

    //PRIVILEGES
    Route::get('privileges/create-privileges', [PrivilegesController::class, 'createPrivilegesView'])->name('create-privileges');
    Route::get('privileges/edit-privileges/{id}', [PrivilegesController::class, 'getEdit'])->name('edit-privileges');
    Route::post('/privilege/postAddSave', [PrivilegesController::class, 'postAddSave'])->name('postAddSave');
    Route::post('/privilege/postEditSave', [PrivilegesController::class, 'postEditSave'])->name('postEditSave');

    //MODULES
    Route::get('create-modules', [ModulsController::class, 'getAddModuls'])->name('create-modules');
    Route::post('/module_generator/postAddSave', [ModulsController::class, 'postAddSave'])->name('postAddSave');
    Route::get('/tables', [ModulsController::class, 'getTableNames']);

    //MENUS
    Route::prefix('menu_management')->group(function () {
        Route::post('/create_menu', [MenusController::class, 'createMenu']);
        Route::post('/update_menu', [MenusController::class, 'updateMenu']);
        Route::post('/auto_update_menu', [MenusController::class, 'autoUpdateMenu']);
        Route::get('/edit/{menu}', [MenusController::class, 'editMenu']);
    });


    //Settings
    Route::post('/settings/postSave', [SettingsController::class, 'postSave'])->name('settings-post-save');
    Route::post('/settings/postDelete', [SettingsController::class, 'postDelete'])->name('settings-post-delete');

    //NOTIFICATION
    Route::get('/notifications', [NotificationsController::class, 'getLatestNotif'])->name('latest-notif');
    Route::post('/notifications/read', [NotificationsController::class, 'markAsRead'])->name('notification-read');
    Route::get('/notifications/view-notification/{id}', [NotificationsController::class, 'viewNotification'])->name('view-notification');
    Route::get('/notifications/view-all-notifications', [NotificationsController::class, 'viewAllNotification'])->name('view-all-notifications');

    //FILTER
    Route::get('/filter/privileges', [AdmRequestController::class, 'privilegesFilter'])->name('privileges-filter');
    Route::get('/filter/users', [AdmRequestController::class, 'usersFilter'])->name('users-filter');

    //EXPORT
    Route::post('/request/export', [AdmRequestController::class, 'export'])->name('export');

    //SYSTEM ERROR LOGS
    Route::prefix('system_error_logs')->group(function () {
        Route::get('/export', [SystemErrorLogsController::class, 'export']);
    });

    // LOG USER ACCESS
    Route::prefix('logs')->group(function () {
        Route::get('/export', [LogsController::class, 'export']);
    });

    // API GENERATOR
    Route::prefix('api_generator')->group(function () {
        
        //API Requests
        Route::post('/generate_key', [AdminApiController::class, 'createKey']);

        //API Key Generation
        Route::post('/deactivate_key/{id}', [AdminApiController::class, 'deactivateKey']);
        Route::post('/activate_key/{id}', [AdminApiController::class, 'activateKey']);
        Route::post('/delete_key/{id}', [AdminApiController::class, 'deleteKey']);

        //API Create Generation
        Route::post('/create_api', [AdminApiController::class, 'createApi']);

        //API Edit
        // Route::post('/edit_api/{id}', [AdminApiController::class, 'editApi']);
        Route::match(['GET', 'POST'], '/edit_api/{id}', [AdminApiController::class, 'editApi']);

    });

    // --------------------------------------- TABLE SETTINGS -------------------------------------//

    Route::prefix('module_headers')->group(function () {
        Route::post('/create', [ModuleHeadersController::class, 'create']);
        Route::post('/update', [ModuleHeadersController::class, 'update']);
        Route::get('/export', [ModuleHeadersController::class, 'export']);
        Route::get('/sort_view', [ModuleHeadersController::class, 'sortView']);
        Route::post('/sort', [ModuleHeadersController::class, 'sort']);
        Route::get('/get_header/{header_name}', [ModuleHeadersController::class, 'getHeader']);
    });


    Route::prefix('table_settings')->group(function () {
        Route::post('/create', [TableSettingsController::class, 'create']);
        Route::get('/create_view', [TableSettingsController::class, 'createView']);
        Route::get('/edit_view/{id}', [TableSettingsController::class, 'EditView']);
        Route::post('/update', [TableSettingsController::class, 'update']);
        Route::get('/get_header/{header_name}', [TableSettingsController::class, 'getHeader']);
    });

    Route::prefix('action_types')->group(function () {
        Route::post('/create', [ActionTypesController::class, 'create']);
        Route::post('/update', [ActionTypesController::class, 'update']);
    });

    // ---------------------------------------- ITEM MASTER ----------------------------------------//

    Route::prefix('item_masters')->group(function () {
        Route::get('/create_view', [ItemMastersController::class, 'getCreate']);
        Route::get('/update_view/{item}', [ItemMastersController::class, 'getUpdate']);
        Route::get('/segmentaion/{item}', [ItemMastersController::class, 'getSegmentation']);
        Route::get('/view_details/{item}', [ItemMastersController::class, 'getView']);
        Route::get('/export', [ItemMastersController::class, 'export']);
        Route::post('/create', [ItemMastersController::class, 'create']);
        Route::post('/update', [ItemMastersController::class, 'update']);
        Route::post('/post_segmentation', [ItemMastersController::class, 'updateSegmentation']);
        
        // IMPORT VIEWS
        Route::get('/import_modules', [ItemMasterModuleImportsController::class, 'getImportModules']);
        Route::get('/item_master_import', [ItemMasterModuleImportsController::class, 'getItemMasterImport']);
        Route::get('/item_master_import/sku_legend', [ItemMasterModuleImportsController::class, 'getItemMasterSkuLegendImport']);
        Route::get('/item_master_import/sku_status', [ItemMasterModuleImportsController::class, 'getItemMasterSkuStatusImport']);
        Route::get('/item_master_import/wrr_date', [ItemMasterModuleImportsController::class, 'getItemMasterWrrDateImport']);
        Route::get('/item_master_import/ecom_details', [ItemMasterModuleImportsController::class, 'getItemEcomDetailsImport']);
        Route::get('/item_master_import/accounting', [ItemMasterModuleImportsController::class, 'getItemMasterAccountingImport']);
        Route::get('/item_master_import/mcb', [ItemMasterModuleImportsController::class, 'getItemMasterMcbImport']);
        
    });

    Route::prefix('item_master_approvals')->group(function () {
        Route::get('/approval_view/{action}/{id}', [ItemMasterApprovalsController::class, 'approvalView']);
        Route::post('/approval', [ItemMasterApprovalsController::class, 'approval']);
        Route::post('/bulk_action', [ItemMasterApprovalsController::class, 'bulkActions']);
        Route::get('/export', [ItemMasterApprovalsController::class, 'export']);
    });

    Route::prefix('item_master_histories')->group(function () {
        Route::get('/view/{id}', [ItemMasterHistoriesController::class, 'view']);
    });


    // ---------------------------------------- GASHAPON ITEM MASTER ----------------------------------------//

    Route::prefix('gashapon_item_masters')->group(function () {
        Route::get('/create_view', [GashaponItemMastersController::class, 'getCreate']);
        Route::post('/create', [GashaponItemMastersController::class, 'create']);
        Route::get('/update_view/{item}', [GashaponItemMastersController::class, 'getUpdate']);
        Route::post('/update', [GashaponItemMastersController::class, 'update']);
        Route::get('/view_details/{item}', [GashaponItemMastersController::class, 'getView']);
        Route::get('/export', [GashaponItemMastersController::class, 'export']);
        Route::get('/import_view', [GashaponItemMastersController::class, 'importView']);
        Route::get('/gashapon_template', [GashaponItemMastersController::class, 'importGashaponTemplate']);
        Route::post('/import_gashapon_item', [GashaponItemMastersController::class, 'importGashaponItem']);
    });

    Route::prefix('gashapon_item_master_approvals')->group(function () {
        Route::get('/approval_view/{action}/{id}', [GashaponItemMasterApprovalsController::class, 'approvalView']);
        Route::post('/approval', [GashaponItemMasterApprovalsController::class, 'approval']);
        Route::post('/bulk_action', [GashaponItemMasterApprovalsController::class, 'bulkActions']);
        Route::get('/export', [GashaponItemMasterApprovalsController::class, 'export']);
    });

    Route::prefix('gashapon_item_master_histories')->group(function () {
        Route::get('/view/{id}', [GashaponItemMasterHistoriesController::class, 'view']);
    });


    // ---------------------------------------- RMA ITEM MASTER ----------------------------------------//

    Route::prefix('rma_item_masters')->group(function() {
        Route::get('/create_view', [RmaItemMastersController::class, 'getCreate']);
        Route::post('/create', [RmaItemMastersController::class, 'create']);
        Route::get('/update_view/{item}', [RmaItemMastersController::class, 'getUpdate']);
        Route::post('/update', [RmaItemMastersController::class, 'update']);
        Route::get('/view_details/{item}', [RmaItemMastersController::class, 'getView']);
        Route::get('/export', [RmaItemMastersController::class, 'export']);
    });

    Route::prefix('rma_item_master_approvals')->group(function() {
        Route::get('/approval_view/{action}/{id}', [RmaItemMasterApprovalsController::class, 'approvalView']);
        Route::post('/approval', [RmaItemMasterApprovalsController::class, 'approval']);
        Route::post('/bulk_action', [RmaItemMasterApprovalsController::class, 'bulkActions']);
        Route::get('/export', [RmaItemMasterApprovalsController::class, 'export']);
    });

    Route::prefix('rma_item_master_histories')->group(function() {
        Route::get('/view/{id}', [RmaItemMasterHistoriesController::class, 'view']);
    });

    // ----------------------------------------- SUBMASTERS -----------------------------------------//

    // APPLE LOBS
    Route::prefix('apple_lobs')->group(function () {
        Route::post('/create', [AppleLobsController::class, 'create']);
        Route::post('/update', [AppleLobsController::class, 'update']);
        Route::get('/export', [AppleLobsController::class, 'export']);
    });

    // BRANDS
    Route::prefix('brands')->group(function () {
        Route::post('/create', [BrandsController::class, 'create']);
        Route::post('/update', [BrandsController::class, 'update']);
        Route::get('/export', [BrandsController::class, 'export']);
    });

    // BRAND DIRECTIONS
    Route::prefix('brand_directions')->group(function () {
        Route::post('/create', [BrandDirectionsController::class, 'create']);
        Route::post('/update', [BrandDirectionsController::class, 'update']);
        Route::get('/export', [BrandDirectionsController::class, 'export']);
    });

    // BRAND GROUPS
    Route::prefix('brand_groups')->group(function () {
        Route::post('/create', [BrandGroupsController::class, 'create']);
        Route::post('/update', [BrandGroupsController::class, 'update']);
        Route::get('/export', [BrandGroupsController::class, 'export']);
    });

    // BRAND MARKETINGS
    Route::prefix('brand_marketings')->group(function () {
        Route::post('/create', [BrandMarketingsController::class, 'create']);
        Route::post('/update', [BrandMarketingsController::class, 'update']);
        Route::get('/export', [BrandMarketingsController::class, 'export']);
    });

    // CATEGORIES
    Route::prefix('categories')->group(function () {
        Route::post('/create', [CategoriesController::class, 'create']);
        Route::post('/update', [CategoriesController::class, 'update']);
        Route::get('/export', [CategoriesController::class, 'export']);
    });

    // CLASSIFICATIONS
    Route::prefix('classifications')->group(function () {
        Route::post('/create', [ClassificationsController::class, 'create']);
        Route::post('/update', [ClassificationsController::class, 'update']);
        Route::get('/export', [ClassificationsController::class, 'export']);
    });

    // COLORS
    Route::prefix('colors')->group(function () {
        Route::post('/create', [ColorsController::class, 'create']);
        Route::post('/update', [ColorsController::class, 'update']);
        Route::get('/export', [ColorsController::class, 'export']);
    });

    // COUNTERS
    Route::prefix('counters')->group(function () {
        Route::post('/create', [CountersController::class, 'create']);
        Route::post('/update', [CountersController::class, 'update']);
    });

    // CURRENCIES   
    Route::prefix('currencies')->group(function () {
        Route::post('/create', [CurrenciesController::class, 'create']);
        Route::post('/update', [CurrenciesController::class, 'update']);
        Route::get('/export', [CurrenciesController::class, 'export']);
    });

    // IDENTIFIERS   
    Route::prefix('identifiers')->group(function () {
        Route::post('/create', [IdentifiersController::class, 'create']);
        Route::post('/update', [IdentifiersController::class, 'update']);
        Route::get('/export', [IdentifiersController::class, 'export']);
    });

    // INCOTERMS
    Route::prefix('incoterms')->group(function () {
        Route::post('/create', [IncotermsController::class, 'create']);
        Route::post('/update', [IncotermsController::class, 'update']);
        Route::get('/export', [IncotermsController::class, 'export']);
    });

    // INVENTORY TYPES
    Route::prefix('inventory_types')->group(function () {
        Route::post('/create', [InventoryTypesController::class, 'create']);
        Route::post('/update', [InventoryTypesController::class, 'update']);
        Route::get('/export', [InventoryTypesController::class, 'export']);
    });

    // ITEM PLATFORMS
    Route::prefix('item_platforms')->group(function () {
        Route::post('/create', [ItemPlatformsController::class, 'create']);
        Route::post('/update', [ItemPlatformsController::class, 'update']);
        Route::get('/export', [ItemPlatformsController::class, 'export']);
    });

    // ITEM PROMO TYPES
    Route::prefix('item_promo_types')->group(function () {
        Route::post('/create', [ItemPromoTypesController::class, 'create']);
        Route::post('/update', [ItemPromoTypesController::class, 'update']);
        Route::get('/export', [ItemPromoTypesController::class, 'export']);
    });

    // ITEM SEGMENTATIONS
    Route::prefix('item_segmentations')->group(function () {
        Route::post('/create', [ItemSegmentationsController::class, 'create']);
        Route::post('/update', [ItemSegmentationsController::class, 'update']);
        Route::get('/export', [ItemSegmentationsController::class, 'export']);
    });

    // ITEM SERIALS
    Route::prefix('item_serials')->group(function () {
        Route::post('/create', [ItemSerialsController::class, 'create']);
        Route::post('/update', [ItemSerialsController::class, 'update']);
        Route::get('/export', [ItemSerialsController::class, 'export']);
    });

    // MARGIN CATEGORIES
    Route::prefix('margin_categories')->group(function () {
        Route::post('/create', [MarginCategoriesController::class, 'create']);
        Route::post('/update', [MarginCategoriesController::class, 'update']);
        Route::get('/export', [MarginCategoriesController::class, 'export']);
    });

    // MODEL SPECIFICS
    Route::prefix('model_specifics')->group(function () {
        Route::post('/create', [ModelSpecificsController::class, 'create']);
        Route::post('/update', [ModelSpecificsController::class, 'update']);
        Route::get('/export', [ModelSpecificsController::class, 'export']);
    });

    // PLATFORMS
    Route::prefix('platforms')->group(function () {
        Route::post('/create', [PlatformsController::class, 'create']);
        Route::post('/update', [PlatformsController::class, 'update']);
        Route::get('/export', [PlatformsController::class, 'export']);
    });

    // PROMO TYPES
    Route::prefix('promo_types')->group(function () {
        Route::post('/create', [PromoTypesController::class, 'create']);
        Route::post('/update', [PromoTypesController::class, 'update']);
        Route::get('/export', [PromoTypesController::class, 'export']);
    });

    // SEGMENTATIONS
    Route::prefix('segmentations')->group(function () {
        Route::post('/create', [SegmentationsController::class, 'create']);
        Route::post('/update', [SegmentationsController::class, 'update']);
        Route::get('/export', [SegmentationsController::class, 'export']);
    });

    // SIZES
    Route::prefix('sizes')->group(function () {
        Route::post('/create', [SizesController::class, 'create']);
        Route::post('/update', [SizesController::class, 'update']);
        Route::get('/export', [SizesController::class, 'export']);
    });

    // SKU CLASSIFICATIONS
    Route::prefix('sku_classifications')->group(function () {
        Route::post('/create', [SkuClassificationsController::class, 'create']);
        Route::post('/update', [SkuClassificationsController::class, 'update']);
        Route::get('/export', [SkuClassificationsController::class, 'export']);
    });

    // SKU LEGENDS
    Route::prefix('sku_legends')->group(function () {
        Route::post('/create', [SkuLegendsController::class, 'create']);
        Route::post('/update', [SkuLegendsController::class, 'update']);
        Route::get('/export', [SkuLegendsController::class, 'export']);
    });

    // SKU STATUSES
    Route::prefix('sku_statuses')->group(function () {
        Route::post('/create', [SkuStatusesController::class, 'create']);
        Route::post('/update', [SkuStatusesController::class, 'update']);
        Route::get('/export', [SkuStatusesController::class, 'export']);
    });

    // STORE CATEGORIES
    Route::prefix('store_categories')->group(function () {
        Route::post('/create', [StoreCategoriesController::class, 'create']);
        Route::post('/update', [StoreCategoriesController::class, 'update']);
        Route::get('/export', [StoreCategoriesController::class, 'export']);
    });

    // SUB CATEGORIES
    Route::prefix('sub_categories')->group(function () {
        Route::post('/create', [SubCategoriesController::class, 'create']);
        Route::post('/update', [SubCategoriesController::class, 'update']);
        Route::get('/export', [SubCategoriesController::class, 'export']);
    });

    // SUB CLASSIFICATIONS
    Route::prefix('sub_classifications')->group(function () {
        Route::post('/create', [SubClassificationsController::class, 'create']);
        Route::post('/update', [SubClassificationsController::class, 'update']);
        Route::get('/export', [SubClassificationsController::class, 'export']);
    });

    // SUPPORT TYPES
    Route::prefix('support_types')->group(function () {
        Route::post('/create', [SupportTypesController::class, 'create']);
        Route::post('/update', [SupportTypesController::class, 'update']);
        Route::get('/export', [SupportTypesController::class, 'export']);
    });

    // UOMS
    Route::prefix('uoms')->group(function () {
        Route::post('/create', [UomsController::class, 'create']);
        Route::post('/update', [UomsController::class, 'update']);
        Route::get('/export', [UomsController::class, 'export']);
    });

    // VENDORS
    Route::prefix('vendors')->group(function () {
        Route::post('/create', [VendorsController::class, 'create']);
        Route::post('/update', [VendorsController::class, 'update']);
        Route::get('/export', [VendorsController::class, 'export']);
    });

    // VENDOR GROUPS
    Route::prefix('vendor_groups')->group(function () {
        Route::post('/create', [VendorGroupsController::class, 'create']);
        Route::post('/update', [VendorGroupsController::class, 'update']);
        Route::get('/export', [VendorGroupsController::class, 'export']);
    });

    // VENDOR TYPES
    Route::prefix('vendor_types')->group(function () {
        Route::post('/create', [VendorTypesController::class, 'create']);
        Route::post('/update', [VendorTypesController::class, 'update']);
        Route::get('/export', [VendorTypesController::class, 'export']);
    });

    // WAREHOUSE CATEGORIES
    Route::prefix('warehouse_categories')->group(function () {
        Route::post('/create', [WarehouseCategoriesController::class, 'create']);
        Route::post('/update', [WarehouseCategoriesController::class, 'update']);
        Route::get('/export', [WarehouseCategoriesController::class, 'export']);
    });

    // WARRANTIES
    Route::prefix('warranties')->group(function () {
        Route::post('/create', [WarrantiesController::class, 'create']);
        Route::post('/update', [WarrantiesController::class, 'update']);
        Route::get('/export', [WarrantiesController::class, 'export']);
    });

    // ----------------------------------- GASHAPON SUBMASTERS -------------------------------------//

    // GASHAPON BRANDS
    Route::prefix('gashapon_brands')->group(function () {
        Route::post('/create', [GashaponBrandsController::class, 'create']);
        Route::post('/update', [GashaponBrandsController::class, 'update']);
        Route::get('/export', [GashaponBrandsController::class, 'export']);
    });

    // GASHAPON CATEGORIES
    Route::prefix('gashapon_categories')->group(function () {
        Route::post('/create', [GashaponCategoriesController::class, 'create']);
        Route::post('/update', [GashaponCategoriesController::class, 'update']);
        Route::get('/export', [GashaponCategoriesController::class, 'export']);
    });

    // GASHAPON COUNTRIES
    Route::prefix('gashapon_countries')->group(function () {
        Route::post('/create', [GashaponCountriesController::class, 'create']);
        Route::post('/update', [GashaponCountriesController::class, 'update']);
        Route::get('/export', [GashaponCountriesController::class, 'export']);
    });

    // GASHAPON INCOTERMS
    Route::prefix('gashapon_incoterms')->group(function () {
        Route::post('/create', [GashaponIncotermsController::class, 'create']);
        Route::post('/update', [GashaponIncotermsController::class, 'update']);
        Route::get('/export', [GashaponIncotermsController::class, 'export']);
    });

    // GASHAPON INVENTORY TYPES
    Route::prefix('gashapon_inventory_types')->group(function () {
        Route::post('/create', [GashaponInventoryTypesController::class, 'create']);
        Route::post('/update', [GashaponInventoryTypesController::class, 'update']);
        Route::get('/export', [GashaponInventoryTypesController::class, 'export']);
    });

    // GASHAPON MODELS
    Route::prefix('gashapon_models')->group(function () {
        Route::post('/create', [GashaponModelsController::class, 'create']);
        Route::post('/update', [GashaponModelsController::class, 'update']);
        Route::get('/export', [GashaponModelsController::class, 'export']);
    });

    // GASHAPON PRODUCT TYPES
    Route::prefix('gashapon_product_types')->group(function () {
        Route::post('/create', [GashaponProductTypesController::class, 'create']);
        Route::post('/update', [GashaponProductTypesController::class, 'update']);
        Route::get('/export', [GashaponProductTypesController::class, 'export']);
    });

    // GASHAPON SKU STATUSES
    Route::prefix('gashapon_sku_statuses')->group(function () {
        Route::post('/create', [GashaponSkuStatusesController::class, 'create']);
        Route::post('/update', [GashaponSkuStatusesController::class, 'update']);
        Route::get('/export', [GashaponSkuStatusesController::class, 'export']);
    });

    // GASHAPON UOMS
    Route::prefix('gashapon_uoms')->group(function () {
        Route::post('/create', [GashaponUomsController::class, 'create']);
        Route::post('/update', [GashaponUomsController::class, 'update']);
        Route::get('/export', [GashaponUomsController::class, 'export']);
    });

    // GASHAPON VENDOR GROUPS
    Route::prefix('gashapon_vendor_groups')->group(function () {
        Route::post('/create', [GashaponVendorGroupsController::class, 'create']);
        Route::post('/update', [GashaponVendorGroupsController::class, 'update']);
        Route::get('/export', [GashaponVendorGroupsController::class, 'export']);
    });

    // GASHAPON VENDOR TYPES
    Route::prefix('gashapon_vendor_types')->group(function () {
        Route::post('/create', [GashaponVendorTypesController::class, 'create']);
        Route::post('/update', [GashaponVendorTypesController::class, 'update']);
        Route::get('/export', [GashaponVendorTypesController::class, 'export']);
    });

    // GASHAPON WAREHOUSE CATEGORIES
    Route::prefix('gashapon_warehouse_categories')->group(function () {
        Route::post('/create', [GashaponWarehouseCategoriesController::class, 'create']);
        Route::post('/update', [GashaponWarehouseCategoriesController::class, 'update']);
        Route::get('/export', [GashaponWarehouseCategoriesController::class, 'export']);
    });

    // ------------------------------------ RMA SUBMASTERS -------------------------------------//

    // RMA CATEGORIES
    Route::prefix('rma_categories')->group(function () {
        Route::post('/create', [RmaCategoriesController::class, 'create']);
        Route::post('/update', [RmaCategoriesController::class, 'update']);
        Route::get('/export', [RmaCategoriesController::class, 'export']);
    });

    // RMA CLASSIFICATIONS
    Route::prefix('rma_classifications')->group(function () {
        Route::post('/create', [RmaClassificationsController::class, 'create']);
        Route::post('/update', [RmaClassificationsController::class, 'update']);
        Route::get('/export', [RmaClassificationsController::class, 'export']);
    });

    // RMA MARGIN CATEGORIES
    Route::prefix('rma_margin_categories')->group(function () {
        Route::post('/create', [RmaMarginCategoriesController::class, 'create']);
        Route::post('/update', [RmaMarginCategoriesController::class, 'update']);
        Route::get('/export', [RmaMarginCategoriesController::class, 'export']);
    });

    // RMA MODEL SPECIFICS
    Route::prefix('rma_model_specifics')->group(function () {
        Route::post('/create', [RmaModelSpecificsController::class, 'create']);
        Route::post('/update', [RmaModelSpecificsController::class, 'update']);
        Route::get('/export', [RmaModelSpecificsController::class, 'export']);
    });

    // RMA MODEL SPECIFICS
    Route::prefix('rma_store_categories')->group(function () {
        Route::post('/create', [RmaStoreCategoriesController::class, 'create']);
        Route::post('/update', [RmaStoreCategoriesController::class, 'update']);
        Route::get('/export', [RmaStoreCategoriesController::class, 'export']);
    });

    // RMA SUB CLASSIFICATIONS
    Route::prefix('rma_sub_classifications')->group(function () {
        Route::post('/create', [RmaSubClassificationsController::class, 'create']);
        Route::post('/update', [RmaSubClassificationsController::class, 'update']);
        Route::get('/export', [RmaSubClassificationsController::class, 'export']);
    });

    // RMA UOMS
    Route::prefix('rma_uoms')->group(function () {
        Route::post('/create', [RmaUomsController::class, 'create']);
        Route::post('/update', [RmaUomsController::class, 'update']);
        Route::get('/export', [RmaUomsController::class, 'export']);
    });
});

Route::group([
    'middleware' => ['auth', 'check.user'],
    'prefix' => config('adm_url.ADMIN_PATH'),
    'namespace' => 'App\Http\Controllers',
], function () {

    // Todo: change table
    $modules = [];
    try {
        $modules = DB::table('adm_modules')->whereIn('controller', CommonHelpers::getOthersControllerFiles())->get();
    } catch (\Exception $e) {
        Log::error("Load adm moduls is failed. Caused = " . $e->getMessage());
    }

    foreach ($modules as $v) {
        if (@$v->path && @$v->controller) {
            try {
                CommonHelpers::routeOtherController($v->path, $v->controller, 'app\Http\Controllers');
            } catch (\Exception $e) {
                Log::error("Path = " . $v->path . "\nController = " . $v->controller . "\nError = " . $e->getMessage());
            }
        }
    }
})->middleware('auth');

//ADMIN ROUTE
Route::group([
    'middleware' => ['auth', 'check.user'],
    'prefix' => config('ad_url.ADMIN_PATH'),
    'namespace' => 'App\Http\Controllers\Admin',
], function () {

    // Todo: change table
    if (request()->is(config('ad_url.ADMIN_PATH'))) {
        $menus = DB::table('adm_menuses')->where('is_dashboard', 1)->first();
        if ($menus) {
            Route::get('/', 'Dashboard\DashboardContentGetIndex');
        } else {
            CommonHelpers::routeController('/', 'AdminController', 'App\Http\Controllers\Admin');
        }
    }

    // Todo: change table
    $modules = [];
    try {
        $modules = DB::table('adm_modules')->whereIn('controller', CommonHelpers::getMainControllerFiles())->get();
    } catch (\Exception $e) {
        Log::error("Load ad moduls is failed. Caused = " . $e->getMessage());
    }

    foreach ($modules as $v) {
        if (@$v->path && @$v->controller) {
            try {
                CommonHelpers::routeController($v->path, $v->controller, 'app\Http\Controllers\Admin');
            } catch (\Exception $e) {
                Log::error("Path = " . $v->path . "\nController = " . $v->controller . "\nError = " . $e->getMessage());
            }
        }
    }
})->middleware('auth');


// API Requests
Route::prefix('api')->group(function () {
    Route::match(['get', 'post', 'delete'], '/{endpoint}/{id?}', [ApiController::class, 'handleRequest']);
});