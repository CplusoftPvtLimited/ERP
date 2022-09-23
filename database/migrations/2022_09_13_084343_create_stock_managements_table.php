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
            
            // $table->dropColumn('product_id');
            // $table->dropColumn('purchase_product_id');
            // $table->dropColumn('retailer_id');
            // $table->dropColumn('reference_no');
            // $table->dropColumn('white_items');
            // $table->dropColumn('black_items');
            // $table->dropColumn('unit_actual_price');
            // $table->dropColumn('unit_sale_price');
            // $table->dropColumn('total_qty');
            // $table->foreign('purchase_product_id')->references('id')->on('product_purchases')->cascadeOnDelete();
            // $table->foreign('retailer_id')->references('id')->on('users');
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
