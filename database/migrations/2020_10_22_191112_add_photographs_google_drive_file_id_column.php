<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPhotographsGoogleDriveFileIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photographs', function (Blueprint $table) {
            $table->string('google_drive_file_id')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('photographs', function (Blueprint $table) {
            $table->dropColumn('google_drive_file_id');
        });
    }
}
