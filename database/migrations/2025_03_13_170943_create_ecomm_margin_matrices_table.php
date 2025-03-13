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
        Schema::create('ecomm_margin_matrices', function (Blueprint $table) {
            $table->id();
            $table->integer('brands_id')->nullable();
            $table->string('margin_category', 50)->nullable();
            $table->text('margin_categories_id')->nullable();
            $table->unsignedInteger('vendor_types_id')->nullable();
            $table->enum('matrix_type', ['BASED ON MATRIX', 'ADD TO LC', 'DEDUCT FROM'])->default('BASED ON MATRIX');
            $table->decimal('max', 8, 4)->nullable();
            $table->decimal('min', 8, 4)->nullable();
            $table->decimal('store_margin_percentage', 8, 4)->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE');
            $table->unsignedInteger('created_by');
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
        Schema::dropIfExists('ecomm_margin_matrices');
    }
};
