<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Telefone;
use Illuminate\Http\Request;

class TelefonesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Telefone::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $telefone = new Telefone;
        $telefone->telefone = $request->telefone;
        $telefone->tipo = $request->tipo;
        $telefone->descricao = $request->descricao;
        $telefone->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Telefone  $telefone
     * @return \Illuminate\Http\Response
     */
    public function show(Telefone $telefone)
    {
        return $telefone;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Telefone  $telefone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Telefone $telefone)
    {
        $telefone->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Telefone  $telefone
     * @return \Illuminate\Http\Response
     */
    public function destroy(Telefone $telefone)
    {
        $telefone->delete();
    }
}
