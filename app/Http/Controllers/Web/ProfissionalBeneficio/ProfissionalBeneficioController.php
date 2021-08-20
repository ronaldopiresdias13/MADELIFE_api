<?php

namespace App\Http\Controllers\Web\ProfissionalBeneficio;

use App\Http\Controllers\Controller;
use App\Models\ProfissionalBeneficio;
use Illuminate\Http\Request;

class ProfissionalBeneficioController extends Controller
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
     * @param  \App\Models\ProfissionalBeneficio  $profissionalBeneficio
     * @return \Illuminate\Http\Response
     */
    public function show(ProfissionalBeneficio $profissionalBeneficio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProfissionalBeneficio  $profissionalBeneficio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProfissionalBeneficio $profissionalBeneficio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProfissionalBeneficio  $profissionalBeneficio
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProfissionalBeneficio $profissionalBeneficio)
    {
        $profissionalBeneficio->ativo = false;
        $profissionalBeneficio->save();
    }
}
