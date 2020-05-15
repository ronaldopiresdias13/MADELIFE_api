<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Marca;
use Illuminate\Http\Request;

class MarcasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $itens = new Marca;
        
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
        $marca = new Marca;
        $marca->descricao = $request->descricao;
        $marca->empresa = $request->empresa;
        $marca->status = $request->staus; 
        $marca->save(); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function show(Marca $marca)
    {
        return $marca;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Marca $marca)
    {
        $marca->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy(Marca $marca)
    {
        $marca->delete();
    }
}
