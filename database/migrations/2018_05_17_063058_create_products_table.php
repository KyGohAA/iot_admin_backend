<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('name');
            $table->integer('product_category_id')->unsigned();
            $table->string('barcode');
            $table->string('lead_time');
            $table->integer('sale_tax_id')->unsigned();
            $table->integer('purchase_tax_id')->unsigned();
            $table->string('cost_method');
            $table->boolean('is_obsolete');
            $table->integer('uom_id')->unsigned();
            $table->double('selling_price',12,4);
            $table->double('purchase_price',12,4);
            $table->double('standard_cost',12,4);
            $table->string('min_quantity');
            $table->string('max_quantity');
            $table->string('reorder_quantity');
            $table->text('remark');
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
        Schema::dropIfExists('products');
    }
}
