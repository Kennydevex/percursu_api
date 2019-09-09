<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('street', 50);
            $table->string('city', 50);
            $table->string('country', 50)->default('Cabo Verde');
            $table->string('postcode', 10);
            $table->unsignedBigInteger('location_id');
           $table->unsignedBigInteger('folk_id');

            $table->foreign('folk_id')->references('id')->on('folks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->onUpdate('cascade')->on('locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
