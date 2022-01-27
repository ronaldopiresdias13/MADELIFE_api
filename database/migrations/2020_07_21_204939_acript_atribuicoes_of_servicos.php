<?php

use App\Models\Ordemservico;
use App\Models\OrdemservicoServico;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AcriptAtribuicoesOfServicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            $ordemservicos = Ordemservico::all();

            foreach ($ordemservicos as $key => $ordemservico) {
                // foreach ($ordemservico->orcamento as $key => $orcamento) {
                foreach ($ordemservico->orcamento->servicos as $key => $servico) {
                    if ($servico['pivot']['basecobranca'] == 'Plantão') {
                        $ordemservico_servico = OrdemservicoServico::firstOrCreate(
                            [
                                'ordemservico_id'  => $ordemservico->id,
                                'servico_id'       => $servico->id,
                                'descricao'        => $servico['pivot']['basecobranca'],
                                'valor'            => ($servico['pivot']['custo'] / 2),
                                'adicionalnoturno' => $servico['pivot']['adicionalnoturno'],
                            ]
                        );
                    } else {
                        $ordemservico_servico = OrdemservicoServico::firstOrCreate(
                            [
                                'ordemservico_id'  => $ordemservico->id,
                                'servico_id'       => $servico->id,
                                'descricao'        => $servico['pivot']['basecobranca'],
                                'valor'            => ($servico['pivot']['custo']),
                                'adicionalnoturno' => $servico['pivot']['adicionalnoturno'],
                            ]
                        );
                    }
                }
                // }
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
        //
    }
}
