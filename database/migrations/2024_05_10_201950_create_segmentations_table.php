<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSegmentationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('segmentations', function (Blueprint $table) {
            $table->id();
            $table->string('segmentation_column',30)->unique();
            $table->string('segmentation_code',15)->unique();
            $table->string('segmentation_description',30)->unique();
            $table->string('import_header_name',30)->unique();
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
        Schema::dropIfExists('segmentations');
    }
}
