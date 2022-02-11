<?php

namespace App\Http\Controllers\Api\App\v3_0_20;

use App\Models\Escala;
use App\Http\Controllers\Controller;
use App\Models\Prestador;
use Illuminate\Http\Request;

class PrestadoresController extends Controller
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
    /**
     * Display the specified resource.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function meuspacientes(Request $request)
    {
        $user = $request->user();
        $prestador = $user->pessoa->prestador;
        $escalas = Escala::where('prestador_id', $prestador->id)
            ->join('ordemservicos', 'ordemservicos.id', '=', 'escalas.ordemservico_id')
            ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
            ->join('homecares', 'homecares.orcamento_id', '=', 'orcamentos.id')
            ->join('pacientes', 'homecares.paciente_id', '=', 'pacientes.id')
            ->join('pessoas', 'pacientes.pessoa_id', '=', 'pessoas.id')
            ->select('pessoas.nome')
            // ->where('homecares.ativo', true)
            ->groupBy('pessoas.nome')
            ->orderBy('pessoas.nome')
            ->get();
        return $escalas;
    }
}
