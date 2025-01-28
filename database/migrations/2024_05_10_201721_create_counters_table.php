<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counters', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('cms_moduls_id');
            $table->string('module_name',50)->unique();
            $table->unsignedBigInteger('code_1');
            $table->unsignedBigInteger('code_2');
            $table->unsignedBigInteger('code_3');
            $table->unsignedBigInteger('code_4');
            $table->unsignedBigInteger('code_5');
            $table->unsignedBigInteger('code_6');
            $table->unsignedBigInteger('code_7');
            $table->unsignedBigInteger('code_8');
            $table->unsignedBigInteger('code_9');
            $table->string('status',10)->default('ACTIVE');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
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
        Schema::dropIfExists('counters');
    }
}
