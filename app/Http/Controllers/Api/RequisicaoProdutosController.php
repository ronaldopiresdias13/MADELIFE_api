<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\RequisicaoProduto;
use Illuminate\Http\Request;

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
        $requisicaoProduto->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RequisicaoProduto  $requisicaoProduto
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequisicaoProduto $requisicaoProduto)
    {
        $requisicaoProduto->delete();
    }
}
