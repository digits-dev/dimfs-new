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
        Schema::create('rma_item_masters', function (Blueprint $table) {
            $table->id();
            $table->string('digits_code', 8)->nullable();
            $table->string('upc_code', 60)->nullable();
            $table->string('upc_code2', 60)->nullable();
            $table->string('upc_code3', 60)->nullable();
            $table->string('upc_code4', 60)->nullable();
            $table->string('upc_code5', 60)->nullable();
            $table->string('supplier_item_code', 60)->nullable();
            $table->string('item_description', 100)->nullable();
            $table->integer('brands_id')->nullable();
            $table->integer('rma_categories_id')->nullable();
            $table->integer('rma_classes_id')->nullable();
            $table->integer('rma_subclasses_id')->nullable();
            $table->integer('rma_store_categories_id')->nullable();
            $table->integer('rma_margin_categories_id')->nullable();
            $table->integer('warehouse_categories_id')->nullable();
            $table->string('model', 50)->nullable();
            $table->integer('rma_model_specifics_id')->nullable();
            $table->integer('colors_id')->nullable();
            $table->string('actual_color', 50)->nullable();
            $table->string('size', 50)->nullable();
            $table->string('size_value', 10)->nullable();
            $table->integer('sizes_id')->nullable();
            $table->integer('rma_uoms_id')->nullable();
            $table->integer('vendors_id')->nullable();
            $table->integer('vendor_types_id')->nullable();
            $table->integer('incoterms_id')->nullable();
            $table->integer('inventory_types_id')->nullable();
            $table->string('serialized', 50)->nullable();
            $table->tinyInteger('has_serial')->nullable();
            $table->tinyInteger('imei_code1')->nullable();
            $table->tinyInteger('imei_code2')->nullable();
            $table->integer('serialized_by')->nullable();
            $table->dateTime('serialized_at')->nullable();
            $table->integer('sku_statuses_id')->nullable();
            $table->integer('sku_legends_id')->nullable();
            $table->decimal('original_srp', 18, 2)->nullable();
            $table->decimal('current_srp', 18, 2)->nullable();
            $table->decimal('promo_srp', 18, 2)->nullable();
            $table->decimal('price_change', 18, 2)->nullable();
            $table->date('effective_date')->nullable();
            $table->decimal('moq', 18, 2)->nullable();
            $table->integer('currencies_id')->nullable();
            $table->decimal('purchase_price', 18, 2)->nullable();
            $table->decimal('cost_factor', 18, 2)->nullable();
            $table->decimal('store_cost', 18, 2)->nullable();
            $table->decimal('store_cost_percentage', 18, 2)->nullable();
            $table->decimal('consignment_store_cost', 18, 2)->nullable();
            $table->decimal('consignment_store_cost_percentage', 18, 2)->nullable();
            $table->decimal('landed_cost', 18, 2)->nullable();
            $table->decimal('working_landed_cost', 18, 2)->nullable();
            $table->decimal('working_store_cost', 18, 2)->nullable();
            $table->decimal('working_store_cost_percentage', 18, 2)->nullable();
            $table->integer('warranties_id')->nullable();
            $table->integer('warranty_duration')->nullable();
            $table->integer('approval_status')->nullable();
            $table->integer('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->date('initial_wrr_date')->nullable();
            $table->date('latest_wrr_date')->nullable();
            $table->integer('approver_privileges_id')->nullable();
            $table->integer('encoder_privileges_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rma_item_masters');
    }
};
