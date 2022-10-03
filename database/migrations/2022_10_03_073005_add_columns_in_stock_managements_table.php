<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInStockManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_managements', function (Blueprint $table) {
            $table->string('unit_purchase_price_of_white_cash')->nullable();
            $table->string('unit_sale_price_of_white_cash')->nullable();
            $table->string('unit_purchase_price_of_black_cash')->nullable();
            $table->string('unit_sale_price_of_black_cash')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_managements', function (Blueprint $table) {
            //
        });
    }
}
