<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_managements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->bigInteger('retailer_id');
            $table->string('reference_no');
            $table->string('white_items');
            $table->string('black_items');
            $table->string('unit_actual_price');
            $table->string('unit_sale_price');
            $table->string('total_qty');
            $table->foreign('product_id')->references('LegacyArticleId')->on('articles');
            $table->foreign('retailer_id')->references('id')->on('users');
            $table->softDeletes();
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
        Schema::dropIfExists('stock_managements');
    }
}
