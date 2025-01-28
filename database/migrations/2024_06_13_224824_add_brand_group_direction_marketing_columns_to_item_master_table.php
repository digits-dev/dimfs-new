<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBrandGroupDirectionMarketingColumnsToItemMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_masters', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_groups_id')->nullable()->after('brands_id');
            $table->unsignedBigInteger('brand_directions_id')->nullable()->after('brand_groups_id');
            $table->unsignedBigInteger('brand_marketings_id')->nullable()->after('brand_directions_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_masters', function (Blueprint $table) {
            $table->dropColumn('brand_groups_id');
            $table->dropColumn('brand_directions_id');
            $table->dropColumn('brand_marketings_id');
        });
    }
}
