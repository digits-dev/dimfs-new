<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_masters', function (Blueprint $table) {
            $table->id();
            $table->date('initial_wrr_date')->nullable();
            $table->date('latest_wrr_date')->nullable();
            $table->string('digits_code',8)->nullable();
            $table->string('upc_code',60)->nullable();
            $table->string('upc_code2',60)->nullable();
            $table->string('upc_code3',60)->nullable();
            $table->string('upc_code4',60)->nullable();
            $table->string('upc_code5',60)->nullable();
            $table->string('supplier_item_code',60)->nullable();
            $table->string('item_description',100)->nullable();
            $table->unsignedInteger('brands_id')->nullable();
            $table->unsignedInteger('categories_id')->nullable();
            $table->unsignedInteger('classifications_id')->nullable();
            $table->unsignedInteger('sub_classifications_id')->nullable();
            $table->unsignedInteger('store_categories_id')->nullable();
            $table->unsignedInteger('margin_categories_id')->nullable();
            $table->unsignedInteger('warehouse_categories_id')->nullable();
            $table->string('model',50)->nullable();
            $table->unsignedInteger('year_launch')->nullable();
            $table->unsignedInteger('model_specifics_id')->nullable();
            $table->unsignedInteger('colors_id')->nullable();
            $table->string('actual_color',50)->nullable();
            $table->unsignedInteger('vendors_id')->nullable();
            $table->unsignedInteger('vendor_types_id')->nullable();
            $table->unsignedInteger('incoterms_id')->nullable();
            $table->unsignedInteger('inventory_types_id')->nullable();
            $table->unsignedInteger('sku_statuses_id')->nullable();
            $table->unsignedInteger('sku_legends_id')->nullable();
            $table->unsignedInteger('currencies_id')->nullable();
            $table->unsignedInteger('warranties_id')->nullable();

            $table->decimal('original_srp', 18, 2)->nullable();
            $table->decimal('current_srp', 18, 2)->nullable();
            $table->decimal('promo_srp', 18, 2)->nullable();
            $table->decimal('price_change', 18, 2)->nullable();
            $table->date('effective_date')->nullable();
            $table->decimal('moq', 18, 2)->nullable();
            $table->decimal('purchase_price', 18, 2)->nullable()->comment('supplier cost');
            $table->decimal('dtp_rf', 18, 2)->nullable()->comment('store cost');
            $table->decimal('dtp_rf_percentage', 18, 2)->nullable()->comment('store margin');
            $table->decimal('dtp_dcon', 18, 2)->nullable();
            $table->decimal('dtp_dcon_percentage', 18, 2)->nullable();
            $table->decimal('landed_cost', 18, 2)->nullable();
            $table->decimal('working_landed_cost', 18, 2)->nullable();
            $table->decimal('working_dtp_rf', 18, 2)->nullable()->comment('working store cost');
            $table->decimal('working_dtp_rf_percentage', 18, 2)->nullable()->comment('working store margin');

            $table->unsignedInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->unsignedInteger('approved_by_acctg')->nullable();
            $table->dateTime('approved_at_acctg')->nullable();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_masters');
    }
}
