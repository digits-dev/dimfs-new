<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('item_masters', function (Blueprint $table) {
            $table->renameColumn('dtp_rf', 'store_cost');
            $table->renameColumn('dtp_rf_percentage', 'store_cost_percentage');
            $table->renameColumn('dtp_dcon', 'consignment_store_cost');
            $table->renameColumn('dtp_dcon_percentage', 'consignment_store_cost_percentage');
            $table->renameColumn('working_dtp_rf', 'working_store_cost');
            $table->renameColumn('working_dtp_rf_percentage', 'working_store_cost_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_masters', function (Blueprint $table) {
            $table->renameColumn('store_cost', 'dtp_rf');
            $table->renameColumn('store_cost_percentage', 'dtp_rf_percentage');
            $table->renameColumn('consignment_store_cost', 'dtp_dcon');
            $table->renameColumn('consignment_store_cost_percentage', 'dtp_dcon_percentage');
            $table->renameColumn('working_store_cost', 'working_dtp_rf');
            $table->renameColumn('working_store_cost_percentage', 'working_dtp_rf_percentage');
        });
    }
};
