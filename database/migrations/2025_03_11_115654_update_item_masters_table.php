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
            $table->string('compatibility')->after('model')->nullable();
            $table->integer('apple_report_inclusion')->after('brand_marketings_id')->nullable();
            $table->integer('apple_lobs_id')->after('apple_report_inclusion')->nullable();
            $table->string('size', 50)->after('actual_color')->nullable();
            $table->string('size_value', 10)->after('actual_color')->nullable();
            $table->integer('sizes_id')->after('size_value')->nullable();
            $table->integer('uoms_id')->after('sizes_id')->nullable();
            $table->integer('support_types_id')->after('uoms_id')->nullable();
            $table->string('device_uid')->after('inventory_types_id')->nullable();
            $table->string('product_type')->after('device_uid')->nullable();
            $table->decimal('item_length', 8, 2)->after('moq')->nullable();
            $table->decimal('item_width', 8, 2)->after('item_length')->nullable();
            $table->decimal('item_height', 8, 2)->after('item_width')->nullable();
            $table->decimal('item_weight', 8, 2)->after('item_height')->nullable();

        

            // ACCOUNTING
            $table->decimal('ecom_store_cost', 18, 2)->nullable()->after('working_store_cost_percentage');
            $table->decimal('ecom_store_cost_percentage', 18, 2)->nullable()->after('ecom_store_cost');
            $table->decimal('ecom_working_store_cost', 18, 2)->nullable()->after('ecom_store_cost_percentage');
            $table->decimal('ecom_working_store_cost_percentage', 18, 2)->nullable()->after('ecom_working_store_cost');
            $table->decimal('actual_landed_cost', 18, 2)->nullable()->after('landed_cost');
            $table->decimal('landed_cost_sea', 18, 2)->nullable()->after('actual_landed_cost');

            $table->dateTime('duration_from')->nullable()->after('landed_cost_sea');
            $table->dateTime('duration_to')->nullable()->after('duration_from');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_masters', function (Blueprint $table) {
            $table->dropColumn('compatibility');
            $table->dropColumn('apple_report_inclusion');
            $table->dropColumn('apple_lobs_id');
            $table->dropColumn('size');
            $table->dropColumn('size_value');
            $table->dropColumn('sizes_id');
            $table->dropColumn('uoms_id');
            $table->dropColumn('device_uid');
            $table->dropColumn('product_type');
            $table->dropColumn('item_length');
            $table->dropColumn('item_width');
            $table->dropColumn('item_height');
            $table->dropColumn('item_weight');
            
            $table->dropColumn('ecom_store_cost');
            $table->dropColumn('ecom_store_cost_percentage');
            $table->dropColumn('ecom_working_store_cost');
            $table->dropColumn('ecom_working_store_cost_percentage');
            $table->dropColumn('actual_landed_cost');
            $table->dropColumn('landed_cost_sea');
            $table->dropColumn('support_types_id');
            $table->dropColumn('duration_from');
            $table->dropColumn('duration_to');
            
            //
        });
    }
};
