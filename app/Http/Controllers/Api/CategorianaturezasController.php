<?php

namespace App\Http\Controllers\Api;

use App\Categorianatureza;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategorianaturezasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $itens = new Categorianatureza;
        
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
        $categorianatureza = new Categorianatureza;
        $categorianatureza->empresa_id = $request->empresa_id;
        $categorianatureza->descriciao = $request->descricao;
        $categorianatureza->status = $request->status;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Categorianatureza  $categorianatureza
     * @return \Illuminate\Http\Response
     */
    public function show(Categorianatureza $categorianatureza)
    {
        return $categorianatureza;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Categorianatureza  $categorianatureza
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categorianatureza $categorianatureza)
    {
        $categorianatureza->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Categorianatureza  $categorianatureza
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categorianatureza $categorianatureza)
    {
        $categorianatureza->delete();
    }
}
