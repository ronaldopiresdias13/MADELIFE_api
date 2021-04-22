<?php

use App\Models\Pagamentoexterno;
use App\Models\Pagamentopessoa;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApagarAtributosPagamentoexternos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Table PAGAMENTOEXTENOS
        Schema::table('pagamentoexternos', function (Blueprint $table) {
            $table->dropForeign(['pessoa_id']);
            $table->dropColumn('pessoa_id');
            $table->dropColumn('datainicio');
            $table->dropColumn('datafim');
            $table->dropColumn('status');
            $table->dropColumn('observacao');
            $table->dropColumn('situacao');
            $table->dropColumn('proventos');
            $table->dropColumn('descontos');
            $table->dropColumn('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagamentoexternos', function (Blueprint $table) {
            $table->unsignedBigInteger('pessoa_id')->nullable();
            $table->foreign('pessoa_id')->references('id')->on('pessoas');
            $table->string('datainicio')->nullable();
            $table->string('datafim')->nullable();
            $table->boolean('status')->default(0);
            $table->string('observacao')->nullable();
            $table->string('situacao')->nullable();
            $table->float('proventos')->nullable();
            $table->float('descontos')->nullable();
        });
        $pagamentopessoas = Pagamentopessoa::all();
        foreach ($pagamentopessoas as $key => $pag) {
            $pge = Pagamentoexterno::create([
                'pessoa_id'  => $pag['pessoa_id'],
                'datainicio' => $pag['periodo1'],
                'datafim'    => $pag['periodo2'],
                'proventos'  => $pag['proventos'],
                'descontos'  => $pag['descontos'],
                'subtotal'   => $pag['subtotal'],
                'observacao' => $pag['observacao'],
                'status'     => $pag['status'],
                'situacao'   => $pag['situacao'],
            ]);
        }
    }
}
