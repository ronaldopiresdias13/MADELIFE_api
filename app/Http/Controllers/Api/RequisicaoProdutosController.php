<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Produto;
use App\RequisicaoProduto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequisicaoProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RequisicaoProduto::all();
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
     * @param  \App\RequisicaoProduto  $requisicaoProduto
     * @return \Illuminate\Http\Response
     */
    public function show(RequisicaoProduto $requisicaoProduto)
    {
        return $requisicaoProduto;
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
        $requisicaoProduto->update($request->all());
        if ($request["status"] === "Aprovado") {
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
        // $requisicaoProduto->delete();
    }
}
