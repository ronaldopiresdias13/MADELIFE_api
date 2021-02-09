<?php

namespace App\Observers;

use App\Models\Orcamento;
use App\Models\Ordemservico;
use App\Models\OrdemservicoServico;

class OrcamentoObserver
{
    /**
     * Handle the Orcamento "created" event.
     *
     * @param  \App\Models\Orcamento  $orcamento
     * @return void
     */
    public function created(Orcamento $orcamento)
    {
        //
    }

    /**
     * Handle the Orcamento "updated" event.
     *
     * @param  \App\Models\Orcamento  $orcamento
     * @return void
     */
    public function updated(Orcamento $orcamento)
    {
        $ordemservico = Ordemservico::where('orcamento_id', $orcamento->id)->first();

        if ($ordemservico) {
            $this->deleteArray($ordemservico->servicos);

            foreach ($orcamento->servicos as $key => $servico) {
                OrdemservicoServico::create(
                    [
                        'ordemservico_id'  => $ordemservico->id,
                        'servico_id'       => $servico->id,
                        'descricao'        => $servico['pivot']['basecobranca'],
                        'valordiurno'      => $servico['pivot']['custodiurno'],
                        'valornoturno'     => $servico['pivot']['custonoturno'],
                    ]
                );
            }
        }
    }

    /**
     * Handle the Orcamento "deleted" event.
     *
     * @param  \App\Models\Orcamento  $orcamento
     * @return void
     */
    public function deleted(Orcamento $orcamento)
    {
        //
    }

    /**
     * Handle the Orcamento "restored" event.
     *
     * @param  \App\Models\Orcamento  $orcamento
     * @return void
     */
    public function restored(Orcamento $orcamento)
    {
        //
    }

    /**
     * Handle the Orcamento "force deleted" event.
     *
     * @param  \App\Models\Orcamento  $orcamento
     * @return void
     */
    public function forceDeleted(Orcamento $orcamento)
    {
        //
    }

    public function deleteArray(array $itens)
    {
        foreach ($itens as $key => $iten) {
            $iten->delete();
        }
    }
}
