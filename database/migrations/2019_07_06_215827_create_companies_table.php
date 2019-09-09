<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->unique();
            $table->mediumText('slogan')->nullable();
            $table->longText('presentation')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover')->nullable();
            $table->date('start')->nullable();
            $table->boolean('status')->nullable()->default(false);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('companies');
    }
}
