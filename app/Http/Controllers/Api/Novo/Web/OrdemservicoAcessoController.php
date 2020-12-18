<?php

namespace App\Http\Controllers\Api\Novo\Web;

use App\Http\Controllers\Controller;
use App\Ordemservico;
use App\OrdemservicoAcesso;
use Illuminate\Http\Request;

class OrdemservicoAcessoController extends Controller
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
     * @param  \App\OrdemservicoAcesso  $ordemservicoAcesso
     * @return \Illuminate\Http\Response
     */
    public function show(OrdemservicoAcesso $ordemservicoAcesso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrdemservicoAcesso  $ordemservicoAcesso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrdemservicoAcesso $ordemservicoAcesso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OrdemservicoAcesso  $ordemservicoAcesso
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrdemservicoAcesso $ordemservicoAcesso)
    {
        //
    }

    /*-------------------- ROTAS CUSTON --------------------*/

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Ordemservico  $ordemservico
     * @return \Illuminate\Http\Response
     */
    public function listaDeAcessosPorOrdemservico(Ordemservico $ordemservico)
    {
        return $ordemservico->acessos;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\OrdemservicoAcesso  $ordemservicoAcesso
     * @return \Illuminate\Http\Response
     */
    public function checkOrdemservicoAcesso(OrdemservicoAcesso $ordemservicoAcesso)
    {
        $ordemservicoAcesso->update(
            [
                'check' => true
            ]
        );
    }
}
