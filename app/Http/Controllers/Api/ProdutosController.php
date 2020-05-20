<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Produto;
use App\Pessoa;
use App\Tipoproduto;
use Illuminate\Http\Request;

class ProdutosController extends Controller
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
                    $itens = Produto::where(
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
            $itens = Produto::where('id', 'like', '%');
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
        $produto = new Produto;
        $produto->descricao = $request->descricao;
        $produto->empresa_id = 1;
        $produto->tipoproduto_id = $request->tipoproduto_id;
        $produto->codigo = $request->codigo;
        $produto->unidademedida_id = $request->unidademedida_id;
        $produto->codigobarra = $request->codigobarra;
        $produto->validade = $request->validade;
        $produto->grupo = $request->grupo;
        $produto->observacoes = $request->observacoes;
        $produto->valorcusto = $request->valorcusto;
        $produto->valorvenda = $request->valorvenda;
        $produto->ultimopreco = $request->ultimopreco;
        $produto->estoqueminimo = $request->estoqueminimo;
        $produto->estoquemaximo = $request->estoquemaximo;
        $produto->quantidadeestoque = $request->quantidadeestoque;
        $produto->armazem = $request->armazem;
        $produto->localizacaofisica = $request->localizacaofisica;
        $produto->fornecedor_id = $request->fornecedor_id ? Pessoa::find($request->fornecedor_id)->fornecedor->id : null;
        $produto->datacompra = $request->datacompra;
        $produto->marca_id = $request->marca_id; 
        $produto->desvalorizacao = $request->desvalorizacao; 
        $produto->valorfinal = $request->valorfinal; 
        $produto->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function show(Produto $produto)
    {
        return $produto;
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
        $produto->descricao = $request->descricao;
        $produto->empresa_id = 1;
        $produto->tipoproduto_id = $request->tipoproduto_id;
        $produto->codigo = $request->codigo;
        $produto->unidademedida_id = $request->unidademedida_id;
        $produto->codigobarra = $request->codigobarra;
        $produto->validade = $request->validade;
        $produto->grupo = $request->grupo;
        $produto->observacoes = $request->observacoes;
        $produto->valorcusto = $request->valorcusto;
        $produto->valorvenda = $request->valorvenda;
        $produto->ultimopreco = $request->ultimopreco;
        $produto->estoqueminimo = $request->estoqueminimo;
        $produto->estoquemaximo = $request->estoquemaximo;
        $produto->quantidadeestoque = $request->quantidadeestoque;
        $produto->armazem = $request->armazem;
        $produto->localizacaofisica = $request->localizacaofisica;
        $produto->fornecedor_id = $request->fornecedor_id;
        $produto->datacompra = $request->datacompra;
        $produto->marca_id = $request->marca_id; 
        $produto->desvalorizacao = $request->desvalorizacao; 
        $produto->valorfinal = $request->valorfinal; 
        $produto->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Produto $produto)
    {
        $produto->delete();
    }
    public function migracao(Request $request)
    {
        // dd($request['tipoProduto']['descricao']);
        $produto = new Produto;
        $produto->descricao = $request->descricao;
        $produto->empresa_id = 1;
        $produto->tipoproduto_id = Tipoproduto::firstWhere('descricao', $request['tipoProduto']['descricao'])->id;
        $produto->codigo = $request->codigo_Interno;
        $produto->unidademedida_id = null;
        $produto->codigobarra = $request->codigo_Barras;
        $produto->validade = $request->validade;
        $produto->grupo = $request->grupo;
        $produto->observacoes = $request->observacoes;
        $produto->valorcusto = $request->valor_Custo;
        $produto->valorvenda = $request->valor_Venda;
        $produto->ultimopreco = $request->ultimo_Preco;
        $produto->estoqueminimo = $request->estoque_Minimo;
        $produto->estoquemaximo = $request->estoque_Maximo;
        $produto->quantidadeestoque = $request->quantidade_Estoque;
        $produto->armazem = $request->armazem;
        $produto->localizacaofisica = $request->localizacao_Fisica;
        $produto->fornecedor_id = null;
        $produto->datacompra = $request->ultima_Compra;
        $produto->marca_id = null; 
        $produto->save();
    }
}
