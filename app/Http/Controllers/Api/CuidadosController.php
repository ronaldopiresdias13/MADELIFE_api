<?php

namespace App\Http\Controllers\Api;

use App\Cuidado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CuidadosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cuidado::all()->ordeBy('descricao');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cuidado = new Cuidado;
        $cuidado->descricao = $request->descricao;
        $cuidado->codigo = $request->codigo;
        $cuidado->empresa_id = $request->empresa_id;
        $cuidado->status = $request->staus; 
        $cuidado->save(); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cuidado  $cuidado
     * @return \Illuminate\Http\Response
     */
    public function show(Cuidado $cuidado)
    {
        return $cuidado;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cuidado  $cuidado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cuidado $cuidado)
    {
        $cuidado->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cuidado  $cuidado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cuidado $cuidado)
    {
        $cuidado->delete();
    }
}
