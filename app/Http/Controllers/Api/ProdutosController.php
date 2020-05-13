<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Produto;
use App\Tipoproduto;
use Illuminate\Http\Request;

class ProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function show(Produto $produto)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Produto $produto)
    {
        //
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
