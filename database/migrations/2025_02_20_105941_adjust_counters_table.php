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
        Schema::table('counters', function (Blueprint $table) {
            $table->dropColumn([
                'code_1', 'code_2', 'code_3', 'code_4', 'code_5', 
                'code_6', 'code_7', 'code_8', 'code_9', 'module_name'
            ]);
            $table->renameColumn('cms_moduls_id', 'adm_module_id');

        });

        Schema::table('counters', function (Blueprint $table) {
            $table->bigInteger('counter_code')->after('adm_module_id');
            $table->string('code_identifier', 50)->after('counter_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('counters', function (Blueprint $table) {
            $table->dropColumn('counter_code');
            $table->dropColumn('code_identifier');
            $table->renameColumn('adm_module_id', 'cms_moduls_id');
        });

        Schema::table('counters', function (Blueprint $table) {
            $table->string('module_name', 50)->unique()->nullable()->after('cms_moduls_id');
            $table->bigInteger('code_1')->nullable()->after('module_name');
            $table->bigInteger('code_2')->nullable()->after('code_1');
            $table->bigInteger('code_3')->nullable()->after('code_2');
            $table->bigInteger('code_4')->nullable()->after('code_3');
            $table->bigInteger('code_5')->nullable()->after('code_4');
            $table->bigInteger('code_6')->nullable()->after('code_5');
            $table->bigInteger('code_7')->nullable()->after('code_6');
            $table->bigInteger('code_8')->nullable()->after('code_7');
            $table->bigInteger('code_9')->nullable()->after('code_8');
        });
    }
};
