<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhotographOtherFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photograph_other_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('photograph_id')->index();
            $table->enum('other_type', ['raw', 'meta']);
            $table->string('filename');
            $table->string('filetype');
            $table->string('google_drive_file_id');
            $table->string('camera')->nullable();
            $table->string('lens')->nullable();
            $table->string('filter')->nullable();
            $table->string('focal_length')->nullable();
            $table->string('exposure_time')->nullable();
            $table->string('aperture')->nullable();
            $table->string('iso')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photograph_other_files');
    }
}
