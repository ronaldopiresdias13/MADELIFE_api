<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Transcricao;
use Illuminate\Http\Request;

class TranscricoesController extends Controller
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
        $transcricao = new Transcricao;
        $transcricao->medico = $request->medico;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transcricao  $transcricao
     * @return \Illuminate\Http\Response
     */
    public function show(Transcricao $transcricao)
    {
        return $transcricao;
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
        $transcricao->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transcricao  $transcricao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transcricao $transcricao)
    {
        $transcricao->delete();
    }
}
