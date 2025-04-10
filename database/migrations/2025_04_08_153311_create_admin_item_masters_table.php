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
        Schema::create('admin_item_masters', function (Blueprint $table) {
            $table->id();
            $table->string('digits_code', 10)->nullable();
            $table->string('upc_code', 60);
            $table->string('upc_code2', 60)->nullable();
            $table->string('upc_code3', 60)->nullable();
            $table->string('upc_code4', 60)->nullable();
            $table->string('upc_code5', 60)->nullable();
            $table->string('item_code', 50)->nullable();
            $table->string('supplier_itemcode', 60)->nullable();
            $table->string('item_description', 100)->nullable();
            $table->unsignedInteger('admin_brands_id')->nullable()->index('admin_brands_id_index');
            $table->unsignedInteger('admin_margin_categories_id')->nullable()->index('admin_margin_categories_id_index');
            $table->unsignedInteger('admin_categories_id')->nullable()->index('admin_categories_id_index');
            $table->unsignedInteger('admin_sub_categories_id')->nullable()->index('admin_sub_categories_id_index');
            $table->unsignedInteger('admin_classification_id')->nullable()->index('admin_classifications_id_index');
            $table->unsignedInteger('admin_sub_classification_id')->nullable()->index('admin_sub_classification_id_index');
            $table->unsignedInteger('admin_store_categories_id')->nullable()->index('admin_store_categories_id_index');
            $table->unsignedInteger('admin_warehouse_categories_id')->nullable()->index('admin_warehouse_categories_id_index');
            $table->string('model', 50)->nullable();
            $table->unsignedInteger('admin_model_specifics_id')->nullable()->index('admin_model_specifics_id_index');
            $table->unsignedInteger('admin_colors_id')->nullable()->index('admin_colors_id_index');
            $table->string('actual_color', 50)->nullable();
            $table->string('size', 50)->nullable();
            $table->string('size_num', 30)->default(0);
            $table->unsignedInteger('admin_sizes_id')->nullable()->index('admin_sizes_id_index');
            $table->unsignedInteger('admin_uoms_id')->nullable()->index('admin_uoms_id_index');
            $table->unsignedInteger('admin_vendors_id_')->nullable()->index('admin_vendors_id_index');
            $table->integer('vendor2_id')->nullable();
            $table->integer('vendor3_id')->nullable();
            $table->integer('vendor4_id')->nullable();
            $table->integer('vendor5_id')->nullable();
            $table->unsignedInteger('admin_vendor_types_id')->nullable()->index('admin_vendor_types_id_index');
            $table->unsignedInteger('admin_incoterms_id')->nullable()->index('admin_incoterms_id_index');
            $table->unsignedInteger('admin_suppliers_id')->nullable();
            $table->unsignedInteger('admin_inventories_id')->nullable();
            $table->string('serialized')->nullable();
            $table->integer('serial_code')->default(0);
            $table->integer('imei_code1')->default(0);
            $table->integer('imei_code2')->default(0);
            $table->unsignedInteger('sku_status_id')->nullable();
            $table->unsignedInteger('sku_legend_id')->nullable();
            $table->decimal('store_cost', 18, 2)->nullable();
            $table->decimal('store_cost_percentage', 18, 2)->nullable();
            $table->decimal('consignment_store_cost', 18, 2)->nullable();
            $table->decimal('consignment_store_cost_percentage', 18, 2)->nullable();
            $table->decimal('original_srp', 18, 2)->nullable();
            $table->decimal('current_srp', 18, 2)->nullable();
            $table->decimal('promo_srp', 18, 2)->nullable();
            $table->decimal('promo_change', 18, 2)->nullable();
            $table->date('promo_effective_date')->nullable();
            $table->decimal('price_change', 18, 2)->nullable();
            $table->unsignedInteger('price_status_id')->nullable();
            $table->date('effective_date')->nullable();
            $table->decimal('moq', 18, 2)->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->decimal('purchase_price', 18, 2)->nullable();
            $table->decimal('exchange_rate', 18, 2)->nullable();
            $table->decimal('cost_factor', 18, 2)->nullable();
            $table->decimal('landed_cost', 18, 2)->nullable();
            $table->decimal('working_landed_cost', 18, 2)->nullable();
            $table->decimal('working_store_cost', 18, 2)->nullable();
            $table->decimal('working_store_cost_percentage', 18, 2)->nullable();
            $table->date('initial_wrr_date')->nullable();
            $table->date('latest_wrr_date')->nullable();
            $table->unsignedInteger('warranty_duration')->default(0);
            $table->integer('admin_warranties_id')->nullable();
            $table->unsignedInteger('is_reclass')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_item_masters');
    }
};
