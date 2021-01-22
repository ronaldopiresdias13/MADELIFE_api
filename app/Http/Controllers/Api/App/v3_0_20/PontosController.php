<?php

namespace App\Http\Controllers\Api\App\v3_0_20;

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
    public function index()
    {
        //
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
    public function show(Ponto $ponto)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ponto  $ponto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ponto $ponto)
    {
        //
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
            return response()->json([
                'alert' => [
                    'title' => 'Ops!',
                    'text' => 'Você já possui Check-in nessa escala!'
                ]
            ], 202)
                ->header('Content-Type', 'application/json');
        } else {
            DB::transaction(function () use ($request) {
                Ponto::firstOrCreate(
                    [
                        'escala_id'  => $request->escala_id,
                        'tipo'       => 'Check-in',
                    ],
                    [
                        'empresa_id' => $request->empresa_id,
                        'latitude'   => $request->latitude,
                        'longitude'  => $request->longitude,
                        'data'       => $request->data,
                        'hora'       => $request->hora,
                        'observacao' => $request->observacao,
                        'status'     => $request->status,
                    ]
                );
            });
            return response()->json([
                'alert' => [
                    'title' => 'Parabéns!',
                    'text' => 'Check-in realizado com Sucesso!'
                ]
            ], 200)
                ->header('Content-Type', 'application/json');
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
                    Ponto::firstOrCreate(
                        [
                            'escala_id'  => $request->escala_id,
                            'tipo'       => 'Check-out',
                        ],
                        [
                            'empresa_id' => $request->empresa_id,
                            'latitude'   => $request->latitude,
                            'longitude'  => $request->longitude,
                            'data'       => $request->data,
                            'hora'       => $request->hora,
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Escala  $escala
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assinaturacheckout(Escala $escala, Request $request)
    {
        $ponto = Ponto::where('escala_id', $request->escala_id)
            ->where('tipo', 'Check-in')->first();
        if ($ponto) {
            $ponto = Ponto::where('escala_id', $request->escala_id)
                ->where('tipo', 'Check-out')->first();
            if ($ponto) {
                return response()->json([
                    'alert' => [
                        'title' => 'Ops!',
                        'text' => 'Esta escala já foi finalizada!'
                    ]
                ], 202)->header('Content-Type', 'application/json');
            } else {
                DB::transaction(function () use ($request) {
                    Ponto::firstOrCreate(
                        [
                            'escala_id'  => $request->escala_id,
                            'tipo'       => 'Check-out',
                        ],
                        [
                            'empresa_id' => $request->empresa_id ? $request->empresa_id : 1,
                            'latitude'   => $request->latitude,
                            'longitude'  => $request->longitude,
                            'data'       => $request->data,
                            'hora'       => $request->hora,
                            'observacao' => $request->observacao,
                            'status'     => $request->status,
                        ]
                    );
                });
                $escala->status              = true;
                $escala->assinaturaprestador = $request->assinaturaprestador;
                $escala->save();
                return response()->json([
                    'alert' => [
                        'title' => 'Parabéns!',
                        'text' => 'Check-out realizado com Sucesso!'
                    ]
                ], 200)
                    ->header('Content-Type', 'application/json');
            }
        } else {
            return response()->json([
                'alert' => [
                    'title' => 'Ops!',
                    'text' => 'Você não realizou Check-in nessa escala!'
                ]
            ], 202)
                ->header('Content-Type', 'application/json');
        }
    }
}
