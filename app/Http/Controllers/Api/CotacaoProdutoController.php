<?php

namespace App\Http\Controllers\Api;

use App\CotacaoProduto;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CotacaoProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = new CotacaoProduto();

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = CotacaoProduto::where(
                        ($where['coluna']) ? $where['coluna'] : 'id',
                        ($where['expressao']) ? $where['expressao'] : 'like',
                        ($where['valor']) ? $where['valor'] : '%'
                    );
                } else {
                    $itens->where(
                        ($where['coluna']) ? $where['coluna'] : 'id',
                        ($where['expressao']) ? $where['expressao'] : 'like',
                        ($where['valor']) ? $where['valor'] : '%'
                    );
                }
            }
        } else {
            $itens = CotacaoProduto::where('id', 'like', '%');
        }

        if ($request['order']) {
            foreach ($request['order'] as $key => $order) {
                $itens->orderBy(
                    ($order['coluna']) ? $order['coluna'] : 'id',
                    ($order['tipo']) ? $order['tipo'] : 'asc'
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
                                } else {
                                    foreach ($iten as $key => $i) {
                                        $i[$a];
                                    }
                                }
                            } else {
                                if ($iten2 != null) {
                                    if ($iten2->count() > 0) {
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
        CotacaoProduto::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CotacaoProduto  $cotacaoProduto
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, CotacaoProduto $cotacaoProduto)
    {
        $iten = $cotacaoProduto;

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['adicionais']) {
            foreach ($request['adicionais'] as $key => $adicional) {
                if (is_string($adicional)) {
                    $iten[$adicional];
                } else {
                    $iten2 = $iten;
                    foreach ($adicional as $key => $a) {
                        if ($key == 0) {
                            if ($iten[0] == null) {
                                $iten2 = $iten[$a];
                            } else {
                                foreach ($iten as $key => $i) {
                                    $i[$a];
                                }
                            }
                        } else {
                            if ($iten2 != null) {
                                if ($iten2->count() > 0) {
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
        }

        return $iten;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CotacaoProduto  $cotacaoProduto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CotacaoProduto $cotacaoProduto)
    {
        // return $request;
        $cotacaoProduto->cotacao_id;
        $cotacaoProduto->produto_id;
        $cotacaoProduto->fornecedor_id;
        $cotacaoProduto->unidademedida;
        $cotacaoProduto->quantidade;
        $cotacaoProduto->quantidadeembalagem;
        $cotacaoProduto->quantidadetotal;
        $cotacaoProduto->valorunitario;
        $cotacaoProduto->valortotal;
        $cotacaoProduto->formapagamento;
        $cotacaoProduto->prazoentrega;
        $cotacaoProduto->observacao;
        $cotacaoProduto->situacao;
        $cotacaoProduto->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CotacaoProduto  $cotacaoProduto
     * @return \Illuminate\Http\Response
     */
    public function destroy(CotacaoProduto $cotacaoProduto)
    {
        $cotacaoProduto->delete();
    }
}
