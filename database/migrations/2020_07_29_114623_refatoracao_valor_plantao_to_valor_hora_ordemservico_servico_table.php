<?php

use App\Models\OrdemservicoServico;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefatoracaoValorPlantaoToValorHoraOrdemservicoServicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordemservico_servico', function (Blueprint $table) {
            $itens = OrdemservicoServico::all();
            foreach ($itens as $key => $iten) {
                if ($iten->descricao == 'Plantão') {
                    $iten->valordiurno = $iten->valordiurno / 12;
                    $iten->valornoturno = $iten->valornoturno / 12;
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
        Schema::table('ordemservico_servico', function (Blueprint $table) {
            $itens = OrdemservicoServico::all();
            foreach ($itens as $key => $iten) {
                if ($iten->descricao == 'Plantão') {
                    $iten->valordiurno = $iten->valordiurno * 12;
                    $iten->valornoturno = $iten->valornoturno * 12;
                    $iten->save();
                }
            }
        });
    }
}
