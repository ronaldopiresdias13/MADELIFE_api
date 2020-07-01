<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCnabdetalheasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cnabdetalheas', function (Blueprint $table) {
            $table->id();
            $table->string('codigobanco')->nullable();
            $table->string('loteservico')->nullable();
            $table->string('tiporegistro')->nullable();
            $table->string('numeroseqregistrolote')->nullable();
            $table->string('codigosegregistrodetalhe')->nullable();
            $table->string('tipomovimento')->nullable();
            $table->string('codigoinstmovimento')->nullable();
            $table->string('codigocamaracomp')->nullable();
            $table->string('codigobancofavo')->nullable();
            $table->string('codigoagenciafavo')->nullable();
            $table->string('digitoagenciafavo')->nullable();
            $table->string('ccfavorecido')->nullable();
            $table->string('digitoconta')->nullable();
            $table->string('digitoagenciaconta')->nullable();
            $table->string('nome')->nullable();
            $table->string('numerocliente')->nullable();
            $table->string('datapagamento')->nullable();
            $table->string('tipomoeda')->nullable();
            $table->string('quantidademoeda')->nullable();
            $table->string('valorpagamento')->nullable();
            $table->string('numerodocbanco')->nullable();
            $table->string('datarealpag')->nullable();
            $table->string('valorrealpag')->nullable();
            $table->string('outrasinfo')->nullable();
            $table->string('finalidadedoc')->nullable();
            $table->string('finalidadeted')->nullable(); // Fornecedores
            $table->string('codigocomplementar')->nullable(); // Fornecedores
            $table->string('filler')->nullable();
            $table->string('emissaofavorecido')->nullable();
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
        Schema::dropIfExists('cnabdetalheas');
    }
}
