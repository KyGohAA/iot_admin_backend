<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateARInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ar_invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ar_invoice_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('product_name');
            $table->string('product_description');
            $table->string('quantity');
            $table->string('uom');
            $table->integer('tax_id')->unsigned();
            $table->string('tax_percent');
            $table->string('tax_percent_txt');
            $table->string('unit_price');
            $table->string('discount');
            $table->string('discount_txt');
            $table->string('total_exclu_tax');
            $table->string('total_inclu_tax');
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
        Schema::dropIfExists('ar_invoice_items');
    }
}
