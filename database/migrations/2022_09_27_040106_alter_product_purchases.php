<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductPurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_purchases', function (Blueprint $table) {
            
            $table->string('brand_id')->nullable();
            $table->string('additional_cost_without_vat')->nullable();
            $table->string('additional_cost_with_vat')->nullable();
            $table->string('vat')->nullable();
            $table->string('profit_margin')->nullable();
            $table->string('total_excluding_vat')->nullable();
            $table->string('actual_cost_per_product')->nullable();
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
