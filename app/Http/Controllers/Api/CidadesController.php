<?php

namespace App\Http\Controllers\Api;

use App\Models\Cidade;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CidadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Cidade::all();
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
            $cidade = new Cidade();
            $cidade->nome = $request->nome;
            $cidade->uf   = $request->uf;
            $cidade->save();
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cidade  $cidade
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Cidade $cidade)
    {
        return $cidade;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cidade  $cidade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cidade $cidade)
    {
        DB::transaction(function () use ($request, $cidade) {
            $cidade->update($request->all());
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cidade  $cidade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cidade $cidade)
    {
        $cidade->ativo = false;
        $cidade->save();
    }
    public function pegarCidadesPorUf(string $uf)
    {
        return Cidade::where('uf', $uf)->get();
    }
}
