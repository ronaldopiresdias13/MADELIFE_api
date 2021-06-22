<?php

namespace App\Http\Controllers\Api\App\v3_0_25;

use App\Models\Escala;
use App\Http\Controllers\Controller;
use App\Models\Monitoramentoescala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        DB::transaction(function () use ($request) {
            $monitoramentoescala = new Monitoramentoescala();
            $monitoramentoescala->escala_id  = $request->escala_id;
            $monitoramentoescala->data       = $request->data;
            $monitoramentoescala->hora       = $request->hora;
            $monitoramentoescala->pa         = $request->pa;
            $monitoramentoescala->p          = $request->p;
            $monitoramentoescala->t          = $request->t;
            $monitoramentoescala->fr         = $request->fr;
            $monitoramentoescala->sat        = $request->sat;
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
            $monitoramentoescala->asp        = $request->asp      ? $request->asp      : false;
            $monitoramentoescala->inal       = $request->inal      ? $request->inal      : false;
            $monitoramentoescala->decub      = $request->decub    ? $request->decub    : false;
            $monitoramentoescala->curativo   = $request->curativo ? $request->curativo : false;
            $monitoramentoescala->fraldas    = $request->fraldas  ? $request->fraldas  : false;
            $monitoramentoescala->sondas     = $request->sondas   ? $request->sondas   : false;
            $monitoramentoescala->dextro     = $request->dextro   ? $request->dextro   : false;
            $monitoramentoescala->o2         = $request->o2       ? $request->o2       : false;
            $monitoramentoescala->observacao = $request->observacao;
            $monitoramentoescala->save();
        });

        return response()->json([
            'toast' => [
                'text' => 'Monitoramento realizado com sucesso!',
                'color' => 'success',
                'duration' => 2000
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllMonitoramentosByEscalaId(Escala $escala)
    {
        // return $escala->ordemservico_id;
        return DB::table('monitoramentoescalas')
            ->join('escalas', 'escalas.id', '=', 'monitoramentoescalas.escala_id')
            ->join('ordemservicos', 'ordemservicos.id', '=', 'escalas.ordemservico_id')
            ->where('escalas.ordemservico_id', $escala->ordemservico_id)
            ->select('monitoramentoescalas.*')
            // ->groupBy('relatorios.nome')
            ->orderBy('monitoramentoescalas.data', 'desc')
            ->limit(20)
            ->get();
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
