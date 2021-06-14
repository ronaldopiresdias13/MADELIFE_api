<?php

namespace App\Http\Controllers\Api\App\v3_0_20;

use App\Models\Conselho;
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

        return response()->json([
            'toast' => [
                'text' => 'Salvo com sucesso!',
                'color' => 'success',
                'duration' => 2000
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
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

        return response()->json([
            'toast' => [
                'text' => 'Atualizado com sucesso!',
                'color' => 'success',
                'duration' => 2000
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Conselho  $conselho
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conselho $conselho)
    {
        DB::transaction(function () use ($conselho) {
            $conselho->ativo = false;
            $conselho->save();
        });

        return response()->json([
            'toast' => [
                'text' => 'Excluido com sucesso!',
                'color' => 'success',
                'duration' => 2000
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }
}
