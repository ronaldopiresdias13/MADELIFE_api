<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Controller;
use App\Models\Ordemservico;
use App\Models\Transcricao;
use Illuminate\Http\Request;

class TranscricoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listTranscricoesByEscalaId(Ordemservico $ordemservico)
    {
        return Transcricao::with(['itensTranscricao.produto', 'itensTranscricao.horariomedicamentos', 'itensTranscricao.acoesmedicamentos'])
            ->where('ordemservico_id', $ordemservico['id'])
            ->where('ativo', true)
            ->get();
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
     * @param  \App\Transcricao  $transcricao
     * @return \Illuminate\Http\Response
     */
    public function show(Transcricao $transcricao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transcricao  $transcricao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transcricao $transcricao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transcricao  $transcricao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transcricao $transcricao)
    {
        //
    }
}
