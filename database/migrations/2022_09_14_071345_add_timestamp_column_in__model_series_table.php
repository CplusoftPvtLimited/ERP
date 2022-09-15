<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampColumnInModelSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modelseries', function (Blueprint $table) {
            $table->timestamp('created_at')->default(Null)->nullable();
            $table->timestamp('updated_at')->default(Null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('_model_series', function (Blueprint $table) {
            //
        });
    }
}
