<?php

namespace App\Http\Controllers\Api\App;

use App\Conselho;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConselhosController extends Controller
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
        DB::transaction(function () use ($request) {
            $conselho = new Conselho();
            $conselho->instituicao = $request->instituicao;
            $conselho->uf          = $request->uf;
            $conselho->numero      = $request->numero;
            $conselho->pessoa_id   = $request->pessoa_id;
            $conselho->save();
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Conselho  $conselho
     * @return \Illuminate\Http\Response
     */
    public function show(Conselho $conselho)
    {
        //
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
        DB::transaction(function () use ($request, $conselho) {
            $conselho->update($request->all());
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Conselho  $conselho
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conselho $conselho)
    {
        $conselho->ativo = false;
        $conselho->save();
    }
}
