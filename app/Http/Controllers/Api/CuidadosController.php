<?php

namespace App\Http\Controllers\Api;

use App\Cuidado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CuidadosController extends Controller
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
                    $itens = Cuidado::where(
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
            $itens = Cuidado::where('id', 'like', '%');
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
        $cuidado = new Cuidado;
        $cuidado->descricao = $request->descricao;
        $cuidado->codigo = $request->codigo;
        $cuidado->empresa_id = $request->empresa_id;
        $cuidado->status = $request->staus; 
        $cuidado->save(); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cuidado  $cuidado
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Cuidado $cuidado)
    {
        $iten = $cuidado;

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
     * @param  \App\Cuidado  $cuidado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cuidado $cuidado)
    {
        $cuidado->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cuidado  $cuidado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cuidado $cuidado)
    {
        $cuidado->delete();
    }

    public function migracao(Request $request)
    {
        $cuidado = Cuidado::firstOrCreate(
            [
                'codigo' => $request['codigo'],
            ],
            [
                'descricao' => $request['descricao'],
                'empresa_id' => 1,
                'status' => true,
            ]);
    }
}
