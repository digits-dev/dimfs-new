<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
use App\Models\Brands;
use App\Models\Classifications;
use App\Models\ItemMaster;
use App\Models\SubClassifications;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{

    public function getIndex(): Response
    {

        $itemsIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                     </svg>';
        
        $brandsIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                      </svg>';

        $classIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                      </svg>';

        $subClassIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3" />
                        </svg>';
        $data = [];

        $data['item_master_stats'] = [
            [
                'value' => ItemMaster::count(),
                'label' => 'Items',
                'sublabel' => 'Total Items',
                'icon' => $itemsIcon,
                'gradient' => 'bg-gradient-to-br from-orange-400 to-pink-600',
                'href' => '/item_masters',
            ],
            [
                'value' => Brands::count(),
                'label' => 'Brands',
                'sublabel' => 'Active Brands',
                'icon' => $brandsIcon,
                'gradient' => 'bg-gradient-to-br from-green-400 to-emerald-600',
                'href' => '/brands',
            ],
            [
                'value' => Classifications::count(),
                'label' => 'Classes',
                'sublabel' => 'Active Classes',
                'icon' => $classIcon,
                'gradient' => 'bg-gradient-to-br from-red-400 to-rose-600',
                'href' => '/classifications',
            ],
            [
                'value' => SubClassifications::count(),
                'label' => 'Sub Classes',
                'sublabel' => 'Active Sub Classes',
                'icon' => $subClassIcon,
                'gradient' => 'bg-gradient-to-br from-blue-400 to-cyan-600',
                'href' => '/sub_classifications',
            ],
        ];

        $data['item_master_creation_counter'] = ItemMaster::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();

        $data['item_master_update_counter'] = ItemMaster::select(DB::raw('DATE(updated_at) as date'), DB::raw('count(*) as count'))
        ->where('updated_at', '!=', null)
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();

        // dd($data['item_master_update_counter']);

        return Inertia::render('Dashboard/Dashboard', $data);
    }
}
