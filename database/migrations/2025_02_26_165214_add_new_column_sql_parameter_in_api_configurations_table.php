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
        Schema::table('api_configurations', function (Blueprint $table) {
            //
            $table->longText('sql_parameter')->nullable()->after('rules');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_configurations', function (Blueprint $table) {
            //
            $table->dropColumn('sql_parameter');
        });
    }
};
