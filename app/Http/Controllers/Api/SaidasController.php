<?php

namespace App\Http\Controllers\Api;

use App\Models\Estoque;
use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\Saida;
use App\Models\SaidaProduto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaidasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $with = [];

        if ($request['adicionais']) {
            foreach ($request['adicionais'] as $key => $adicional) {
                if (is_string($adicional)) {
                    array_push($with, $adicional);
                } else {
                    $filho = '';
                    foreach ($adicional as $key => $a) {
                        if ($key == 0) {
                            $filho = $a;
                        } else {
                            $filho = $filho . '.' . $a;
                        }
                    }
                    array_push($with, $filho);
                }
            }
            $itens = Saida::with($with)->where('ativo', true);
        } else {
            $itens = Saida::where('ativo', true);
        }

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
        // DB::transaction(function () use ($request) {
        $saida = Saida::create([
            'empresa_id'      => $request['empresa_id'],
            'data'            => $request['data'],
            'descricao'       => $request['descricao'],
            'profissional_id' => $request['profissional_id']
        ]);

        // return $saida;

        foreach ($request['produtos'] as $key => $produto) {
            $prod = Produto::find($produto["produto_id"]);
            if ($prod->controlelote) {
                if ($produto['lote']) {
                    $estoque = Estoque::firstWhere('lote', $produto['lote']);
                    if ($estoque) {
                        $atualiza_quantidade_estoque = Estoque::firstWhere('lote', $produto['lote']);
                        $atualiza_quantidade_estoque->quantidade = $atualiza_quantidade_estoque->quantidade - $produto['quantidade'];
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
            $saida_produto = SaidaProduto::create([
                'saida_id'      => $saida->id,
                'produto_id'    => $produto['produto_id'],
                'quantidade'    => $produto['quantidade'],
                'lote'          => $produto['lote'],
                'valor'         => $produto['valor'],
                'ativo'         => 1
            ]);
            $prod->quantidadeestoque = $prod->quantidadeestoque - $produto["quantidade"];
            $prod->update();
        }
        return response()->json([
            'alert' => [
                'title' => 'Parabéns!',
                'text' => 'Salvo com sucesso!'
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
        // });
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Saida  $saida
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Saida $saida)
    {
        $iten = $saida;

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
     * @param  \App\Saida  $saida
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Saida $saida)
    {
        DB::transaction(function () use ($request, $saida) {
            $saida->update($request->all());
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Saida  $saida
     * @return \Illuminate\Http\Response
     */
    public function destroy(Saida $saida)
    {
        $saida->ativo = false;
        $saida->save();
    }
}
