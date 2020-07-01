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
            $table->string('codigobanco')->nullable();
            $table->string('loteservico')->nullable();
            $table->string('tiporegistro')->nullable();
            $table->string('filler')->nullable();
            $table->string('tipoinscemp')->nullable();
            $table->string('numinscemp')->nullable();
            $table->string('codigoconvbanco')->nullable();
            $table->string('agenciaconta')->nullable();
            $table->string('digitoagencia')->nullable();
            $table->string('numcontacorrente')->nullable();
            $table->string('digitoconta')->nullable();
            $table->string('digitoagenciaconta')->nullable();
            $table->string('nomeempresa')->nullable();
            $table->string('nomebanco')->nullable();
            $table->string('filler2')->nullable();
            $table->string('codremessa')->nullable();
            $table->string('dataarquivo')->nullable();
            $table->string('horaarquivo')->nullable();
            $table->string('numseqarquivo')->nullable();
            $table->string('numversaolayout')->nullable();
            $table->string('densidadegravacaoarquivo')->nullable();
            $table->string('reservadobanco')->nullable(); //Apenas Fornecedores
            $table->string('usobanco')->nullable();
            $table->string('usoempresa')->nullable();
            $table->string('filler3')->nullable();
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
        Schema::dropIfExists('cnabheaderarquivos');
    }
}
