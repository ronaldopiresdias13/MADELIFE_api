<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCnabdetalhebsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cnabdetalhebs', function (Blueprint $table) {
            $table->id();
            $table->string('codigobanco')->nullable();
            $table->string('loteservico')->nullable();
            $table->string('tiporegistro')->nullable();
            $table->string('numeroseqregistrolote')->nullable();
            $table->string('codigosegregistrodetalhe')->nullable();
            $table->string('filler')->nullable();
            $table->string('tipoinscfavorecido')->nullable();
            $table->string('cpfcnpjfavorecido')->nullable();
            $table->string('logradourofavorecido')->nullable();
            $table->string('numerolocalfavorecido')->nullable();
            $table->string('complocalfavorecido')->nullable();
            $table->string('bairrofavorecido')->nullable();
            $table->string('cidadefavorecido')->nullable();
            $table->string('cepfavorecido')->nullable();
            $table->string('estadofavorecido')->nullable();
            $table->string('datavencimento')->nullable();
            $table->string('valordocumento')->nullable();
            $table->string('valorabatimento')->nullable();
            $table->string('valordesconto')->nullable();
            $table->string('valormora')->nullable();
            $table->string('valormulta')->nullable();
            $table->string('horarioenvio')->nullable();
            $table->string('filler2')->nullable();
            $table->string('codigohistcredito')->nullable();
            $table->string('ocorrenciasretorno')->nullable();
            $table->string('filler3')->nullable(); // Fornecedores
            $table->string('tedfinanceira')->nullable(); // Fornecedores
            $table->string('identificacaospb')->nullable(); // Fornecedores
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
        Schema::dropIfExists('cnabdetalhebs');
    }
}
