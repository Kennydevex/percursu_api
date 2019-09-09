<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChargePartnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charge_partner', function (Blueprint $table) {
            $table->unsignedBigInteger('partner_id');
            $table->unsignedBigInteger('charge_id');

            $table->foreign('partner_id')->references('id')->on('partners')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('charge_id')->references('id')->on('charges')->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['partner_id', 'charge_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charge_partner');
    }
}
