<?php

use App\Models\Escala;
use App\Models\Ordemservico;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterarValoresDasEscalas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $data = \Carbon\Carbon::parse($suaData)->format('d/m/Y')
        $escalas = Escala::where('dataentrada', '>', '2020-12-31')->orderByDesc('dataentrada')->get();
        // foreach ($escalas as $key => $escala) {
        //     $os_s = OrdemservicoServico::where('ordemservico_id', $escala['ordemservico_id'])
        //         ->where('servico_id', $escala['servico_id'])
        //         ->first();
        //     if ($os_s) {
        //         $escala->valorhoradiurno = $os_s->valordiurno;
        //         $escala->valorhoranoturno = $os_s->valornoturno;
        //         $escala->save();
        //     }
        // }

        foreach ($escalas as $key => $escala) {
            $ordemservico = Ordemservico::with([
                'orcamento.servicos' => function ($query) use ($escala) {
                    $query->where('servico_id', $escala->servico_id);
                }
            ])
                ->has('orcamento')
                ->whereHas('orcamento.servicos', function (Builder $query) use ($escala) {
                    $query->where('servico_id', $escala->servico_id);
                })
                ->where('id', $escala['ordemservico_id'])
                ->first();

            if ($ordemservico) {

                $tipo   = $ordemservico->orcamento->servicos[0]->basecobranca;
                $horasD = $ordemservico->orcamento->servicos[0]->horascuidadodiurno;
                $horasN = $ordemservico->orcamento->servicos[0]->horascuidadonoturno;
                $valorD = $ordemservico->orcamento->servicos[0]->custodiurno;
                $valorN = $ordemservico->orcamento->servicos[0]->custonoturno;

                $escala->valorhoradiurno  = $valorD ? ($tipo == 'Plantão' ? $valorD / $horasD : $valorD) : $valorD;
                $escala->valorhoranoturno = $valorN ? ($tipo == 'Plantão' ? $valorN / $horasN : $valorN) : $valorN;

                $escala->save();
            }
        }
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
