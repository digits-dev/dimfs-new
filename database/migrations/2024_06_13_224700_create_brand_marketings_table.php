<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandMarketingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_marketings', function (Blueprint $table) {
            $table->id();
            $table->string('brand_marketing_description',50)->nullable();
            $table->string('status', 15)->nullable()->default('ACTIVE');
            $table->unsignedInteger('created_by')->length(10)->nullable();
            $table->unsignedInteger('updated_by')->length(10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brand_marketings');
    }
}
