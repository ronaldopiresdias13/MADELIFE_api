<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrdemservicoPrestador;
use App\Models\Ordemservico;
use App\Models\Prestador;
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
        $with = [];

        if ($request['adicionais']) {
            foreach ($request['adicionais'] as $key => $adicional) {
                if (is_string($adicional)) {
                    array_push($with, $adicional);
                } else {
                    $filho = '';
                    foreach ($adicional as $key => $a) {
                        if ($key == 0) {
                            $filho = $a;
                        } else {
                            $filho = $filho . '.' . $a;
                        }
                    }
                    array_push($with, $filho);
                }
            }
            $itens = OrdemservicoPrestador::with($with)->where('ativo', true);
        } else {
            $itens = OrdemservicoPrestador::where('ativo', true);
        }

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
        // return $request;
        DB::transaction(function () use ($request) {
            OrdemservicoPrestador::create(
                [
                    'prestador_id' => $request['prestador_id'],
                    'ordemservico_id' => $request['ordemservico_id'],
                    'servico_id' => $request['servico_id'],
                    'descricao' => $request['descricao'],
                    'valordiurno' => $request['valordiurno'],
                    'valornoturno' => $request['valornoturno'],
                    'ativo' => true,
                ]
            );
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
    public function profissionaisatribuidosaopaciente(Ordemservico $ordemservico)
    {
        return OrdemservicoPrestador::With([
            'prestador.pessoa.conselhos',
            'prestador.formacoes', 'servico'
        ])
            ->where(
                'ordemservico_id',
                $ordemservico->id
            )->get();
    }
}
