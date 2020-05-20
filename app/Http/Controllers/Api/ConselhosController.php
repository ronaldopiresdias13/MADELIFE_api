<?php

namespace App\Http\Controllers\Api;

use App\Conselho;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConselhosController extends Controller
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
                    $itens = Conselho::where(
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
            $itens = Conselho::where('id', 'like', '%');
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
        $conselho = new Conselho;
        $conselho->instituicao = $request->instituicao;
        $conselho->uf = $request->uf;
        $conselho->numero = $request->numero;
        $conselho->pessoa = $request->pessoa;
        $conselho->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Conselho  $conselho
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Conselho $conselho)
    {
        $iten = $conselho;

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
     * @param  \App\Conselho  $conselho
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Conselho $conselho)
    {
        $conselho->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Conselho  $conselho
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conselho $conselho)
    {
        $conselho->delete();
    }
}
