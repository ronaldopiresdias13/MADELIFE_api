<?php

namespace App\Http\Controllers\Web\ProfissionalFormacao;

use App\Http\Controllers\Controller;
use App\Models\ProfissionalFormacao;
use Illuminate\Http\Request;

class ProfissionalFormacaoController extends Controller
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
     * @param  \App\Models\ProfissionalFormacao  $profissionalFormacao
     * @return \Illuminate\Http\Response
     */
    public function show(ProfissionalFormacao $profissionalFormacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProfissionalFormacao  $profissionalFormacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProfissionalFormacao $profissionalFormacao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProfissionalFormacao  $profissionalFormacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProfissionalFormacao $profissionalFormacao)
    {
        $profissionalFormacao->ativo = false;
        $profissionalFormacao->save();
    }
}
