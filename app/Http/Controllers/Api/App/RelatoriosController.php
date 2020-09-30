<?php

namespace App\Http\Controllers\Api\App;

use App\Escala;
use App\Http\Controllers\Controller;
use App\Relatorio;
use Illuminate\Http\Request;

class RelatoriosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listRelatoriosByEscalaId(Escala $escala)
    {
        return Relatorio::where('escala_id', $escala['id'])
            ->where('ativo', true)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Relatorio::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Relatorio  $relatorio
     * @return \Illuminate\Http\Response
     */
    public function show(Relatorio $relatorio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Relatorio  $relatorio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Relatorio $relatorio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Relatorio  $relatorio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Relatorio $relatorio)
    {
        $relatorio->ativo = false;
        $relatorio->save();
    }
}
