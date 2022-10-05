<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_sales', function (Blueprint $table) {
            $table->id();
            $table->integer('retailer_id');
            $table->bigInteger('customer_id');
            $table->enum('cash_type',['white','black']);
            $table->string('vat')->default(0);
            $table->string('shipping_cost')->default(0);
            $table->string('document')->nullable();
            $table->string('discount')->default(0);
            $table->string('tax_stamp')->nullable();
            $table->string('sale_note')->nullable();
            $table->string('staff_note')->nullable();
            $table->string('total_bill')->nullable();
            $table->enum('status',['estimate','negotiation','accept','cancelled'])->default('estimate');
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
        Schema::dropIfExists('new_sales');
    }
}
