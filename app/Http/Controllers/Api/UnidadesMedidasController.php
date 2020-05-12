<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Unidademedida;
use Illuminate\Http\Request;

class UnidadesMedidasController extends Controller
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
     * @param  \App\Unidademedida  $unidademedida
     * @return \Illuminate\Http\Response
     */
    public function show(Unidademedida $unidademedida)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Unidademedida  $unidademedida
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unidademedida $unidademedida)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Unidademedida  $unidademedida
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unidademedida $unidademedida)
    {
        //
    }
    public function migracao(Request $request)
    {
        // dd($request);
        $unidade = new Unidademedida;
        $unidade->descricao = $request->descricao;
        $unidade->sigla = $request->sigla;
        $unidade->grupo = $request->grupo;
        $unidade->padrao = true;
        $unidade->status = true;
        $unidade->empresa_id = 1;
        $unidade->save();
    }
}
