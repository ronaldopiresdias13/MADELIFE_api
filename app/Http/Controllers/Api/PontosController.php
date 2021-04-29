<?php

namespace App\Http\Controllers\Api;

use App\Models\Escala;
use App\Http\Controllers\Controller;
use App\Models\Ponto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PontosController extends Controller
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
            $itens = Ponto::with($with)->where('ativo', true);
        } else {
            $itens = Ponto::where('ativo', true);
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
        DB::transaction(function () use ($request) {
            $user = $request->user();
            $empresa_id = $user->pessoa->profissional->empresa_id;
            $ponto = Ponto::create(
                [
                    'empresa_id' => $empresa_id,
                    'escala_id' => $request->escala_id,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'data' => $request->data,
                    'hora' => $request->hora,
                    'tipo' => $request->tipo,
                    'observacao' => $request->observacao,
                    'status' => $request->status
                ]
            );
            return $ponto;
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ponto  $ponto
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Ponto $ponto)
    {
        $iten = $ponto;

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
     * @param  \App\Ponto  $ponto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ponto $ponto)
    {
        $ponto->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ponto  $ponto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ponto $ponto)
    {
        $ponto->ativo = false;
        $ponto->save();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Escala  $escala
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkin(Escala $escala, Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $ponto = Ponto::where('escala_id', $request->escala_id)
            ->where('tipo', 'Check-in')->first();
        if ($ponto) {
            return response()->json('Você já possui Check-in nessa escala!', 400)->header('Content-Type', 'text/plain');
        } else {
            // DB::transaction(function () use ($request) {
            $p = Ponto::create(
                [
                    'empresa_id' => $empresa_id,
                    'escala_id'  => $request->escala_id,
                    'latitude'   => $request->latitude,
                    'longitude'  => $request->longitude,
                    'data'       => $request->data,
                    'hora'       => $request->hora,
                    'tipo'       => 'Check-in',
                    'observacao' => $request->observacao,
                    'status'     => $request->status,
                ]
            );
            return $p;
            // });
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Escala  $escala
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkout(Escala $escala, Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $ponto = Ponto::where('escala_id', $request->escala_id)
            ->where('tipo', 'Check-in')->first();
        if ($ponto) {
            $ponto = Ponto::where('escala_id', $request->escala_id)
                ->where('tipo', 'Check-out')->first();
            if ($ponto) {
                return response()->json('Você já possui Check-out nessa escala!', 400)
                    ->header('Content-Type', 'text/plain');
            } else {
                $p = Ponto::create(
                    [
                        'empresa_id' => $empresa_id,
                        'escala_id'  => $request->escala_id,
                        'latitude'   => $request->latitude,
                        'longitude'  => $request->longitude,
                        'data'       => $request->data,
                        'hora'       => $request->hora,
                        'tipo'       => 'Check-out',
                        'observacao' => $request->observacao,
                        'status'     => $request->status,
                    ]
                );

                $escala->status = true;
                $escala->save();
                return $p;
            }
        } else {
            return response()->json('Você não realizou Check-in nessa escala!', 400)
                ->header('Content-Type', 'text/plain');
        }
    }
    public function buscaPontosPorIdEscala(Escala $escala)
    {
        return Ponto::Where('escala_id', $escala->id)->get();
    }
}
