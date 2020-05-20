<?php

namespace App\Http\Controllers\Api;

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
    public function index()
    {
        $itens = null;

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }
        
        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Monitoramentoescala::where(
                        ($where['coluna'   ])? $where['coluna'   ] : 'id'  ,
                        ($where['expressao'])? $where['expressao'] : 'like',
                        ($where['valor'    ])? $where['valor'    ] : '%'
                    );
                } else {
                    $itens->where(
                        ($where['coluna'   ])? $where['coluna'   ] : 'id',
                        ($where['expressao'])? $where['expressao'] : 'like',
                        ($where['valor'    ])? $where['valor'    ] : '%'
                    );
                }
            }
        } else {
            $itens = Monitoramentoescala::where('id', 'like', '%');
        }

        if ($request['order']) {
            foreach ($request['order'] as $key => $order) {
                $itens->orderBy(
                    ($order['coluna'])? $order['coluna'] : 'id',
                    ($order['tipo'  ])? $order['tipo'  ] : 'asc'
                );
            }
        }
        
        $itens = $itens->get();
        
        if ($request['adicionais']) {
            foreach ($itens as $key => $iten) {
                foreach ($request['adicionais'] as $key => $adic) {
                    if (is_string($adic)) {
                        $iten[$adic];
                    } else {
                        switch (count($adic)) {
                            case 1:
                                $iten[$adic[0]];
                                break;
                            
                            case 2:
                                $iten[$adic[0]][$adic[1]];
                                break;
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
        $monitoramentoescala = new Monitoramentoescala;
        $monitoramentoescala->escala_id = $request->escala_id;
        $monitoramentoescala->datahora = $request->datahora;
        $monitoramentoescala->pa = $request->pa;
        $monitoramentoescala->p = $request->p;
        $monitoramentoescala->t = $request->t;
        $monitoramentoescala->fr = $request->fr;
        $monitoramentoescala->sat = $request->sat;
        $monitoramentoescala->criev = $request->criev;
        $monitoramentoescala->ev = $request->ev;
        $monitoramentoescala->dieta = $request->dieta;
        $monitoramentoescala->cridieta = $request->cridieta;
        $monitoramentoescala->criliquido = $request->criliquido;
        $monitoramentoescala->liquido = $request->liquido;
        $monitoramentoescala->diurese = $request->diurese;
        $monitoramentoescala->cridiurese = $request->cridiurese;
        $monitoramentoescala->evac = $request->evac;
        $monitoramentoescala->crievac = $request->crievac;
        $monitoramentoescala->vomito = $request->vomito;
        $monitoramentoescala->crivomito = $request->crivomito;
        $monitoramentoescala->asp = $request->asp;
        $monitoramentoescala->decub = $request->decub;
        $monitoramentoescala->curativo = $request->curativo;
        $monitoramentoescala->fraldas = $request->fraldas;
        $monitoramentoescala->sondas = $request->sondas;
        $monitoramentoescala->dextro = $request->dextro;
        $monitoramentoescala->o2 = $request->o2;
        $monitoramentoescala->observacao = $request->observacao;
        $monitoramentoescala->save();
    }

    /**
     * diurese the specified resource.
     *
     * @param  \App\Monitoramentoescala  $monitoramentoescala
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Monitoramentoescala $monitoramentoescala)
    {
        $iten = $monitoramentoescala;

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['adicionais']) {
            foreach ($request['adicionais'] as $key => $adic) {
                if (is_string($adic)) {
                    $iten[$adic];
                } else {
                    switch (count($adic)) {
                        case 1:
                            $iten[$adic[0]];
                            break;
                        
                        case 2:
                            $iten[$adic[0]][$adic[1]];
                            break;
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
     * @param  \App\Monitoramentoescala  $monitoramentoescala
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Monitoramentoescala $monitoramentoescala)
    {
        $monitoramentoescala->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Monitoramentoescala  $monitoramentoescala
     * @return \Illuminate\Http\Response
     */
    public function destroy(Monitoramentoescala $monitoramentoescala)
    {
        $monitoramentoescala->delete();
    }
}
