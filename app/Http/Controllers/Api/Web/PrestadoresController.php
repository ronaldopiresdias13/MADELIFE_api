<?php

namespace App\Http\Controllers\Api\Web;

use App\Models\Escala;
use App\Http\Controllers\Controller;
use App\Models\Prestador;
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
            ->where('prestadores.ativo', true)
            ->orderBy('pessoas.nome')
            ->get();
        // $prestadores = Prestador::where('ativo', true)
        //     ->join('pessoas', 'pessoas.id', '=', 'prestadores.pessoa_id')
        //     ->select('prestadores.id', 'pessoas.nome')
        //     ->orderBy('pessoas.nome');
        // // ->get();

        return $prestadores;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listPrestadoresComFormacoes(Request $request)
    {
        // $user = $request->user();

        // $prestadores = Prestador::with([
        //     'pessoa' => function ($query) {
        //         $query->select('id', 'nome');
        //     }
        // ])->get(['id', 'pessoa_id']);


        // Alterar para trazer somente os prestadores da minha empresa atravez do ususario autenticado

        // $prestadores = Prestador::with('pessoa', 'formacoes')
        //     ->where('ativo', true)
        //     ->get();
        $prestadores = Prestador::where('ativo', true)
            ->join('pessoas', 'pessoas.id', '=', 'prestadores.pessoa_id')
            ->select('prestadores.id', 'pessoas.nome')
            ->orderBy('pessoas.nome');
        // ->get();

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
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function buscaprestadoresporfiltro(Request $request)
    {
        return Prestador::with(['formacoes', 'pessoa.conselhos', 'pessoa.enderecos' => function ($query) {
            $query->with('cidade');
        }])
            // ->join('formacoes', 'pessoas.id', '=', 'prestadores.pessoa_id')
            ->join('pessoas', 'pessoas.id', '=', 'prestadores.pessoa_id')
            ->join('conselhos', 'pessoas.id', '=', 'conselhos.pessoa_id')
            ->where('pessoas.nome', 'like', $request->nome ? '%' . $request->nome . '%' : '')
            ->where('prestadores.ativo', 1)
            ->orWhere('pessoas.cpfcnpj', 'like', $request->cpf ? $request->cpf : '')
            ->orWhere('conselhos.numero', 'like', $request->conselho ? $request->conselho : '')
            ->select('prestadores.*')
            ->get();
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function historicopacientesprestador(Prestador $prestador)
    {
        // $user = $request->user();
        // $prestador = $request->pessoa->prestador;
        $escalas = Escala::where('prestador_id', $prestador->id)
            ->join('ordemservicos', 'ordemservicos.id', '=', 'escalas.ordemservico_id')
            ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
            ->join('homecares', 'homecares.orcamento_id', '=', 'orcamentos.id')
            ->join('pacientes', 'homecares.paciente_id', '=', 'pacientes.id')
            ->join('pessoas', 'pacientes.pessoa_id', '=', 'pessoas.id')
            ->select('pessoas.nome')
            ->where('homecares.ativo', true)
            ->groupBy('pessoas.nome')
            ->orderBy('pessoas.nome')
            ->get();
        return $escalas;
    }
}
