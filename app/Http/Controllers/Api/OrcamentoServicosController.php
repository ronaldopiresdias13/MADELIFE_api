<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\OrcamentoServico;
use Illuminate\Http\Request;

class OrcamentoServicosController extends Controller
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
     * @param  \App\OrcamentoServico  $orcamentoServico
     * @return \Illuminate\Http\Response
     */
    public function show(OrcamentoServico $orcamentoServico)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrcamentoServico  $orcamentoServico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrcamentoServico $orcamentoServico)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OrcamentoServico  $orcamentoServico
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrcamentoServico $orcamentoServico)
    {
        $orcamentoServico->delete();
    }
}
