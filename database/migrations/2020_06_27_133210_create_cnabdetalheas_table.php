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
            $table->char('codigobanco', 3);
            $table->char('loteservico', 4);
            $table->char('tiporegistro', 1);
            $table->char('numeroseqregistrolote', 5);
            $table->char('codigosegregistrodetalhe', 1);
            $table->char('tipomovimento', 1);
            $table->char('codigoinstmovimento', 2);
            $table->char('codigocamaracomp', 3);
            $table->char('codigobancofavo', 3);
            $table->char('codigoagenciafavo', 5);
            $table->char('digitoagenciafavo', 1);
            $table->char('ccfavorecido', 12);
            $table->char('digitoconta', 1);
            $table->char('digitoagenciaconta', 1);
            $table->char('nome', 30);
            $table->char('numerocliente', 20);
            $table->char('datapagamento', 8);
            $table->char('tipomoeda', 3);
            $table->char('quantidademoeda', 15);
            $table->char('valorpagamento', 15);
            $table->char('numerodocbanco', 20);
            $table->char('datarealpag', 8);
            $table->char('valorrealpag', 15);
            $table->char('outrasinfo', 40);
            $table->char('finalidadedoc', 2);
            $table->char('finalidadeted', 5); // Fornecedores
            $table->char('codigocomplementar', 2); // Fornecedores
            $table->char('filler', 3);
            $table->char('emissaofavorecido', 1);
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
        Schema::dropIfExists('cnabdetalheas');
    }
}
