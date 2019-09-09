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
            $table->bigIncrements('id');
            $table->string('slug')->nullable();
            $table->string('title', 100)->unique();
            $table->mediumText('summary');
            $table->longText('body');
            $table->string('image', 100)->nullable()->default('default.svg');
            $table->boolean('published')->nullable()->default(false);
            $table->boolean('featured')->nullable()->default(false);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();


            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
