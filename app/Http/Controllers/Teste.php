<?php

namespace App\Http\Controllers;

use App\Escala;
use App\Prestador;
use Illuminate\Http\Request;

class Teste extends Controller
{
    public function teste(Request $request)
    {
        // $with = [];

        // if ($request['adicionais']) {
        //     foreach ($request['adicionais'] as $key => $adicional) {
        //         if (is_string($adicional)) {
        //             array_push($with, $adicional);
        //         } else {
        //             $filho = '';
        //             foreach ($adicional as $key => $a) {
        //                 if ($key == 0) {
        //                     $filho = $a;
        //                 } else {
        //                     $filho = $filho . '.' . $a;
        //                 }
        //             }
        //             array_push($with, $filho);
        //         }
        //     }
        //     $itens = Prestador::with($with)->where('ativo', true);
        // } else {
        //     $itens = Prestador::where('ativo', true);
        // }

        // if ($request->commands) {
        //     $request = json_decode($request->commands, true);
        // }

        // if ($request['where']) {
        //     foreach ($request['where'] as $key => $where) {
        //         $itens->where(
        //             ($where['coluna']) ? $where['coluna'] : 'id',
        //             ($where['expressao']) ? $where['expressao'] : 'like',
        //             ($where['valor']) ? $where['valor'] : '%'
        //         );
        //     }
        // }

        // if ($request['order']) {
        //     foreach ($request['order'] as $key => $order) {
        //         $itens->orderBy(
        //             ($order['coluna']) ? $order['coluna'] : 'id',
        //             ($order['tipo']) ? $order['tipo'] : 'asc'
        //         );
        //     }
        // }

        // $itens = $itens->get();

        // if ($request['adicionais']) {
        //     foreach ($itens as $key => $iten) {
        //         foreach ($request['adicionais'] as $key => $adicional) {
        //             if (is_string($adicional)) {
        //                 $iten[$adicional];
        //             } else {
        //                 $iten2 = $iten;
        //                 foreach ($adicional as $key => $a) {
        //                     if ($key == 0) {
        //                         if ($iten[0] == null) {
        //                             $iten2 = $iten[$a];
        //                         } else {
        //                             foreach ($iten as $key => $i) {
        //                                 $i[$a];
        //                             }
        //                         }
        //                     } else {
        //                         if ($iten2 != null) {
        //                             if ($iten2->count() > 0) {
        //                                 if ($iten2[0] == null) {
        //                                     $iten2 = $iten2[$a];
        //                                 } else {
        //                                     foreach ($iten2 as $key => $i) {
        //                                         $i[$a];
        //                                     }
        //                                 }
        //                             }
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }

        // return $itens;


        // $itens = Prestador::all();

        $itens = Prestador::with(['pessoa', 'formacoes'])->get();

        // // $itens = Escala::with('ordemservico')->where('ativo', true)->limit(5)->get();
        // $itens = Escala::where('ativo', true)
        //     ->limit(5)
        //     ->join('ordemservicos', 'ordemservicos.id', '=', 'escalas.ordemservico_id')
        //     ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
        //     ->join('homecares', 'homecares.orcamento_id', '=', 'orcamentos.id')
        //     ->select('homecares.paciente_id')
        //     ->get();
        // // dd($itens);

        return $itens;
    }
}
