<?php

use App\Models\Pagamentoexterno;
use App\Models\Pagamentopessoa;
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
        Schema::table('pagamentopessoas', function (Blueprint $table) {
            $table->dropColumn('ativo');                           // Apagar Coluna Ativo
            $table->dropForeign(['ordemservico_id']);              // Apagar FK de Ordem de Serviço
            $table->dropColumn('ordemservico_id');                 // Apagar Coluna ordemservico_id
            $table->string('tipopessoa')->after('valor');                    // Adicionar Coluna tipopessoa
            $table->renameColumn('periodo1', 'datainicio');          // Renomear coluna periodo1 para datainicio
            $table->renameColumn('periodo2', 'datafim');             // Renomear coluna periodo2 para datafim
            $table->float('proventos')->nullable()->after('valor');  // Adicionar Coluna proventos
            $table->float('descontos')->nullable()->after('valor');  // Adicionar Coluna descontos
            $table->softDeletes();                                   // Adicionar Soft Deletes
        });
        Schema::table('pagamentoexternos', function (Blueprint $table) {
            $table->unsignedBigInteger('pagamentopessoa_id')->nullable()->after('empresa_id');
            $table->foreign('pagamentopessoa_id')->references('id')->on('pagamentopessoas');
        });

        $pagamentosexternos = Pagamentoexterno::all();
        foreach ($pagamentosexternos as $key => $pag) {
            $pag_p = Pagamentopessoa::create([
                'empresa_id' => $pag['empresa_id'],
                'pessoa_id'  => $pag['pessoa_id'],
                'datainicio' => $pag['datainicio'],
                'tipopessoa' => 'Prestador Externo',
                'datafim'    => $pag['datafim'],
                'proventos'  => $pag['proventos'],
                'descontos'  => $pag['descontos'],
                'valor'      => $pag['subtotal'],
                'observacao' => $pag['observacao'],
                'status'     => $pag['status'],
                'situacao'   => $pag['situacao'],
            ]);
            $pag->pagamentopessoa_id = $pag_p->id;
        }
        // Table PAGAMENTOEXTENOS
        Schema::table('pagamentoexternos', function (Blueprint $table) {
            $table->dropForeign(['pessoa_id']);
            $table->dropColumn(['pessoa_id']);
            $table->dropColumn(['datainicio']);
            $table->dropColumn(['datafim']);
            $table->dropColumn(['status']);
            $table->dropColumn(['observacao']);
            $table->dropColumn(['situacao']);
            $table->dropColumn(['proventos']);
            $table->dropColumn(['descontos']);
            $table->dropColumn(['subtotal']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('pagamentoexternos', function (Blueprint $table) {
        //     $table->unsignedBigInteger('pessoa_id')->nullable();
        //     $table->foreign('pessoa_id')->references('id')->on('pessoas');
        //     $table->string('datainicio')->nullable();
        //     $table->string('datafim')->nullable();
        //     $table->boolean('status')->default(0);
        //     $table->string('observacao')->nullable();
        //     $table->string('situacao')->nullable();
        //     $table->float('proventos')->nullable();
        //     $table->float('descontos')->nullable();
        // });
        // $pagamentopessoas = Pagamentopessoa::all();
        // foreach ($pagamentopessoas as $key => $pag) {
        //     $pge = Pagamentoexterno::create([
        //         'pessoa_id'  => $pag['pessoa_id'],
        //         'datainicio' => $pag['datainicio'],
        //         'datafim'    => $pag['datafim'],
        //         'proventos'  => $pag['proventos'],
        //         'descontos'  => $pag['descontos'],
        //         'subtotal'   => $pag['subtotal'],
        //         'observacao' => $pag['observacao'],
        //         'status'     => $pag['status'],
        //         'situacao'   => $pag['situacao'],
        //     ]);
        // }
        Schema::table('pagamentoexternos', function (Blueprint $table) {
            $table->dropForeign(['pagamentopessoa_id']);
            $table->dropColumn('pagamentopessoa_id');
        });

        // Table PAGAMENTOPESSOAS
        Schema::table('pagamentopessoas', function (Blueprint $table) {
            $table->boolean('ativo')->default(1);                                       // Adicionar Coluna Ativo
            $table->dropSoftDeletes();                                                  // Apagar Soft Deletes
            $table->unsignedBigInteger('ordemservico_id')->nullable();                  // Adicionar FK de Ordem de Serviço
            $table->foreign('ordemservico_id')->references('id')->on('ordemservicos');  // Adicionar Coluna ordemservico_id
            $table->dropColumn('tipopessoa');                                           // Apagar Coluna tipopessoa
            $table->dropColumn('proventos')->nullable();                                // Apagar Coluna proventos
            $table->dropColumn('descontos')->nullable();                                // Apagar Coluna descontos
            $table->renameColumn('datainicio', 'periodo1');                             // Renomear coluna periodo1 para datainicio
            $table->renameColumn('datafim', 'periodo2');                                // Renomear coluna periodo2 para datafim
        });
    }
}
