<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeForeignOrdemservicoPrestadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordemservico_prestador', function (Blueprint $table) {
            $table->dropForeign('atribuicoes_prestador_id_foreign');
            $table->renameIndex('atribuicoes_prestador_id_foreign', 'ordemservico_prestador_prestador_id_foreign');
            $table->foreign('prestador_id')->references('id')->on('prestadores')->onDelete('cascade');

            $table->dropForeign('atribuicoes_ordemservico_id_foreign');
            $table->renameIndex('atribuicoes_ordemservico_id_foreign', 'ordemservico_prestador_ordemservico_id_foreign');
            $table->foreign('ordemservico_id')->references('id')->on('ordemservicos')->onDelete('cascade');

            $table->renameIndex('atribuicoes_ativo_index', 'ordemservico_prestador_ativo_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
