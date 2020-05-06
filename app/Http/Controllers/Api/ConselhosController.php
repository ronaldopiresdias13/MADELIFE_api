<?php

namespace App\Http\Controllers\Api;

use App\Conselho;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConselhosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Conselho::all()->sortBy('uf');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $conselho = new Conselho;
        $conselho->instituicao = $request->instituicao;
        $conselho->uf = $request->uf;
        $conselho->numero = $request->numero;
        $conselho->pessoa = $request->pessoa;
        $conselho->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Conselho  $conselho
     * @return \Illuminate\Http\Response
     */
    public function show(Conselho $conselho)
    {
        return $conselho;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Conselho  $conselho
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Conselho $conselho)
    {
        $conselho->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Conselho  $conselho
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conselho $conselho)
    {
        $conselho->delete();
    }
}
