<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewSaleProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_sale_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sale_id')->unsigned();
            $table->foreign('sale_id')->references('id')->on('new_sales')->onDelete('cascade');
            $table->string('reference_no');
            $table->integer('quantity');
            $table->string('sale_price');
            $table->integer('discount')->default(0);
            $table->integer('vat');
            $table->string('total_with_discount')->nullable();
            $table->string('total_without_discount')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_sale_products');
    }
}
