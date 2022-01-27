<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCnabtrailerlotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cnabtrailerlotes', function (Blueprint $table) {
            $table->id();
            $table->string('codigobanco')->nullable();
            $table->string('loteservico')->nullable();
            $table->string('tiporegistro')->nullable();
            $table->string('filler')->nullable();
            $table->string('quantidadereglote')->nullable();
            $table->string('somatoriavalores')->nullable();
            $table->string('somatoriaquantmoeda')->nullable();
            $table->string('numeroavisodebito')->nullable();
            $table->string('filler2')->nullable();
            $table->string('ocorrenciasretorno')->nullable();
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
        Schema::dropIfExists('cnabtrailerlotes');
    }
}
