<?php

namespace App\Http\Controllers\Api;

use App\Diagnosticosecundario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiagnosticossecundariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Diagnosticosecundario::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $diagnosticosecundario = new Diagnosticosecundario;
        $diagnosticosecundario->codigo     = $request->codigo;
        $diagnosticosecundario->descricao  = $request->descricao;
        $diagnosticosecundario->observacao = $request->observacao;
        $diagnosticosecundario->referencia = $request->referencia;
        $diagnosticosecundario->pil        = $request->pil;
        $diagnosticosecundario->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Diagnosticosecundario  $diagnosticosecundario
     * @return \Illuminate\Http\Response
     */
    public function show(Diagnosticosecundario $diagnosticosecundario)
    {
        return $diagnosticosecundario;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Diagnosticosecundario  $diagnosticosecundario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Diagnosticosecundario $diagnosticosecundario)
    {
        $diagnosticosecundario->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Diagnosticosecundario  $diagnosticosecundario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Diagnosticosecundario $diagnosticosecundario)
    {
        $diagnosticosecundario->delete();
    }
}
