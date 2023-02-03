<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('resource_name');
            $table->string('resource_label');
            $table->string('resource_description');
            $table->string('resource_controller');
            $table->string('resource_action');
            $table->string('resource_seq');
            $table->boolean('resource_status');
            $table->boolean('umrah');
            $table->boolean('billing');
            $table->boolean('power_meter');
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
        Schema::dropIfExists('resources');
    }
}
