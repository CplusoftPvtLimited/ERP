<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropPriceColumnsInStockManagements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_managements', function (Blueprint $table) {
            $table->dropColumn('unit_actual_price');
            $table->dropColumn('unit_sale_price');
            // $table->dropColumn('unit_white_cash_sale_price');
            // $table->dropColumn('unit_white_cash_sale_price');
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
