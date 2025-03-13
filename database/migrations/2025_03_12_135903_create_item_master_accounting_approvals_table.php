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
        Schema::create('item_master_accounting_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('FOR APPROVAL');
            $table->integer('item_masters_id')->nullable();
            $table->integer('brands_id')->nullable();
            $table->integer('categories_id')->nullable();
            $table->integer('margin_categories_id')->nullable();
            $table->integer('support_types_id')->nullable();
            $table->decimal('current_srp', 18, 2)->nullable();
            $table->decimal('promo_srp', 18, 2)->nullable();
            $table->decimal('store_cost', 18, 2)->nullable();
            $table->decimal('store_cost_percentage', 18, 2)->nullable();
            $table->decimal('ecom_store_cost', 18, 2)->nullable();
            $table->decimal('ecom_store_cost_percentage', 18, 2)->nullable();
            $table->decimal('landed_cost', 18, 2)->nullable();
            $table->decimal('landed_cost_sea', 18, 2)->nullable();
            $table->decimal('actual_landed_cost', 18, 2)->nullable();
            $table->decimal('working_store_cost', 18, 2)->nullable();
            $table->decimal('working_store_cost_percentage', 18, 2)->nullable();
            $table->decimal('ecom_working_store_cost', 18, 2)->nullable();
            $table->decimal('ecom_working_store_cost_percentage', 18, 2)->nullable();
            $table->decimal('working_landed_cost', 18, 2)->nullable();
            $table->dateTime('effective_date')->nullable();
            $table->dateTime('duration_from')->nullable();
            $table->dateTime('duration_to')->nullable();
            $table->integer('encoder_privileges_id')->nullable();
            $table->integer('approver_privileges_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('rejected_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_master_accounting_approvals');
    }
};
