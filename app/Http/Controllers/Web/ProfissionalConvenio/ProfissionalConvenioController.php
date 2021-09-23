<?php

namespace App\Http\Controllers\Web\ProfissionalConvenio;

use App\Http\Controllers\Controller;
use App\Models\ProfissionalConvenio;
use Illuminate\Http\Request;

class ProfissionalConvenioController extends Controller
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
     * @param  \App\Models\ProfissionalConvenio  $profissionalConvenio
     * @return \Illuminate\Http\Response
     */
    public function show(ProfissionalConvenio $profissionalConvenio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProfissionalConvenio  $profissionalConvenio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProfissionalConvenio $profissionalConvenio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProfissionalConvenio  $profissionalConvenio
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProfissionalConvenio $profissionalConvenio)
    {
        $profissionalConvenio->ativo = false;
        $profissionalConvenio->save();
    }
}
