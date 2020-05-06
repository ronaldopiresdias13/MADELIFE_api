<?php

namespace App\Http\Controllers\Api;

use App\Beneficio;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BeneficiosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Beneficio::all()->sortBy('descricao');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $beneficio = new Beneficio;
        $beneficio->descricao = $request->descricao;
        $beneficio->empresa = $request->empresa;
        $beneficio->valor = $request->valor;
        $beneficio->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Beneficio  $beneficio
     * @return \Illuminate\Http\Response
     */
    public function show(Beneficio $beneficio)
    {
        return $beneficio;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Beneficio  $beneficio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Beneficio $beneficio)
    {
        $beneficio->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Beneficio  $beneficio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Beneficio $beneficio)
    {
        $beneficio->delete();
    }
}
