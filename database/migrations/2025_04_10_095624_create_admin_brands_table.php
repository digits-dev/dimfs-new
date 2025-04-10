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
        Schema::create('admin_brands', function (Blueprint $table) {
            $table->id();
            $table->string('brand_code', 5)->nullable();
            $table->string('brand_description', 30)->nullable();
            $table->string('brand_beacode', 10)->nullable();
            $table->unsignedInteger('admin_brand_types_id')->nullable();
            $table->enum('status',['ACTIVE','INACTIVE','STATUS QUO','CORE'])->nullable();
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
        Schema::dropIfExists('admin_brands');
    }
};
