<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateErpInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sale_id')->unsigned();
            $table->foreign('sale_id')->references('id')->on('new_sales')->onDelete('cascade');
            $table->string('date');
            $table->bigInteger('customer_id');
            $table->bigInteger('retailer_id');
            $table->enum('cash_type',['white','black']);
            $table->string('entire_vat')->default(0);
            $table->string('shipping_cost')->default(0);
            $table->string('document')->nullable();
            $table->string('sale_entire_total_exculding_vat')->default(0);
            $table->string('discount')->default(0);
            $table->string('tax_stamp')->nullable();
           
            $table->string('total_qty')->nullable();
            $table->string('total_bill')->nullable();
            $table->enum('status',['paid','unpaid'])->default('unpaid');
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
        Schema::dropIfExists('erp_invoices');
    }
}
