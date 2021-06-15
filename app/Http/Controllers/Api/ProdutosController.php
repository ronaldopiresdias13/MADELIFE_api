<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
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
            $itens = Produto::with($with)->where('ativo', true);
        } else {
            $itens = Produto::where('ativo', true);
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
        DB::transaction(function () use ($request) {
            $produto = new Produto();
            $produto->descricao         = $request->descricao;
            $produto->empresa_id        = $request->empresa_id;
            $produto->tipoproduto_id    = $request->tipoproduto_id;
            $produto->codigo            = $request->codigo;
            $produto->unidademedida_id  = $request->unidademedida_id;
            $produto->codigobarra       = $request->codigobarra;
            $produto->validade          = $request->validade;
            $produto->grupo             = $request->grupo;
            $produto->observacoes       = $request->observacoes;
            $produto->valorcusto        = $request->valorcusto;
            $produto->valorvenda        = $request->valorvenda;
            $produto->ultimopreco       = $request->ultimopreco;
            $produto->estoqueminimo     = $request->estoqueminimo;
            $produto->estoquemaximo     = $request->estoquemaximo;
            $produto->quantidadeestoque = $request->quantidadeestoque;
            $produto->armazem           = $request->armazem;
            $produto->localizacaofisica = $request->localizacaofisica;
            $produto->datacompra        = $request->datacompra;
            $produto->marca_id          = $request->marca_id;
            $produto->desvalorizacao    = $request->desvalorizacao;
            $produto->valorfinal        = $request->valorfinal;
            $produto->tipo              = $request->tipo;
            $produto->codtuss           = $request->codtuss;
            $produto->categoria         = $request->categoria ? $request->categoria : null;
            $produto->save();
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Produto $produto)
    {
        $iten = $produto;

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
     * @param  \App\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Produto $produto)
    {
        $produto->descricao         = $request->descricao;
        $produto->empresa_id        = $request->empresa_id;
        $produto->tipoproduto_id    = $request->tipoproduto_id;
        $produto->codigo            = $request->codigo;
        $produto->unidademedida_id  = $request->unidademedida_id;
        $produto->codigobarra       = $request->codigobarra;
        $produto->validade          = $request->validade;
        $produto->grupo             = $request->grupo;
        $produto->observacoes       = $request->observacoes;
        $produto->valorcusto        = $request->valorcusto;
        $produto->valorvenda        = $request->valorvenda;
        $produto->ultimopreco       = $request->ultimopreco;
        $produto->estoqueminimo     = $request->estoqueminimo;
        $produto->estoquemaximo     = $request->estoquemaximo;
        $produto->quantidadeestoque = $request->quantidadeestoque;
        $produto->armazem           = $request->armazem;
        $produto->localizacaofisica = $request->localizacaofisica;
        // $produto->fornecedor_id = $request->fornecedor_id;
        $produto->datacompra        = $request->datacompra;
        $produto->marca_id          = $request->marca_id;
        $produto->desvalorizacao    = $request->desvalorizacao;
        $produto->valorfinal        = $request->valorfinal;
        $produto->tipo              = $request->tipo;
        $produto->codtuss           = $request->codtuss;
        $produto->categoria         = $request->categoria ? $request->categoria : null;
        $produto->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Produto $produto)
    {
        $produto->ativo = false;
        $produto->save();
    }
}
