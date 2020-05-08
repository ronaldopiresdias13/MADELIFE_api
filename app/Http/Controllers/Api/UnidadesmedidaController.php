<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Unidademedida;
use Illuminate\Http\Request;

class UnidadesmedidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Unidademedida::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $unidademedida = new Unidademedida;
        $unidademedida->descricao = $request->descricao;
        $unidademedida->sigla = $request->sigla;
        $unidademedida->grupo = $request->grupo;
        $unidademedida->padrao = $request->padrao;
        $unidademedida->status = $request->status;
        $unidademedida->sigla = $request->sigla;
        $unidademedida->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Unidademedida  $unidademedida
     * @return \Illuminate\Http\Response
     */
    public function show(Unidademedida $unidademedida)
    {
        return $unidademedida;
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
        $unidademedida->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Unidademedida  $unidademedida
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unidademedida $unidademedida)
    {
        $unidademedida->delete();
    }
}
