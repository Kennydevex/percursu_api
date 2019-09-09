<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('status')->default(false);
            $table->boolean('featured')->default(false);
            $table->boolean('promo')->default(false);
            $table->unsignedBigInteger('folk_id');
            $table->timestamps();
            $table->foreign('folk_id')->references('id')->on('folks')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partners');
    }
}
