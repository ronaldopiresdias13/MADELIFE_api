<?php

namespace App\Http\Controllers\Web\EmpresaDados;

use App\Http\Controllers\Controller;
use App\Models\EmpresaDados;
use Illuminate\Http\Request;

class EmpresaDadosController extends Controller
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
     * @param  \App\Models\EmpresaDados  $empresaDados
     * @return \Illuminate\Http\Response
     */
    public function show(EmpresaDados $empresaDados)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmpresaDados  $empresaDados
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmpresaDados $empresaDados)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmpresaDados  $empresaDados
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmpresaDados $empresaDados)
    {
       $empresaDados->delete();
    }
}
