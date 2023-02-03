<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUtilityChargeItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utility_charge_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('utility_charge_id');
            $table->string('started');
            $table->string('ended');
            $table->boolean('is_gst');
            $table->double('unit_price',8,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utility_charge_items');
    }
}
