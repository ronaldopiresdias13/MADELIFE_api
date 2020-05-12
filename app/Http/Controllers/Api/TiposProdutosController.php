<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Tipoproduto;
use Illuminate\Http\Request;

class TiposProdutosController extends Controller
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
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tipoproduto  $tipoproduto
     * @return \Illuminate\Http\Response
     */
    public function show(Tipoproduto $tipoproduto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tipoproduto  $tipoproduto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tipoproduto $tipoproduto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tipoproduto  $tipoproduto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tipoproduto $tipoproduto)
    {
        //
    }
    public function migracao(Request $request)
    {
        // dd($request);
        $tipo = new Tipoproduto;
        $tipo->descricao = $request->descricao;
        $tipo->status = true;
        $tipo->empresa_id = 1;
        $tipo->save();
    }
}
