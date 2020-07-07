<?php

namespace App\Http\Controllers\Api;

use App\CuidadoEscala;
use App\Http\Controllers\Controller;
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
        $itens = new CuidadoEscala();

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = CuidadoEscala::where(
                        ($where['coluna']) ? $where['coluna'] : 'id',
                        ($where['expressao']) ? $where['expressao'] : 'like',
                        ($where['valor']) ? $where['valor'] : '%'
                    );
                } else {
                    $itens->where(
                        ($where['coluna']) ? $where['coluna'] : 'id',
                        ($where['expressao']) ? $where['expressao'] : 'like',
                        ($where['valor']) ? $where['valor'] : '%'
                    );
                }
            }
        } else {
            $itens = CuidadoEscala::where('id', 'like', '%');
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
        //
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
        $cuidadoEscala->delete();
    }
}
