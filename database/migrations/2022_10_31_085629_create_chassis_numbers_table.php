<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChassisNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chassis_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('CHASSIS', 255)->nullable();
            $table->string('DAT_V', 255)->nullable();
            $table->string('DMC', 255)->nullable();
            $table->string('PUISSANCE', 255)->nullable();
            $table->string('ENERGIE', 255)->nullable();
            $table->string('CAR', 255)->nullable();
            $table->string('PTAC', 255)->nullable();
            $table->string('PTRA', 255)->nullable();
            $table->string('PVID', 255)->nullable();
            $table->string('PLA_AS', 255)->nullable();
            $table->string('CU', 255)->nullable();
            $table->string('CYL', 255)->nullable();
            $table->string('CD_TYP_CONS', 255)->nullable();
            $table->string('DAT_IMMAT', 255)->nullable();
            $table->string('GENRE', 255)->nullable();
            $table->string('USAGE', 255)->nullable();
            $table->string('TYP_COMM', 255)->nullable();
            $table->string('VILLE', 255)->nullable();
            $table->string('MARQUE', 255)->nullable();
            $table->string('PREMIERE_OPERATION', 255)->nullable();
            $table->string('GAUCHE', 255)->nullable();
            $table->string('CD_SERIE', 255)->nullable();
            $table->string('DROIT_MIL', 255)->nullable();
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
        Schema::dropIfExists('chassis_numbers');
    }
}
