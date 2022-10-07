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
            $table->timestamp('date');
            $table->bigInteger('customer_id');
            $table->bigInteger('retailer_id');
            $table->enum('cash_type',['white','black']);
            $table->string('entire_vat')->default(0);
            $table->string('shipping_cost')->default(0);
            $table->string('document')->nullable();
            $table->string('discount')->default(0);
            $table->string('tax_stamp')->nullable();
            $table->string('sale_note')->nullable();
            $table->string('staff_note')->nullable();
            $table->string('total_qty')->nullable();
            $table->string('total_bill')->nullable();
            $table->enum('status',['created','negotiation','accepted','cancelled','reactivated'])->default('created');
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
