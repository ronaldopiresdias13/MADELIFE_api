<?php

namespace App\Http\Controllers;

use App\Banco;
use App\Ordemservico;
use App\OrdemservicoAcesso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Teste extends Controller
{
    public function teste(Ordemservico $ordemservico)
    {

        // return OrdemservicoAcesso::all();

        // return 'teste';
        // return $ordemservico;
        // return $ordemservico->empresa;
        // return $ordemservico->empresa->acessos;

        $acessos = $ordemservico->empresa->acessos;

        // return $acessos;

        foreach ($acessos as $key => $acesso) {
            DB::transaction(function () use ($ordemservico, $acesso) {
                // OrdemservicoAcesso::create(
                //     [
                //         'empresa_id'      => $ordemservico->empresa_id,
                //         'ordemservico_id' => $ordemservico->id,
                //         'acesso_id'       => $acesso->id
                //     ]
                // );
                $ordemservicoAcesso = new OrdemservicoAcesso();
                $ordemservicoAcesso->empresa_id      = $ordemservico->empresa_id;
                $ordemservicoAcesso->ordemservico_id = $ordemservico->id;
                $ordemservicoAcesso->acesso_id       = $acesso->id;
                $ordemservicoAcesso->save();
            });
        }
    }
}
