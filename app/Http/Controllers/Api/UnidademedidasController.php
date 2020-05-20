<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Unidademedida;
use Illuminate\Http\Request;

class UnidademedidasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = null;

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }
        
        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Unidademedida::where(
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
            $itens = Unidademedida::where('id', 'like', '%');
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
        $unidademedida = new Unidademedida;
        $unidademedida->empresa_id = $request->empresa_id;
        $unidademedida->descricao = $request->descricao;
        $unidademedida->sigla = $request->sigla;
        $unidademedida->grupo = $request->grupo;
        $unidademedida->padrao = $request->padrao;
        $unidademedida->status = $request->status;
        $unidademedida->sigla = $request->sigla;
        $unidademedida->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Unidademedida  $unidademedida
     * @return \Illuminate\Http\Response
     */
    public function show(Unidademedida $unidademedida)
    {
        return $unidademedida;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Unidademedida  $unidademedida
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unidademedida $unidademedida)
    {
        $unidademedida->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Unidademedida  $unidademedida
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unidademedida $unidademedida)
    {
        $unidademedida->delete();
    }
    
    public function migracao(Request $request)
    {
        // dd($request);
        $unidade = new Unidademedida;
        $unidade->descricao = $request->descricao;
        $unidade->sigla = $request->sigla;
        $unidade->grupo = $request->grupo;
        $unidade->padrao = true;
        $unidade->status = true;
        $unidade->empresa_id = 1;
        $unidade->save();
    }
}
