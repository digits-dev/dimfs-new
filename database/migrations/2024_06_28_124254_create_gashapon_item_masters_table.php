<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGashaponItemMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gashapon_item_masters', function (Blueprint $table) {
            $table->id();
            $table->json('for_approval')->nullable();
            $table->unsignedInteger('approval_status')->nullable();
            $table->unsignedInteger('acctg_approval_status')->nullable();
            $table->string('jan_no',150)->nullable();
            $table->string('digits_code',10)->nullable();
            $table->string('item_no',150)->nullable();
            $table->string('sap_no',150)->nullable();
            $table->date('initial_wrr_date')->nullable();
            $table->date('latest_wrr_date')->nullable();
            $table->string('item_description')->nullable();
            $table->string('model_description',150)->nullable();
            $table->unsignedInteger('gashapon_brands_id')->nullable()->index('gashapon_brands_id_index');
            $table->unsignedInteger('gashapon_categories_id')->nullable()->index('gashapon_categories_id_index');
            $table->unsignedInteger('gashapon_product_types_id')->nullable()->index('gashapon_product_types_id_index');
            $table->unsignedInteger('gashapon_incoterms_id')->nullable()->index('gashapon_incoterms_id_index');
            $table->unsignedInteger('gashapon_uoms_id')->nullable()->index('gashapon_uoms_id_index');
            $table->unsignedInteger('gashapon_warehouse_categories_id')->nullable()->index('gashapon_warehouse_categories_id_index');
            $table->unsignedInteger('gashapon_inventory_types_id')->nullable()->index('gashapon_inventory_types_id_index');
            $table->unsignedInteger('gashapon_vendor_types_id')->nullable()->index('gashapon_vendor_types_id_index');
            $table->unsignedInteger('gashapon_vendor_groups_id')->nullable()->index('gashapon_vendor_groups_id_index');
            $table->unsignedInteger('gashapon_countries_id')->nullable()->index('gashapon_countries_id_index');
            $table->unsignedInteger('gashapon_sku_statuses_id')->nullable()->index('gashapon_sku_statuses_id_index');

            $table->decimal('msrp', 18, 2)->unsigned()->nullable();
            $table->decimal('current_srp', 18, 2)->unsigned()->nullable();
            $table->unsignedInteger('no_of_tokens')->nullable();
            $table->decimal('store_cost', 18, 2)->unsigned()->nullable();
            $table->decimal('sc_margin', 18, 2)->nullable();
            $table->decimal('lc_per_pc', 18, 2)->unsigned()->nullable();
            $table->decimal('lc_margin_per_pc', 18, 2)->nullable();
            $table->decimal('lc_per_carton', 18, 2)->unsigned()->nullable();
            $table->decimal('lc_margin_per_carton', 18, 2)->nullable();
            $table->unsignedInteger('dp_ctn')->nullable();
            $table->unsignedInteger('pcs_dp')->nullable();
            $table->decimal('moq', 18, 2)->unsigned()->nullable();
            $table->unsignedInteger('pcs_ctn')->nullable();
            $table->unsignedInteger('no_of_ctn')->nullable();
            $table->unsignedInteger('no_of_assort')->nullable();
            $table->unsignedInteger('currencies_id')->nullable()->index('currencies_id_index');
            $table->decimal('supplier_cost', 18, 2)->unsigned()->nullable();

            $table->string('status',10)->default('ACTIVE');
            $table->string('age_grade',10)->nullable();
            $table->string('battery',50)->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedInteger('approved_by_acctg')->nullable();
            $table->timestamp('approved_at_acctg')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gashapon_item_masters');
    }
}
