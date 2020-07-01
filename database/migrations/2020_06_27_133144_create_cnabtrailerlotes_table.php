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
            $table->char('codigobanco', 3);
            $table->char('loteservico', 4);
            $table->char('tiporegistro', 1);
            $table->char('filler', 9);
            $table->char('quantidadereglote', 6);
            $table->char('somatoriavalores', 18);
            $table->char('somatoriaquantmoeda', 18);
            $table->char('numeroavisodebito', 6);
            $table->char('filler2', 165);
            $table->char('ocorrenciasretorno', 10);
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
