<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Prestador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestadoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listNomePrestadores(Request $request)
    {
        // $user = $request->user();

        // $prestadores = Prestador::with([
        //     'pessoa' => function ($query) {
        //         $query->select('id', 'nome');
        //     }
        // ])->get(['id', 'pessoa_id']);


        // Alterar para trazer somente os prestadores da minha empresa atravez do ususario autenticado

        $prestadores = DB::table('prestadores')
            ->join('pessoas', 'pessoas.id', '=', 'prestadores.pessoa_id')
            ->select('prestadores.id as value', 'pessoas.nome as label')
            ->orderBy('pessoas.nome')
            ->get();

        return $prestadores;
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
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function show(Prestador $prestador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prestador $prestador)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prestador $prestador)
    {
        //
    }
}
