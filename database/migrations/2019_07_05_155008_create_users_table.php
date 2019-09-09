<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) 
        {
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->boolean('status')->nullable()->default(false);
            $table->unsignedBigInteger('folk_id');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();


            $table->foreign('folk_id')->references('id')->on('folks')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
