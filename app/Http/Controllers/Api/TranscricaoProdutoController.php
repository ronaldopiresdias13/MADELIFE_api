<?php

namespace App\Http\Controllers\api;

use App\Models\TranscricaoProduto;
use App\Http\Controllers\Controller;
use App\Models\Transcricao;
use Illuminate\Http\Request;

class TranscricaoProdutoController extends Controller
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TranscricaoProduto  $transcricao_produto
     * @return \Illuminate\Http\Response
     */
    public function destroy(TranscricaoProduto $transcricao_produto)
    {
        $transcricao_produto->ativo = false;
        $transcricao_produto->save();
    }
}
