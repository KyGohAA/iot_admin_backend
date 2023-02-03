<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeterRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meter_registers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_no');
            $table->string('contract_no');
            $table->string('ip_address');
            $table->string('meter_id');
            $table->integer('meter_class_id')->unsigned();
            $table->integer('house_id')->unsigned();
            $table->integer('utility_charge_id')->unsigned();
            $table->double('deposit',8,2);
            $table->string('billing_address1');
            $table->string('billing_address2');
            $table->string('billing_postcode');
            $table->string('billing_city_id');
            $table->string('billing_state_id');
            $table->string('billing_country_id');
            $table->boolean('status');
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
        Schema::dropIfExists('meter_registers');
    }
}
