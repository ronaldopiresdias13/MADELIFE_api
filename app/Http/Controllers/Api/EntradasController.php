<?php

namespace App\Http\Controllers\Api;

use App\Entrada;
use App\EntradaProduto;
use App\Estoque;
use App\Http\Controllers\Controller;
use App\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntradasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = Entrada::where('ativo', true);

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                $itens->where(
                    ($where['coluna']) ? $where['coluna'] : 'id',
                    ($where['expressao']) ? $where['expressao'] : 'like',
                    ($where['valor']) ? $where['valor'] : '%'
                );
            }
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
        DB::transaction(function () use ($request) {
            $entrada = Entrada::create([
                'empresa_id'      => $request['empresa_id'],
                'data'            => $request['data'],
                'numeronota'      => $request['numeronota'],
                'fornecedor_id'   => $request['fornecedor_id']
            ]);


            foreach ($request['produtos'] as $key => $produto) {
                $prod = Produto::find($produto['produto_id']);
                if ($prod->controlelote) {
                    if ($produto['lote']) {
                        $estoque = Estoque::firstWhere('lote', $produto['lote']);
                        if ($estoque) {
                            $atualiza_quantidade_estoque = Estoque::firstWhere('lote', $produto['lote']);
                            $atualiza_quantidade_estoque->quantidade = $atualiza_quantidade_estoque->quantidade + $produto['quantidade'];
                            $atualiza_quantidade_estoque->update();
                        } else {
                            $nova_estoque = Estoque::create([
                                'produto_id' => $produto['produto_id'],
                                'unidade'    => $prod->unidademedida_id,
                                'quantidade' => $produto['quantidade'],
                                'lote'       => $produto['lote'],
                                'validade'   => $produto['validade'],
                                'ativo'      => 1
                            ]);
                        }
                        // return $estoque;
                    }
                }
                // return $prod;

                $entrada_produto = EntradaProduto::create([
                    'entrada_id'    => 5,
                    // 'entrada_id'    => $entrada->id,
                    'produto_id'    => $produto['produto_id'],
                    'quantidade'    => $produto['quantidade'],
                    'lote'          => $produto['lote'],
                    'validade'      => $produto['validade'],
                    'valor'         => $produto['valor'],
                    'ativo'         => 1
                ]);
                $prod->quantidadeestoque = $prod->quantidadeestoque + $produto["quantidade"];
                $prod->update();
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Entrada $entrada)
    {
        $iten = $entrada;

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
     * @param  \App\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Entrada $entrada)
    {
        DB::transaction(function () use ($request, $entrada) {
            $entrada->update($request->all());
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function destroy(Entrada $entrada)
    {
        $entrada->ativo = false;
        $entrada->save();
    }
}
