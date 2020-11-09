<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddUsersProfileColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('remember_token');
            $table->text('biography')->nullable()->after('date_of_birth');
            $table->text('photograph_checklist')->nullable()->after('biography');
            $table->boolean('active')->default(1)->after('photograph_checklist');
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
            $table->dropColumn('date_of_birth');
            $table->dropColumn('biography');
            $table->dropColumn('photograph_checklist');
            $table->dropColumn('active');
        });
    }
}
