<?php

namespace App\Http\Controllers\Api;

use App\Acaomedicamento;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AcaomedicamentosController extends Controller
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
        // dd($request);
        Acaomedicamento::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Acaomedicamento  $acaomedicamento
     * @return \Illuminate\Http\Response
     */
    public function show(Acaomedicamento $acaomedicamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Acaomedicamento  $acaomedicamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Acaomedicamento $acaomedicamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Acaomedicamento  $acaomedicamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Acaomedicamento $acaomedicamento)
    {
        //
    }
}
