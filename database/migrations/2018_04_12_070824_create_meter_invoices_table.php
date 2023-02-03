<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeterInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meter_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('document_no');
            $table->date('last_invoice_date');
            $table->date('document_date');
            $table->integer('meter_register_id')->unsigned();
            $table->string('last_meter_reading');
            $table->string('current_meter_reading');
            $table->string('over_due_amount');
            $table->double('current_amount',8,2);
            $table->double('total_amount',8,2);
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned();
            $table->timestamps();
            $table->integer('leaf_group_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meter_invoices');
    }
}
