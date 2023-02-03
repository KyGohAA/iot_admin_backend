<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUtransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utransactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meter_register_id')->unsigned();
            $table->string('leaf_payment_id');
            $table->string('document_no');
            $table->date('document_date');
            $table->double('amount',8,2);
            $table->string('transaction_charge_percent');
            $table->double('transaction_charge',8,2);
            $table->string('transaction_charge_gst_percent');
            $table->double('transaction_charge_gst',8,2);
            $table->string('description');
            $table->boolean('is_paid');
            $table->integer('pay_by');
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
        Schema::dropIfExists('utransactions');
    }
}
