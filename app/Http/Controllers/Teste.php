<?php

namespace App\Http\Controllers;

use App\Ordemservico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Teste extends Controller
{
    public function teste(Request $request)
    {
        $user = $request->user();
        $profissional = $user->pessoa->profissional;

        $escalas = Ordemservico::with([
            'servicos',
            'orcamento.cidade', 'orcamento' => function ($query) {
                // $query->select('id', 'cliente_id');
                $query->with(['homecare' => function ($query) {
                    // $query->select('id', 'orcamento_id', 'paciente_id');
                    $query->with(['paciente.pessoa', 'paciente.responsavel.pessoa']);
                }]);
                $query->with(['cliente' => function ($query) {
                    $query->select('id', 'pessoa_id');
                    $query->with(['pessoa' => function ($query) {
                        $query->select('id', 'nome');
                    }]);
                }]);
            }
        ])
            ->withCount('prestadores')
            ->withCount('escalas')
            ->where('empresa_id', $profissional->empresa_id)
            ->where('ativo', true)
            ->get(['id', 'orcamento_id']);

        return $escalas;
    }
}
