<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tipoproduto;
use Illuminate\Http\Request;

class TipoprodutosController extends Controller
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
                    $itens = Tipoproduto::where(
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
            $itens = Tipoproduto::where('id', 'like', '%');
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
                foreach ($request['adicionais'] as $key => $adicional) {
                    if (is_string($adicional)) {
                        $iten[$adicional];
                    } else {
                        $iten2 = $iten;
                        foreach ($adicional as $key => $a) {
                            if ($key == 0) {
                                if ($iten[0] == null) {
                                    $iten2 = $iten[$a];
                                }
                                else {
                                    foreach ($iten as $key => $i) {
                                        $i[$a];
                                    }
                                }
                            } else {
                                if ($iten2[0] == null) {
                                    $iten2 = $iten2[$a];
                                } else {
                                    foreach ($iten2 as $key => $i) {
                                        $i[$a];
                                    }
                                }
                            }
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
        // $tipoproduto = new Tipoproduto;
        // $tipoproduto->descricao = $request->descricao;
        // $tipoproduto->empresa_id = $request->empresa_id;
        // $tipoproduto->status = $request->status; 
        // $tipoproduto->save();
        Tipoproduto::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tipoproduto  $tipoproduto
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Tipoproduto $tipoproduto)
    {
        $iten = $tipoproduto;

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
     * @param  \App\Tipoproduto  $tipoproduto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tipoproduto $tipoproduto)
    {
        $tipoproduto->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tipoproduto  $tipoproduto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tipoproduto $tipoproduto)
    {
        $tipoproduto->delete();
    }
    
    public function migracao(Request $request)
    {
        // dd($request);
        $tipo = new Tipoproduto;
        $tipo->descricao = $request->descricao;
        $tipo->status = true;
        $tipo->empresa_id = 1;
        $tipo->save();
        // Tipoproduto::create($request->all());
    }
}
