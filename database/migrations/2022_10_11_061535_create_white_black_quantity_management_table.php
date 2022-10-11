<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhiteBlackQuantityManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('white_black_quantity_management', function (Blueprint $table) {
            $table->id();
            $table->integer('sale_id');
            $table->integer('sale_item_id')->nullable();
            $table->integer('black_quantity');
            $table->integer('white_quantity');
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
        Schema::dropIfExists('white_black_quantity_management');
    }
}
