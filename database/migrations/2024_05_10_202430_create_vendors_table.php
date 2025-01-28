<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brands_id');
            $table->string('vendor_name');
            $table->unsignedBigInteger('vendor_types_id');
            $table->unsignedBigInteger('incoterms_id');
            $table->string('status',10)->default('ACTIVE');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('brands_id')->references('id')->on('brands');
            $table->foreign('vendor_types_id')->references('id')->on('vendor_types');
            $table->foreign('incoterms_id')->references('id')->on('incoterms');
            $table->unique(['brands_id', 'vendor_name','vendor_types_id','incoterms_id'],'vendor_unique_combination');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
