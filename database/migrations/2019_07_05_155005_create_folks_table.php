<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFolksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('lastname', 100);
            $table->enum('gender', ['m', 'f', 'nd'])->nullable();
            $table->string('avatar', 100)->nullable()->default('default.svg');
            $table->string('cover', 100)->nullable()->default('default.gif');
            $table->string('ic', 50)->nullable()->unique();
            $table->string('nif', 50)->nullable()->unique();
            $table->date('birthdate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('folks');
    }
}
