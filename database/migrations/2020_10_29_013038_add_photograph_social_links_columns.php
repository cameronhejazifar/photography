<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPhotographSocialLinksColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photographs', function (Blueprint $table) {
            $table->text('instagram_url')->nullable()->after('google_drive_file_id');
            $table->text('fineartamerica_url')->nullable()->after('instagram_url');
            $table->text('redbubble_url')->nullable()->after('fineartamerica_url');
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
            $table->dropColumn('instagram_url');
            $table->dropColumn('fineartamerica_url');
            $table->dropColumn('redbubble_url');
        });
    }
}
