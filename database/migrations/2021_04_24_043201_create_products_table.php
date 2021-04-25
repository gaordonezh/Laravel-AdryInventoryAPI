<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->unsignedBigInteger('id_category');
            $table->foreign('id_category')->references('id')->on('categories');
            $table->unsignedBigInteger('id_measurement_unit');
            $table->foreign('id_measurement_unit')->references('id')->on('measurement_units');
            $table->string('name', 50)->unique();
            $table->string('description')->nullable();
            $table->integer('stock');
            $table->double('unit_sale_price', 8,2);
            $table->double('unit_purchase_price', 8,2);
            $table->boolean('status');
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
        Schema::dropIfExists('products');
    }
}
