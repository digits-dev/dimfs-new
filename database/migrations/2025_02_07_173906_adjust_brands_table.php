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
        Schema::table('brands', function (Blueprint $table) {
            $table->renameColumn('brand_group', 'brand_groups_id');
        });

        Schema::table('brands', function (Blueprint $table) {

            $table->bigInteger('brand_groups_id')->nullable()->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->string('brand_groups_id', 50)->change();

            $table->renameColumn('brand_groups_id', 'brand_group');
        });
    }
};
