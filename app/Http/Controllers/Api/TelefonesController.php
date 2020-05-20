<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Telefone;
use Illuminate\Http\Request;

class TelefonesController extends Controller
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
                    $itens = Telefone::where(
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
            $itens = Telefone::where('id', 'like', '%');
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
        $telefone = new Telefone;
        $telefone->telefone = $request->telefone;
        $telefone->tipo = $request->tipo;
        $telefone->descricao = $request->descricao;
        $telefone->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Telefone  $telefone
     * @return \Illuminate\Http\Response
     */
    public function show(Telefone $telefone)
    {
        return $telefone;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Telefone  $telefone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Telefone $telefone)
    {
        $telefone->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Telefone  $telefone
     * @return \Illuminate\Http\Response
     */
    public function destroy(Telefone $telefone)
    {
        $telefone->delete();
    }
}
