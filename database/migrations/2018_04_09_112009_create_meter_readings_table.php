<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeterReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meter_readings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meter_register_id');
            $table->date('date');
            $table->time('time_started');
            $table->time('time_ended');
            $table->string('last_meter_reading');
            $table->string('current_meter_reading');
            $table->string('current_usage');
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
        Schema::dropIfExists('meter_readings');
    }
}
