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
        Schema::create('admin_classifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('admin_categories_id')->nullable();
            $table->string('class_code', 5)->nullable();
            $table->string('class_description', 50)->nullable();
            $table->enum('status',['ACTIVE','INACTIVE'])->nullable();
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
        Schema::dropIfExists('admin_classifications');
    }
};
