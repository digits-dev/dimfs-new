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
        Schema::create('table_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('adm_privileges_id')->nullable();
            $table->unsignedInteger('adm_moduls_id')->nullable();
            $table->unsignedInteger('action_types_id')->nullable();
            $table->string('table_name', 50)->nullable();
            $table->longText('report_header')->nullable();
            $table->longText('report_query')->nullable();
            $table->string('status', 10)->default('ACTIVE');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_settings');
    }
};
