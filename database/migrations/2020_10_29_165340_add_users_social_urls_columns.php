<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddUsersSocialUrlsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('nixplay_url')->nullable()->after('google_drive_dir_metas');
            $table->text('fineartamerica_url')->nullable()->after('nixplay_url');
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
            $table->dropColumn('nixplay_url');
            $table->dropColumn('fineartamerica_url');
        });
    }
}
