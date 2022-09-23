<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
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
            //    $table->index('supplier_id');
           
             //    $table->dropColumn('legacy_article_id');
            //    $table->dropColumn('actual_price');
            //    $table->dropColumn('sell_price');
            //    $table->dropColumn('manufacture_id');
            //    $table->dropColumn('model_id');
            //    $table->dropColumn('engine_details');
            //    $table->dropColumn('eng_linkage_target_id');
            //    $table->dropColumn('assembly_group_node_id');
            //    $table->dropColumn('additional_cost');
            //    $table->dropColumn('cash_type');
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
