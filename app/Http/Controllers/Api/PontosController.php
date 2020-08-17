<?php

namespace App\Http\Controllers\Api;

use App\Escala;
use App\Http\Controllers\Controller;
use App\Ponto;
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
        $itens = Ponto::where('ativo', true);

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
        $ponto = new Ponto();
        $ponto->empresa_id = $request->empresa_id;
        $ponto->escala_id = $request->escala_id;
        $ponto->latitude = $request->latitude;
        $ponto->longitude = $request->longitude;
        $ponto->data = $request->data;
        $ponto->hora = $request->hora;
        $ponto->tipo = $request->tipo;
        $ponto->observacao = $request->observacao;
        $ponto->status = $request->status;
        $ponto->save();
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
        $ponto = Ponto::where('escala_id', $request->escala_id)
            ->where('tipo', 'Check-in')->first();
        if ($ponto) {
            return response()->json('Você já possui Check-in nessa escala!', 400)->header('Content-Type', 'text/plain');
        } else {
            DB::transaction(function () use ($request) {
                Ponto::create(
                    [
                        'empresa_id' => $request->empresa_id,
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
            });
            return response()->json('Check-in realizado com Sucesso!', 200)->header('Content-Type', 'text/plain');
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
        $ponto = Ponto::where('escala_id', $request->escala_id)
            ->where('tipo', 'Check-in')->first();
        if ($ponto) {
            $ponto = Ponto::where('escala_id', $request->escala_id)
                ->where('tipo', 'Check-out')->first();
            if ($ponto) {
                return response()->json('Você já possui Check-out nessa escala!', 400)
                    ->header('Content-Type', 'text/plain');
            } else {
                DB::transaction(function () use ($request) {
                    Ponto::create(
                        [
                            'empresa_id' => $request->empresa_id,
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
                });
                $escala->status = true;
                $escala->save();
                return response()->json('Check-out realizado com Sucesso!', 200)
                    ->header('Content-Type', 'text/plain');
            }
        } else {
            return response()->json('Você não realizou Check-in nessa escala!', 400)
                ->header('Content-Type', 'text/plain');
        }
    }
}
