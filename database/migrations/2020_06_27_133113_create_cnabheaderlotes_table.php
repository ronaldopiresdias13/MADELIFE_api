<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCnabheaderlotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cnabheaderlotes', function (Blueprint $table) {
            $table->id();
            $table->string('codigobanco')->nullable();
            $table->string('loteservico')->nullable();
            $table->string('tiporegistro')->nullable();
            $table->string('tipooperacao')->nullable();
            $table->string('tiposervico')->nullable();
            $table->string('formalancamento')->nullable();
            $table->string('numerolote')->nullable();
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
            $table->string('filler2')->nullable();
            $table->string('endereco')->nullable();
            $table->string('numero')->nullable();
            $table->string('compendereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('cep')->nullable();
            $table->string('complcep')->nullable();
            $table->string('uf')->nullable();
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
        Schema::dropIfExists('cnabheaderlotes');
    }
}
