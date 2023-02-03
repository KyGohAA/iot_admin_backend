<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateARInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ar_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->string('customer_name');
            $table->string('contact_person');
            $table->string('phone_no');
            $table->string('po_no');
            $table->integer('currency_id')->unsigned();
            $table->string('currency_code');
            $table->string('currency_rate');
            $table->string('document_no');
            $table->date('document_date');
            $table->integer('payment_term_id')->unsigned();
            $table->date('payment_term_code');
            $table->integer('payment_term_days');
            $table->date('due_date');
            $table->string('status');
            $table->string('sales_person');
            $table->boolean('is_tax_inclusive');
            $table->string('billing_address1');
            $table->string('billing_address2');
            $table->string('billing_postcode');
            $table->integer('billing_country_id')->unsigned();
            $table->integer('billing_state_id')->unsigned();
            $table->integer('billing_city_id')->unsigned();
            $table->string('delivery_address1');
            $table->string('delivery_address2');
            $table->string('delivery_postcode');
            $table->integer('delivery_country_id')->unsigned();
            $table->integer('delivery_state_id')->unsigned();
            $table->integer('delivery_city_id')->unsigned();
            $table->double('amount',20,4);
            $table->double('gst_amount',20,4);
            $table->double('total_amount',20,4);
            $table->text('remark');
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
        Schema::dropIfExists('ar_invoices');
    }
}
