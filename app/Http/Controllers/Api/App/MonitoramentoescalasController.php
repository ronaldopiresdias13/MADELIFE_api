<?php

namespace App\Http\Controllers\Api\App;

use App\Escala;
use App\Http\Controllers\Controller;
use App\Monitoramentoescala;
use Illuminate\Http\Request;

class MonitoramentoescalasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listaMonitoramento(Escala $escala)
    {
        return Monitoramentoescala::where('escala_id', $escala['id'])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function salvarMonitoramento(Request $request)
    {
        $monitoramentoescala = new Monitoramentoescala();
        $monitoramentoescala->escala_id = $request->escala_id;
        $monitoramentoescala->data = $request->data;
        $monitoramentoescala->hora = $request->hora;
        $monitoramentoescala->pa = $request->pa;
        $monitoramentoescala->p = $request->p;
        $monitoramentoescala->t = $request->t;
        $monitoramentoescala->fr = $request->fr;
        $monitoramentoescala->sat = $request->sat;
        $monitoramentoescala->criev      = $request->criev;
        $monitoramentoescala->ev         = $request->ev;
        $monitoramentoescala->dieta      = $request->dieta;
        $monitoramentoescala->cridieta   = $request->cridieta;
        $monitoramentoescala->criliquido = $request->criliquido;
        $monitoramentoescala->liquido    = $request->liquido;
        $monitoramentoescala->diurese    = $request->diurese;
        $monitoramentoescala->cridiurese = $request->cridiurese;
        $monitoramentoescala->evac       = $request->evac;
        $monitoramentoescala->crievac    = $request->crievac;
        $monitoramentoescala->vomito     = $request->vomito;
        $monitoramentoescala->crivomito  = $request->crivomito;
        $monitoramentoescala->asp        = $request->asp;
        $monitoramentoescala->decub      = $request->decub;
        $monitoramentoescala->curativo   = $request->curativo;
        $monitoramentoescala->fraldas    = $request->fraldas;
        $monitoramentoescala->sondas     = $request->sondas;
        $monitoramentoescala->dextro     = $request->dextro;
        $monitoramentoescala->o2         = $request->o2;
        $monitoramentoescala->observacao = $request->observacao;
        $monitoramentoescala->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Monitoramentoescala  $monitoramentoescala
     * @return \Illuminate\Http\Response
     */
    public function show(Monitoramentoescala $monitoramentoescala)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Monitoramentoescala  $monitoramentoescala
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Monitoramentoescala $monitoramentoescala)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Monitoramentoescala  $monitoramentoescala
     * @return \Illuminate\Http\Response
     */
    public function destroy(Monitoramentoescala $monitoramentoescala)
    {
        //
    }
}
