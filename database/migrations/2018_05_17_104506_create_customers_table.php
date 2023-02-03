<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('name');
            $table->string('registration_no');
            $table->string('gst_no');
            $table->integer('currency_id')->unsigned();
            $table->integer('customer_group_id')->unsigned();
            $table->integer('payment_term_id')->unsigned();
            $table->string('sales_person');
            $table->double('credit_limit',16,4);
            $table->boolean('is_suspend');
            $table->string('contact_person');
            $table->string('phone_no_1');
            $table->string('phone_no_2');
            $table->string('fax_no');
            $table->string('email');
            $table->string('website');
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
            $table->text('remark');
            $table->boolean('status');
            $table->integer('created_by')->unsigned();
            $table->time('created_at');
            $table->time('updated_by');
            $table->integer('updated_at')->unsigned();
            $table->timestamps();
            $table->integer('leaf_group_id')->unsigned();
            $table->integer('ncl_id')->unsigned();
            $table->integer('id_house_member')->unsigned();
            $table->integer('acc_id_customer')->unsigned();
            $table->integer('id_house')->unsigned();
            $table->integer('leaf_id_user')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
