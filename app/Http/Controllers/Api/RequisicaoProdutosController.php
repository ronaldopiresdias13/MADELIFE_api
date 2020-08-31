<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Pessoa;
use App\Produto;
use App\Profissional;
use App\Requisicao;
use App\RequisicaoProduto;
use App\Saida;
use App\SaidaProduto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequisicaoProdutosController extends Controller
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
            $itens = RequisicaoProduto::with($with)->where('ativo', true);
        } else {
            $itens = RequisicaoProduto::where('ativo', true);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RequisicaoProduto  $requisicaoProduto
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, RequisicaoProduto $requisicaoProduto)
    {
        $iten = $requisicaoProduto;

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
     * @param  \App\RequisicaoProduto  $requisicaoProduto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RequisicaoProduto $requisicaoProduto)
    {
        $produto = Produto::find($request["produto_id"]);
        $requisicaoProduto->update([
            'requisicao_id' => $request['requisicao_id'],
            'produto_id'    => $request['produto_id'],
            'quantidade'    => $request['quantidade'],
            'observacao'    => $request['observacao'],
            'status'        => $request['status']
        ]);
        if ($request["status"] === "Aprovado") {
            $profissional = Profissional::firstWhere('pessoa_id', Pessoa::find(Requisicao::find($request['requisicao_id'])->pessoa_id)->id);
            $saidaproduto = SaidaProduto::create([
                'saida_id' => Saida::create([
                    'empresa_id'      => $profissional->empresa_id,
                    'data'            => $request['data'],
                    'descricao'       => "Requisição de Material",
                    'profissional_id' => $profissional->id
                ])->id,
                'produto_id'    => $request['produto_id'],
                'quantidade'    => $request['quantidade'],
                'lote'          => "",
                'valor'         => $produto->valorcusto,
                'ativo'         => 1
            ]);

            $produto->quantidadeestoque = $produto->quantidadeestoque - $request["quantidade"];
            $produto->update();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RequisicaoProduto  $requisicaoProduto
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequisicaoProduto $requisicaoProduto)
    {
        $requisicaoProduto->ativo = false;
        $requisicaoProduto->save();
    }
}
