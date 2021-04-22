<?php

namespace App\Http\Controllers\Api;

use App\Models\Assinatura;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssinaturasController extends Controller
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
     * @param  \App\Assinatura  $assinatura
     * @return \Illuminate\Http\Response
     */
    public function show(Assinatura $assinatura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Assinatura  $assinatura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Assinatura $assinatura)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Assinatura  $assinatura
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assinatura $assinatura)
    {
        $assinatura->ativo = false;
        $assinatura->save();
    }
}
