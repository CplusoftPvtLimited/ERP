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
            $table->bigInteger('purchase_product_id')->nullable();
            $table->bigInteger('retailer_id');
            $table->string('reference_no');
            $table->string('white_items')->nullable();
            $table->string('black_items')->nullable();
            $table->string('unit_actual_price')->nullable();
            $table->string('unit_sale_price')->nullable();
            $table->string('total_qty')->nullable();
            $table->string('discount')->nullable();
            $table->string('additional_cost_without_vat')->nullable();
            $table->string('additional_cost_with_vat')->nullable();
            $table->string('vat')->nullable();
            $table->string('profit_margin')->nullable();
            $table->string('total_excluding_vat')->nullable();
            $table->string('actual_cost_per_product')->nullable();
            
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
