<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPhotographEtsyUrlColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photographs', function (Blueprint $table) {
            $table->text('etsy_url')->nullable()->after('redbubble_url');
            $table->text('ebay_url')->nullable()->after('etsy_url');
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
            $table->dropColumn('etsy_url');
            $table->dropColumn('ebay_url');
        });
    }
}
