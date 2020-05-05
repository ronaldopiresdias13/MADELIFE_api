<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\acesso;
use Illuminate\Http\Request;

class AcessosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Acesso::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $acesso = Acesso::updateOrCreate(
        //     ['nome' => $request->nome]
        // );

        $acesso = new Acesso;
        $acesso->nome = $request->nome;
        $acesso->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\acesso  $acesso
     * @return \Illuminate\Http\Response
     */
    public function show(acesso $acesso)
    {
        return $acesso;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\acesso  $acesso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, acesso $acesso)
    {
        $acesso->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\acesso  $acesso
     * @return \Illuminate\Http\Response
     */
    public function destroy(acesso $acesso)
    {
        $acesso->delete();
    }
}
