<?php

namespace App\Observers;

use App\Models\Ordemservico;
use App\Models\OrdemservicoAcesso;
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
                $ordemservicoAcesso = new OrdemservicoAcesso();
                $ordemservicoAcesso->empresa_id      = $ordemservico->empresa_id;
                $ordemservicoAcesso->ordemservico_id = $ordemservico->id;
                $ordemservicoAcesso->acesso_id       = $acesso->id;
                $ordemservicoAcesso->save();
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
