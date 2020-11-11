<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPhotographEditDimensionsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('photograph_edits', function (Blueprint $table) {
            $table->integer('original_width')->after('filetype');
            $table->integer('original_height')->after('original_width');
            $table->integer('scaled_width')->after('original_height');
            $table->integer('scaled_height')->after('scaled_width');
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
            $table->dropColumn('original_width');
            $table->dropColumn('original_height');
            $table->dropColumn('scaled_width');
            $table->dropColumn('scaled_height');
        });
    }
}
