<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCnabheaderarquivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cnabheaderarquivos', function (Blueprint $table) {
            $table->id();
            $table->char('codigobanco', 3);
            $table->char('loteservico', 4);
            $table->char('tiporegistro', 1);
            $table->char('filler', 9);
            $table->char('tipoinscemp', 1);
            $table->char('numinscemp', 14);
            $table->char('codigoconvbanco', 20);
            $table->char('agenciaconta', 5);
            $table->char('digitoagencia', 1);
            $table->char('numcontacorrente', 12);
            $table->char('digitoconta', 1);
            $table->char('digitoagenciaconta', 1);
            $table->char('nomeempresa', 30);
            $table->char('nomebanco', 30);
            $table->char('filler2', 10);
            $table->char('codremessa', 1);
            $table->char('dataarquivo', 8);
            $table->char('horaarquivo', 6);
            $table->char('numseqarquivo', 6);
            $table->char('numversaolayout', 3);
            $table->char('densidadegravacaoarquivo', 5);
            $table->char('reservadobanco', 20); //Apenas Fornecedores
            $table->char('usobanco', 20);
            $table->char('usoempresa', 20);
            $table->char('filler3', 19);
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
        Schema::dropIfExists('cnabheaderarquivos');
    }
}
