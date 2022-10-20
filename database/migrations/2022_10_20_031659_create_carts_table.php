<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_no');
            $table->date('date');
            $table->integer('warehouse_id');
            $table->integer('retailer_id');
            $table->integer('supplier_id')->nullable();
            $table->integer('item');
            $table->integer('total_qty');
            $table->string('total_discount');
            $table->string('total_tax');
            $table->string('total_cost');
            $table->string('total_vat')->nullable();
            $table->string('tax_stamp')->nullable();
            $table->string('total_exculding_vat')->nullable();
            $table->string('order_tax_rate')->nullable();
            $table->string('order_tax')->nullable();
            $table->string('order_discount')->nullable();
            $table->string('shipping_cost')->nullable();
            $table->string('grand_total');
            $table->string('paid_amount');
            $table->integer('status');
            $table->integer('payment_status');
            $table->string('document')->nullable();
            $table->text('note')->nullable();
            $table->integer('legacy_article_id')->nullable();
            $table->string('actual_price')->nullable();
            $table->string('sell_price')->nullable();
            $table->integer('manufacture_id')->nullable();
            $table->integer('model_id')->nullable();
            $table->string('engine_details')->nullable();
            $table->integer('eng_linkage_target_id')->nullable();
            $table->integer('assembly_group_node_id')->nullable();
            $table->string('additional_cost')->nullable();
            $table->enum('cash_type',['white','black'])->nullable(); 
        
            
            $table->index('legacy_article_id');
            $table->index('manufacture_id');
            $table->index('model_id');
            $table->index('eng_linkage_target_id');
            $table->index('assembly_group_node_id');
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
        Schema::dropIfExists('carts');
    }
}
