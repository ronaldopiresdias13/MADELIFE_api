<?php

namespace App\Observers;

use App\Ordemservico;
use App\OrdemservicoAcesso;
use Illuminate\Support\Facades\DB;

class OrdemservicoObserver
{
    /**
     * Handle the ordemservico "created" event.
     *
     * @param  \App\Ordemservico  $ordemservico
     * @return void
     */
    public function created(Ordemservico $ordemservico)
    {
        $acessos = $ordemservico->empresa->acessos;

        foreach ($acessos as $key => $acesso) {
            DB::transaction(function () use ($ordemservico, $acesso) {
                OrdemservicoAcesso::create(
                    [
                        'empresa_id'      => $ordemservico->empresa_id,
                        'ordemservico_id' => $ordemservico->id,
                        'acesso_id'       => $acesso->id
                    ]
                );
            });
        }
    }

    /**
     * Handle the ordemservico "updated" event.
     *
     * @param  \App\Ordemservico  $ordemservico
     * @return void
     */
    public function updated(Ordemservico $ordemservico)
    {
        //
    }

    /**
     * Handle the ordemservico "deleted" event.
     *
     * @param  \App\Ordemservico  $ordemservico
     * @return void
     */
    public function deleted(Ordemservico $ordemservico)
    {
        //
    }

    /**
     * Handle the ordemservico "restored" event.
     *
     * @param  \App\Ordemservico  $ordemservico
     * @return void
     */
    public function restored(Ordemservico $ordemservico)
    {
        //
    }

    /**
     * Handle the ordemservico "force deleted" event.
     *
     * @param  \App\Ordemservico  $ordemservico
     * @return void
     */
    public function forceDeleted(Ordemservico $ordemservico)
    {
        //
    }
}
