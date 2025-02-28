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
        Schema::create('rma_item_master_approvals', function (Blueprint $table) {
            $table->id();
            $table->json('item_values')->nullable();
            $table->string('action', 20)->nullable();
            $table->integer('rma_item_master_id')->nullable();
            $table->string('status', 20)->nullable()->default('FOR APPROVAL');
            $table->integer('approved_by')->nullable();
            $table->integer('rejected_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rma_item_master_approvals');
    }
};