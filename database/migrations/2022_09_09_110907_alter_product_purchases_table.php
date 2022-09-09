<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_purchases', function (Blueprint $table) {
            $table->integer('legacy_article_id')->nullable();
               $table->string('actual_price')->nullable();
               $table->string('sell_price')->nullable();
               $table->integer('manufacture_id')->nullable();
               $table->integer('supplier_id')->nullable();
               $table->integer('model_id')->nullable();
               $table->string('engine_details')->nullable();
               $table->integer('eng_linkage_target_id')->nullable();
               $table->integer('assembly_group_node_id')->nullable();
               $table->integer('black_item_qty')->nullable();
               $table->integer('white_item_qty')->nullable();

               $table->index('legacy_article_id');
               $table->index('manufacture_id');
               $table->index('model_id');
               $table->index('eng_linkage_target_id');
               $table->index('assembly_group_node_id');
               $table->index('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_purchases', function (Blueprint $table) {
            //
        });
    }
}
