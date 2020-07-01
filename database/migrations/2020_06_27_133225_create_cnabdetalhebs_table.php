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
            $table->char('codigobanco', 3);
            $table->char('loteservico', 4);
            $table->char('tiporegistro', 1);
            $table->char('numeroseqregistrolote', 5);
            $table->char('codigosegregistrodetalhe', 1);
            $table->char('filler', 3);
            $table->char('tipoinscfavorecido', 1);
            $table->char('cpfcnpjfavorecido', 14);
            $table->char('logradourofavorecido', 30);
            $table->char('numerolocalfavorecido', 5);
            $table->char('complocalfavorecido', 15);
            $table->char('bairrofavorecido', 15);
            $table->char('cidadefavorecido', 20);
            $table->char('cepfavorecido', 8);
            $table->char('estadofavorecido', 2);
            $table->char('datavencimento', 8);
            $table->char('valordocumento', 15);
            $table->char('valorabatimento', 15);
            $table->char('valordesconto', 15);
            $table->char('valormora', 15);
            $table->char('valormulta', 15);
            $table->char('horarioenvio', 4);
            $table->char('filler2', 11);
            $table->char('codigohistcredito', 4);
            $table->char('ocorrenciasretorno', 1);
            $table->char('filler3', 1); // Fornecedores
            $table->char('tedfinanceira', 1); // Fornecedores
            $table->char('identificacaospb', 8); // Fornecedores
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
