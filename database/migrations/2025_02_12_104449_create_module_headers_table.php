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
        Schema::create('module_headers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('module_id')->nullable();
            $table->string('name', 50)->nullable();
            $table->string('header_name')->nullable();
            $table->string('width')->nullable();
            $table->string('status',10)->default('ACTIVE');
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
        Schema::dropIfExists('module_headers');
    }
};
