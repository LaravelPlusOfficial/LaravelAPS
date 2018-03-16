<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('body')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('category_slug')->nullable();
            $table->string('post_type')->default('post');
            $table->string('post_format')->default('standard');
            $table->unsignedInteger('featured_image_id')->nullable();
            $table->dateTime('publish_at')->nullable();
            $table->timestamps();

            $table->unsignedInteger('author_id');
            $table->foreign('author_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}