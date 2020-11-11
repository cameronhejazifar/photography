<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFlickrPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flickr_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('flickr_oauth_id')->index();
            $table->unsignedBigInteger('photograph_id')->index();
            $table->string('flickr_photo_id')->nullable();
            $table->text('image_path')->nullable();
            $table->string('title')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->text('tags')->nullable();
            $table->boolean('is_public')->nullable();
            $table->boolean('is_friend')->nullable();
            $table->boolean('is_family')->nullable();
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
        Schema::dropIfExists('flickr_posts');
    }
}
