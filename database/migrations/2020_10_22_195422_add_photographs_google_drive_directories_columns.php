<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPhotographsGoogleDriveDirectoriesColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('google_drive_dir_edits')->nullable()->after('photograph_checklist');
            $table->text('google_drive_dir_raws')->nullable()->after('google_drive_dir_edits');
            $table->text('google_drive_dir_metas')->nullable()->after('google_drive_dir_raws');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google_drive_dir_edits');
            $table->dropColumn('google_drive_dir_raws');
            $table->dropColumn('google_drive_dir_metas');
        });
    }
}
