<?php

use App\Models\OrcamentoServico;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefatoracaoHorascuidadoToOrcamentoServicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orcamento_servico', function (Blueprint $table) {
            $itens = OrcamentoServico::all();
            foreach ($itens as $key => $iten) {
                if ($iten->basecobranca == 'Plantão') {
                    $iten->horascuidado = 24;
                    $iten->save();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orcamento_servico', function (Blueprint $table) {
            $itens = OrcamentoServico::all();
            foreach ($itens as $key => $iten) {
                if ($iten->basecobranca == 'Plantão') {
                    $iten->horascuidado = 1;
                    $iten->save();
                }
            }
        });
    }
}
