<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFlickrOauthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flickr_oauths', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('flickr_nsid')->nullable()->comment('Flickr User ID');
            $table->string('flickr_name')->nullable();
            $table->string('flickr_username')->nullable();
            $table->string('request_token')->nullable();
            $table->string('request_token_secret')->nullable();
            $table->string('request_token_verifier')->nullable();
            $table->string('access_token')->nullable();
            $table->string('access_token_secret')->nullable();
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
        Schema::dropIfExists('flickr_oauths');
    }
}
