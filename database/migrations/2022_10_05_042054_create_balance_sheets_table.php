<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalanceSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_sheets', function (Blueprint $table) {
            $table->id();
            $table->enum('transaction_type',['debit','credit'])->default('credit');
            $table->enum('mode_payment',['cheque','cash','draft','withholding'])->default('cash');
            $table->bigInteger('retailer_id')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('supplier_id')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->string('amount');
            $table->timestamp('settlement_date')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->string('cheque_number')->nullable();
            $table->string('draft_number')->nullable();
            $table->enum('balance_type',['primary','secondary'])->default('primary');
            $table->bigInteger('account_source')->nullable();
            $table->string('carrier')->nullable();
            $table->bigInteger('bank_id')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('balance_sheets');
    }
}
