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

            //    $table->bigInteger('purchase_id');

               $table->integer('legacy_article_id')->nullable();
               $table->string('actual_price')->nullable();
               $table->string('sell_price')->nullable();
               $table->integer('manufacture_id')->nullable();
               $table->integer('supplier_id')->nullable();
               $table->integer('model_id')->nullable();
               $table->string('engine_details')->nullable();
               $table->integer('eng_linkage_target_id')->nullable();
               $table->integer('assembly_group_node_id')->nullable();
               $table->enum('linkage_target_type',['P','O'])->nullable();
               $table->enum('linkage_target_sub_type',['V','L','B','C','T','M','A','K'])->nullable();  
               $table->string('additional_cost')->nullable();
               $table->enum('cash_type',['white','black'])->nullable();
               
            //    $table->dropColumn('legacy_article_id');
            //    $table->dropColumn('actual_price');
            //    $table->dropColumn('sell_price');
            //    $table->dropColumn('manufacture_id');
            //    $table->dropColumn('supplier_id');
            //    $table->dropColumn('model_id');
            //    $table->dropColumn('engine_details');
            //    $table->dropColumn('eng_linkage_target_id');
            //    $table->dropColumn('assembly_group_node_id');
            //    $table->dropColumn('black_item_qty');
            //    $table->dropColumn('white_item_qty');
            //    $table->dropColumn('linkage_target_type');
            //    $table->dropColumn('linkage_target_sub_type');
            //    $table->dropColumn('additional_cost');
            //    $table->dropColumn('cash_type');
            //    $table->dropColumn('purchase_id');

            //    $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
              
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
