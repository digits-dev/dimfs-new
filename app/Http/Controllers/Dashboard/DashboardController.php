<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\AdmEmbeddedDashboard;
use App\Models\AdmEmbeddedDashboardPrivilege;
use App\Models\AdmModels\AdmSettings;
use App\Models\Brands;
use App\Models\Classifications;
use App\Models\GashaponBrands;
use App\Models\GashaponCategories;
use App\Models\GashaponItemMaster;
use App\Models\GashaponItemMasterHistory;
use App\Models\GashaponProductTypes;
use App\Models\ItemMaster;
use App\Models\ItemMasterHistory;
use App\Models\RmaCategories;
use App\Models\RmaClassifications;
use App\Models\RmaItemMaster;
use App\Models\RmaItemMasterHistory;
use App\Models\RmaSubClassifications;
use App\Models\SubClassifications;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{

    public function getIndex(): Response
    {

        $data = [];

        $icons = [
            'items' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>',
            'brands' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                        </svg>',
            'classes' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                        </svg>',
            'sub_classes' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3" />
                            </svg>',
        ];

        // ITEM MASTER
       
        $item_master_count = ItemMaster::count();

        $data['item_master_stats'] = [
            [
                'name' => 'Item Master',
                'value' => $item_master_count,
                'label' => 'Items',
                'sublabel' => 'Total Items',
                'icon' => $icons['items'],
                'gradient' => 'linear-gradient(to bottom right, #fb923c, #db2777)',
                'href' => '/item_masters',
                'total' => $item_master_count,
            ],
            [
                'name' => 'Brands',
                'value' => Brands::where('status', 'ACTIVE')->count(),
                'label' => 'Brands',
                'sublabel' => 'Active Brands',
                'icon' => $icons['brands'],
                'gradient' => 'linear-gradient(to bottom right, #4ade80, #059669)',
                'href' => '/brands',
                'total' => Brands::count(),
            ],
            [
                'name' => 'Classification',
                'value' => Classifications::where('status', 'ACTIVE')->count(),
                'label' => 'Classes',
                'sublabel' => 'Active Classes',
                'icon' => $icons['classes'],
                'gradient' => 'linear-gradient(to bottom right, #f87171, #e11d48)',
                'href' => '/classifications',
                'total' => Classifications::count(),
            ],
            [
                'name' => 'Sub Classification',
                'value' => SubClassifications::where('status', 'ACTIVE')->count(),
                'label' => 'Sub Classes',
                'sublabel' => 'Active Sub Classes',
                'icon' => $icons['sub_classes'],
                'gradient' => 'linear-gradient(to bottom right, #60a5fa, #0891b2)',
                'href' => '/sub_classifications',
                'total' => SubClassifications::count(),
            ],
        ];

        $data['item_master_creation_counter'] = ItemMaster::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();

        $data['item_master_update_counter'] = ItemMasterHistory::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
        ->where('action', 'UPDATE-APPROVED')
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();


        // GASHAPON ITEM MASTER

        $gashapon_item_master_count = GashaponItemMaster::count();

        $data['gashapon_item_master_stats'] = [
            [
                'name' => 'Gashapon Item Master',
                'value' => $gashapon_item_master_count,
                'label' => 'Gashapon Items',
                'sublabel' => 'Total Gashapon Items',
                'icon' => $icons['items'],
                'gradient' => 'linear-gradient(to bottom right, #fb923c, #db2777)',
                'href' => '/gashapon_item_masters',
                'total' => $item_master_count,
            ],
            [
                'name' => 'Gashapon Brands',
                'value' => GashaponBrands::where('status', 'ACTIVE')->count(),
                'label' => 'Gashapon Brands',
                'sublabel' => 'Active Gashapon Brands',
                'icon' => $icons['brands'],
                'gradient' => 'linear-gradient(to bottom right, #4ade80, #059669)',
                'href' => '/gashapon_brands',
                'total' => GashaponBrands::count(),
            ],
            [
                'name' => 'Gashapon Categories',
                'value' => GashaponCategories::where('status', 'ACTIVE')->count(),
                'label' => 'Gashapon Categories',
                'sublabel' => 'Active Gashapon Categories',
                'icon' => $icons['classes'],
                'gradient' => 'linear-gradient(to bottom right, #f87171, #e11d48)',
                'href' => '/gashapon_categories',
                'total' => GashaponCategories::count(),
            ],
            [
                'name' => 'Gashapon Product Types',
                'value' => GashaponProductTypes::where('status', 'ACTIVE')->count(),
                'label' => 'Product Types',
                'sublabel' => 'Active Product Types',
                'icon' => $icons['sub_classes'],
                'gradient' => 'linear-gradient(to bottom right, #60a5fa, #0891b2)',
                'href' => '/gashapon_product_types',
                'total' => GashaponProductTypes::count(),
            ],
        ];

        $data['gashapon_item_master_creation_counter'] = GashaponItemMaster::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();

        $data['gashapon_item_master_update_counter'] = GashaponItemMasterHistory::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
        ->where('action', 'UPDATE-APPROVED')
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();

        // RMA ITEM MASTER 

        $rma_item_master_count = RmaItemMaster::count();

        $data['rma_item_master_stats'] = [
            [
                'name' => 'RMA Item Master',
                'value' => $rma_item_master_count,
                'label' => 'RMA Items',
                'sublabel' => 'Total RMA Items',
                'icon' => $icons['items'],
                'gradient' => 'linear-gradient(to bottom right, #fb923c, #db2777)',
                'href' => '/rma_item_masters',
                'total' => $rma_item_master_count,
            ],
            [
                'name' => 'RMA Categories',
                'value' => RmaCategories::where('status', 'ACTIVE')->count(),
                'label' => 'RMA Categories',
                'sublabel' => 'Active RMA Categories',
                'icon' => $icons['brands'],
                'gradient' => 'linear-gradient(to bottom right, #4ade80, #059669)',
                'href' => '/rma_categories',
                'total' => RmaCategories::count(),
            ],
            [
                'name' => 'RMA Classification',
                'value' => RmaClassifications::where('status', 'ACTIVE')->count(),
                'label' => 'RMA Classes',
                'sublabel' => 'Active RMA Classes',
                'icon' => $icons['classes'],
                'gradient' => 'linear-gradient(to bottom right, #f87171, #e11d48)',
                'href' => '/rma_classifications',
                'total' => RmaClassifications::count(),
            ],
            [
                'name' => 'RMA Sub Classification',
                'value' => RmaSubClassifications::where('status', 'ACTIVE')->count(),
                'label' => 'RMA Sub Classes',
                'sublabel' => 'Active RMA Sub Classes',
                'icon' => $icons['sub_classes'],
                'gradient' => 'linear-gradient(to bottom right, #60a5fa, #0891b2)',
                'href' => '/rma_sub_classifications',
                'total' => RmaSubClassifications::count(),
            ],
        ];

        $data['rma_item_master_creation_counter'] = RmaItemMaster::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();

        $data['rma_item_master_update_counter'] = RmaItemMasterHistory::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
        ->where('action', 'UPDATE-APPROVED')
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();

        // FOR EMBEDDED AND DEFAULT DASHBOARD

        $data['dashboard_settings_data'] = AdmSettings::whereIn('name', ['Default Dashboard', 'Embedded Dashboard'])
        ->get()
        ->mapWithKeys(function ($item) {
            return [$item->content => $item->content_input_type];
        })
        ->toArray();

        $dashboard_privilege = AdmEmbeddedDashboardPrivilege::where('adm_privileges_id',  CommonHelpers::myId())
                ->pluck('adm_embedded_dashboard_id');

        $data['embedded_dashboards'] = AdmEmbeddedDashboard::whereIn('id', $dashboard_privilege)
            ->where('status', 'ACTIVE')
            ->get();
        
        return Inertia::render('Dashboard/Dashboard', $data);
    }
}
