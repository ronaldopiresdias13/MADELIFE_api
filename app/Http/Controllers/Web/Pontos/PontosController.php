<?php

namespace App\Http\Controllers\Web\Pontos;

use App\Http\Controllers\Controller;
use App\Models\Escala;
use App\Models\Ponto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Boolean;

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ponto  $ponto
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
     * @param  \App\Models\Ponto  $ponto
     * @return \Illuminate\Http\Response
     */
    public function correcaoPontos(Request $request, Escala $escala, Boolean $tipo)
    {
        $t = $tipo ? 'Check-out' : 'Check-in';
        $ponto = new Ponto();

        DB::transaction(function () use ($ponto, $escala, $t, $request) {
            $ponto = Ponto::updateOrCreate(
                [
                    'escala_id'  => $escala,
                    'tipo'       => $t,
                ],
                [
                    'empresa_id' => $escala->empresa_id,
                    'latitude'   => $request->latitude,
                    'longitude'  => $request->longitude,
                    'data'       => $request->data,
                    'hora'       => $request->hora,
                    'observacao' => $request->observacao,
                    'status'     => 1,
                ]
            );
        });
        return $ponto;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ponto  $ponto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ponto $ponto)
    {
        //
    }
}
