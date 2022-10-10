<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAfterMarkitSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('after_markit_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('retailer_id');
            $table->string('email')->unique();
            $table->string('image')->nullable();
            $table->string('phone')->unique();
            $table->string('shop_name')->unique();
            $table->longText('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->boolean('is_active')->nullable();
            // $table->foreign('retailer_id')->references('id')->on('users')->cascadeOnDelete();
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
        Schema::dropIfExists('after_markit_suppliers');
    }
}
