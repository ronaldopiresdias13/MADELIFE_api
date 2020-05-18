<?php

namespace App\Http\Controllers\Api;

use App\Escala;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EscalasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $itens = new Escala;
        if ($request->where) {
            foreach ($request->where as $key => $where) {
                $itens->where(
                    ($where['coluna'   ])? $where['coluna'   ] : 'id',
                    ($where['expressao'])? $where['expressao'] : 'like',
                    ($where['valor'    ])? $where['valor'    ] : '%'
                );
            }
        }

        if ($request->order) {
            foreach ($request->order as $key => $order) {
                $itens->orderBy(
                    ($order['coluna'])? $order['coluna'] : 'id',
                    ($order['tipo'  ])? $order['tipo'  ] : 'asc'
                );
            }
        }
        
        $itens = $itens->get();
        
        if ($request->adicionais) {
            foreach ($itens as $key => $iten) {
                foreach ($request->adicionais as $key => $adic) {
                    $iten[$adic];
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
        $escala = new Escala;
        $escala->empresa_id = $request->empresa_id;
        $escala->ordemservico_id = $request->ordemservico_id;
        $escala->prestador_id = $request->prestador_id;
        $escala->horaentrada = $request->horaentrada;
        $escala->horasaida = $request->horasaida;
        $escala->dataentrada = $request->dataentrada;
        $escala->datasaida = $request->datasaida;
        $escala->periodo = $request->periodo;
        $escala->assinaturaprestador = $request->assinaturaprestador;
        $escala->assinaturaresponsavel = $request->assinaturaresponsavel;
        $escala->observacao = $request->observacao;
        $escala->status = $request->status;
        $escala->folga = $request->folga;
        $escala->substituto = $request->substituto;
        $escala->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function show(Escala $escala)
    {
        return $escala;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Escala $escala)
    {
        $escala->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function destroy(Escala $escala)
    {
        $escala->delete();
    }
}
