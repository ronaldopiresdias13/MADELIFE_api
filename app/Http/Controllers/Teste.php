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
}
