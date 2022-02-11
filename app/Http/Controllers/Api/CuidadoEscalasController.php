<?php

namespace App\Http\Controllers\Api;

use App\Models\CuidadoEscala;
use App\Http\Controllers\Controller;
use App\Models\Cuidado;
use App\Models\Escala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CuidadoEscalasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
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
            $itens = CuidadoEscala::with($with)->where('ativo', true);
        } else {
            $itens = CuidadoEscala::where('ativo', true);
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
        // foreach ($request->cuidados as $key => $cuidado) {
        CuidadoEscala::create([
            'escala_id'  => $request->escala_id,
            'cuidado_id' => $request->cuidado_id,
            'data'       => $request->data,
            'hora'       => $request->hora,
            'status'     => $request->status,
        ]);
        // }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adicionarCuidadosNaEscala(Request $request)
    {
        // return $request;
        foreach ($request->cuidados as $key => $cuidado) {
            CuidadoEscala::firstOrCreate(
                [
                    'escala_id'  => $request->escala_id,
                    'cuidado_id' => $cuidado['id'],
                ],
                [
                    'data'       => null,
                    'hora'       => null,
                    'status'     => false,
                ]
            );
        }
        $escala = Escala::find($request->escala_id);
        return $escala->cuidados;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CuidadoEscala  $cuidadoEscala
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, CuidadoEscala $cuidadoEscala)
    {
        $iten = $cuidadoEscala;

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
     * @param  \App\CuidadoEscala  $cuidadoEscala
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CuidadoEscala $cuidadoEscala)
    {
        // $cuidadoEscala->escala_id  = $request['escala_id'];
        // $cuidadoEscala->cuidado_id = $request['cuidado_id'];
        // $cuidadoEscala->data       = $request['data'];
        // $cuidadoEscala->hora       = $request['hora'];
        // $cuidadoEscala->status     = $request['status'];
        // return $request;
        DB::transaction(function () use ($request, $cuidadoEscala) {
            $cuidadoEscala->update($request->all());
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CuidadoEscala  $cuidadoEscala
     * @return \Illuminate\Http\Response
     */
    public function destroy(CuidadoEscala $cuidadoEscala)
    {
        $cuidadoEscala->ativo = false;
        $cuidadoEscala->save();
    }
}
