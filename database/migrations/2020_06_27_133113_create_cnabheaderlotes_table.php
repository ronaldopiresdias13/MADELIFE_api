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
            $table->char('codigobanco', 3);
            $table->char('loteservico', 4);
            $table->char('tiporegistro', 1);
            $table->char('tipooperacao', 1);
            $table->char('tiposervico', 2);
            $table->char('formalancamento', 2);
            $table->char('numerolote', 3);
            $table->char('filler', 1);
            $table->char('tipoinscemp', 1);
            $table->char('numinscemp', 14);
            $table->char('codigoconvbanco', 20);
            $table->char('agenciaconta', 5);
            $table->char('digitoagencia', 1);
            $table->char('numcontacorrente', 12);
            $table->char('digitoconta', 1);
            $table->char('digitoagenciaconta', 1);
            $table->char('nomeempresa', 30);
            $table->char('filler2', 40);
            $table->char('endereco', 30);
            $table->char('numero', 5);
            $table->char('compendereco', 15);
            $table->char('cidade', 20);
            $table->char('cep', 5);
            $table->char('complcep', 3);
            $table->char('uf', 2);
            $table->char('filler3', 8);
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
        Schema::dropIfExists('cnabheaderlotes');
    }
}
