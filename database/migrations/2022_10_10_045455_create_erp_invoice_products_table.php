<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateErpInvoiceProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_invoice_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice_id')->unsigned();
            $table->foreign('invoice_id')->references('id')->on('erp_invoices')->onDelete('cascade');
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
        Schema::dropIfExists('erp_invoice_products');
    }
}
