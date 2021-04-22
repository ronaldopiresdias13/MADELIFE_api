<?php

namespace App\Http\Controllers\Web\Formacoes;

use App\Http\Controllers\Controller;
use App\Models\Formacao;
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
        return Formacao::orderBy('descricao')->get(['id', 'descricao']);
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
     * @param  \App\Models\Formacao  $formacao
     * @return \Illuminate\Http\Response
     */
    public function show(Formacao $formacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Formacao  $formacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Formacao $formacao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Formacao  $formacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Formacao $formacao)
    {
        //
    }
}
