<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\PrestadorFormacao;
use Illuminate\Http\Request;

class PrestadorFormacaoController extends Controller
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
    public function newPrestadorFormacao(Request $request)
    {
        PrestadorFormacao::updateOrCreate(
            [
                'prestador_id' => $request['prestador_id'],
                'formacao_id'  => $request['formacao_id'],
            ],
            [
                'ativo'  => true
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PrestadorFormacao  $prestadorFormacao
     * @return \Illuminate\Http\Response
     */
    public function show(PrestadorFormacao $prestadorFormacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PrestadorFormacao  $prestadorFormacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PrestadorFormacao $prestadorFormacao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PrestadorFormacao  $prestadorFormacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(PrestadorFormacao $prestadorFormacao)
    {
        //
    }
}
