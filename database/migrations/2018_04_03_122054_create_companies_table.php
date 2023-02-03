<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('address');
            $table->string('postcode');
            $table->integer('city_id')->unsigned();
            $table->integer('state_id')->unsigned();
            $table->integer('country_id')->unsigned();
            $table->string('contact_person');
            $table->string('tel');
            $table->string('mobile');
            $table->string('email');
            $table->string('website');
            $table->boolean('is_min_credit');
            $table->integer('due_date_duration');
            $table->boolean('is_prepaid');
            $table->boolean('is_inclusive');
            $table->double('is_transaction_charge',8,2);
            $table->string('transaction_percent');
            $table->double('min_credit',8,2);
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
        Schema::dropIfExists('companies');
    }
}
