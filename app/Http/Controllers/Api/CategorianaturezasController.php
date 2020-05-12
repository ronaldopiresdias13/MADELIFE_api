<?php

namespace App\Http\Controllers\Api;

use App\Categorianatureza;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategorianaturezasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Categorianatureza::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $categorianatureza = new Categorianatureza;
        $categorianatureza->empresa_id = $request->empresa_id;
        $categorianatureza->descriciao = $request->descricao;
        $categorianatureza->status = $request->status;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Categorianatureza  $categorianatureza
     * @return \Illuminate\Http\Response
     */
    public function show(Categorianatureza $categorianatureza)
    {
        return $categorianatureza;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Categorianatureza  $categorianatureza
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categorianatureza $categorianatureza)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Categorianatureza  $categorianatureza
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categorianatureza $categorianatureza)
    {
        //
    }
}
