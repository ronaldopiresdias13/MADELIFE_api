<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\OrdemservicoPrestador;
use App\Prestador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdemservicoPrestadoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = OrdemservicoPrestador::where('ativo', true);

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                $itens->where(
                    ($where['coluna']) ? $where['coluna'] : 'id',
                    ($where['expressao']) ? $where['expressao'] : 'like',
                    ($where['valor']) ? $where['valor'] : '%'
                );
            }
        }

        if ($request['order']) {
            foreach ($request['order'] as $key => $order) {
                $itens->orderBy(
                    ($order['coluna']) ? $order['coluna'] : 'id',
                    ($order['tipo']) ? $order['tipo'] : 'asc'
                );
            }
        }

        $itens = $itens->get();

        if ($request['adicionais']) {
            foreach ($itens as $key => $iten) {
                foreach ($request['adicionais'] as $key => $adicional) {
                    if (is_string($adicional)) {
                        $iten[$adicional];
                    } else {
                        $iten2 = $iten;
                        foreach ($adicional as $key => $a) {
                            if ($key == 0) {
                                if ($iten[0] == null) {
                                    $iten2 = $iten[$a];
                                } else {
                                    foreach ($iten as $key => $i) {
                                        $i[$a];
                                    }
                                }
                            } else {
                                if ($iten2 != null) {
                                    if ($iten2->count() > 0) {
                                        if ($iten2[0] == null) {
                                            $iten2 = $iten2[$a];
                                        } else {
                                            foreach ($iten2 as $key => $i) {
                                                $i[$a];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $itens;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            OrdemservicoPrestador::create($request->all());
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrdemservicoPrestador  $ordemservicoPrestador
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, OrdemservicoPrestador $ordemservicoPrestador)
    {
        $iten = $ordemservicoPrestador;

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['adicionais']) {
            foreach ($request['adicionais'] as $key => $adicional) {
                if (is_string($adicional)) {
                    $iten[$adicional];
                } else {
                    $iten2 = $iten;
                    foreach ($adicional as $key => $a) {
                        if ($key == 0) {
                            if ($iten[0] == null) {
                                $iten2 = $iten[$a];
                            } else {
                                foreach ($iten as $key => $i) {
                                    $i[$a];
                                }
                            }
                        } else {
                            if ($iten2 != null) {
                                if ($iten2->count() > 0) {
                                    if ($iten2[0] == null) {
                                        $iten2 = $iten2[$a];
                                    } else {
                                        foreach ($iten2 as $key => $i) {
                                            $i[$a];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $iten;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrdemservicoPrestador  $ordemservicoPrestador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrdemservicoPrestador $ordemservicoPrestador)
    {
        DB::transaction(function () use ($request, $ordemservicoPrestador) {
            $ordemservicoPrestador->update($request->all());
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OrdemservicoPrestador  $ordemservicoPrestador
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrdemservicoPrestador $ordemservicoPrestador)
    {
        $ordemservicoPrestador->ativo = false;
        $ordemservicoPrestador->save();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function listaPorPrestador(Prestador $prestador)
    {
        $ordemservicoPrestador = OrdemservicoPrestador::where('prestador_id', $prestador->id)
        // ->join('ordemservicos', function ($join) {
        //     $join->on('ordemservicos.id', '=', 'ordemservico_prestador.ordemservico_id');
        // })
        ->join('ordemservicos', 'ordemservicos.id', '=', 'ordemservico_prestador.ordemservico_id')
        ->select('ordemservicos.*', 'ordemservico_prestador.*')
        ->groupBy('ordemservicos.id')
        // ->select('ordemservico_prestador.*')
        ->groupBy('ordemservico_prestador.id')
        // ->select('ordemservicos.*')//->groupBy('ordemservicos.id')
        ->get();
        return $ordemservicoPrestador;
        // $escalas = Escala::where('prestador_id', $prestador->id)
        //     ->join('ordemservicos', 'ordemservicos.id', '=', 'escalas.ordemservico_id')
        //     ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
        //     ->join('homecares', 'homecares.orcamento_id', '=', 'orcamentos.id')
        //     ->select('homecares.nome')
        //     ->where('homecares.ativo', true)
        //     ->groupBy('homecares.nome')
        //     ->orderBy('homecares.nome')
        //     ->get();
        // return $escalas;
    }
}
