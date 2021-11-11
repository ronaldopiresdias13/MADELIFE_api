<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratoTabelaprecoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_tabelapreco', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('contrato_id')->references('id')->on('contratos');
            $table->foreignUuid('tabelapreco_id')->references('id')->on('tabelaprecos');
            $table->float('percentualdesconto')->nullable();
            $table->string('versao')->nullable();
            $table->string('datainicio');
            $table->string('datafim')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contrato_tabelapreco');
    }
}
