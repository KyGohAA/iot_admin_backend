<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeterInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meter_invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meter_invoice_id')->unsigned();
            $table->string('meter_started');
            $table->string('meter_ended');
            $table->string('max_meter');
            $table->string('total_meter');
            $table->double('unit_price',8,2);
            $table->double('total_price',8,2);
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
        Schema::dropIfExists('meter_invoice_items');
    }
}
