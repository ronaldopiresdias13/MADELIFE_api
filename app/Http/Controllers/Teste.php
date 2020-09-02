<?php

namespace App\Http\Controllers;

use App\Escala;

class Teste extends Controller
{
    public function teste()
    {
        // $itens = Escala::with('ordemservico')->where('ativo', true)->limit(5)->get();
        $itens = Escala::where('ativo', true)
            ->limit(5)
            ->join('ordemservicos', 'ordemservicos.id', '=', 'escalas.ordemservico_id')
            ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
            ->join('homecares', 'homecares.orcamento_id', '=', 'orcamentos.id')
            ->select('homecares.paciente_id')
            ->get();
        // dd($itens);

        return $itens;
    }
}
