<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTablePagamentopessoas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Table PAGAMENTOPESSOAS
        // Schema::table('pagamentopessoas', function (Blueprint $table) {
        //     $table->dropColumn('ativo');                     // Apagar Coluna Ativo
        //     $table->softDeletes();                           // Adicionar Soft Deletes
        //     $table->dropForeign(['ordemservico_id']);        // Apagar FK de Ordem de Serviço
        //     $table->dropColumn('ordemservico_id');           // Apagar Coluna ordemservico_id
        //     $table->string('tipopessoa');                    // Adicionar Coluna tipopessoa
        //     $table->float('proventos')->nullable();          // Adicionar Coluna proventos
        //     $table->float('descontos')->nullable();          // Adicionar Coluna descontos
        //     $table->renameColumn('periodo1', 'datainicio');  // Renomear coluna periodo1 para datainicio
        //     $table->renameColumn('periodo2', 'datafim');     // Renomear coluna periodo2 para datafim
        // });


        // Table PAGAMENTOEXTENOS
        // Schema::table('pagamentoexternos', function (Blueprint $table) {
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('pacotes', function (Blueprint $table) {
        //     $table->boolean('ativo');                        // Apagar Coluna Ativo
        //     $table->dropSoftDeletes();                       // Adicionar Soft Deletes
        //     $table->dropForeign(['ordemservico_id']);        // Apagar FK de Ordem de Serviço
        //     $table->dropColumn('ordemservico_id');           // Apagar Coluna ordemservico_id
        //     $table->string('tipopessoa');                    // Adicionar Coluna tipopessoa
        //     $table->float('proventos')->nullable();          // Adicionar Coluna proventos
        //     $table->float('descontos')->nullable();          // Adicionar Coluna descontos
        //     $table->renameColumn('periodo1', 'datainicio');  // Renomear coluna periodo1 para datainicio
        //     $table->renameColumn('periodo2', 'datafim');     // Renomear coluna periodo2 para datafim
        // });
    }
}
