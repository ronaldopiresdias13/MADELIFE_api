<?php

namespace App\Http\Controllers\Api;

use App\Cotacao;
use App\CotacaoProduto;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CotacoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = new Cotacao();

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Cotacao::where(
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
            $itens = Cotacao::where('id', 'like', '%');
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
        $cotacao = Cotacao::create([
            'codigo'          => $request['codigo'],
            'profissional_id' => $request['profissional_id'],
            'empresa_id'      => $request['empresa_id'],
            'observacao'      => $request['observacao'],
            'situacao'        => $request['situacao'],
            'motivo'          => $request['motivo'],
        ]);
        if ($request['produtos']) {
            foreach ($request['produtos'] as $key => $produto) {
                $cotacao_produto = CotacaoProduto::create([
                    'cotacao_id'          => $cotacao->id,
                    'produto_id'          => $produto['id'],
                    'fornecedor_id'       => $produto['pivot']['fornecedor_id'],
                    'unidademedida'       => $produto['pivot']['unidademedida'],
                    'quantidade'          => $produto['pivot']['quantidade'],
                    'quantidadeembalagem' => $produto['pivot']['quantidadeembalagem'],
                    'quantidadetotal'     => $produto['pivot']['quantidadetotal'],
                    'valorunitario'       => $produto['pivot']['valorunitario'],
                    'valortotal'          => $produto['pivot']['valortotal'],
                    'formapagamento'      => $produto['pivot']['formapagamento'],
                    'prazoentrega'        => $produto['pivot']['prazoentrega'],
                    'observacao'          => $produto['pivot']['observacao'],
                    'situacao'            => $produto['pivot']['situacao']
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cotacao  $cotacao
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Cotacao $cotacao)
    {
        $iten = $cotacao;

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
     * @param  \App\Cotacao  $cotacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cotacao $cotacao)
    {
        $cotacao->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cotacao  $cotacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cotacao $cotacao)
    {
        $cotacao->delete();
    }
}
