<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameAtribuicoesToOrdemservicoPrestadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('atribuicoes', 'ordemservico_prestador');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('ordemservico_prestador', 'atribuicoes');
        Schema::table('atribuicoes', function (Blueprint $table) {
            $table->dropForeign('ordemservico_prestador_prestador_id_foreign');
            $table->foreign('prestador_id')->references('id')->on('prestadores')->onDelete('cascade');
            $table->dropForeign('ordemservico_prestador_ordemservico_id_foreign');
            $table->foreign('ordemservico_id')->references('id')->on('ordemservicos')->onDelete('cascade');

            $table->renameIndex('ordemservico_prestador_prestador_id_foreign', 'atribuicoes_prestador_id_foreign');
            $table->renameIndex('ordemservico_prestador_ordemservico_id_foreign', 'atribuicoes_ordemservico_id_foreign');
            $table->renameIndex('ordemservico_prestador_ativo_index', 'atribuicoes_ativo_index');
        });
    }
}
