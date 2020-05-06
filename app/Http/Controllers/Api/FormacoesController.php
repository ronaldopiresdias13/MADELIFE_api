<?php

namespace App\Http\Controllers\Api;

use App\Formacao;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormacoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Formacao::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formacao = new Formacao;
        $formacao->cbo       = $request->cbo;
        $formacao->descricao = $request->descricao;
        $formacao->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Formacao  $formacao
     * @return \Illuminate\Http\Response
     */
    public function show(Formacao $formacao)
    {
        return $formacao;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Formacao  $formacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Formacao $formacao)
    {
        $formacao->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Formacao  $formacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Formacao $formacao)
    {
        $formacao->delete();
    }
}
