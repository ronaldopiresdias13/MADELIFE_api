<?php

namespace App\Http\Controllers\Api\App\v3_0_22;

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
        $user = $request->user();
        $prestador = $user->pessoa->prestador;
        $escalas = Escala::where('prestador_id', $prestador->id)
            ->join('ordemservicos as os', 'os.id', '=', 'escalas.ordemservico_id')
            ->join('orcamentos as o', 'o.id', '=', 'os.orcamento_id')
            ->join('homecares as hc', 'hc.orcamento_id', '=', 'o.id')
            ->join('pacientes as pac', 'hc.paciente_id', '=', 'pac.id')
            ->join('pessoas as p', 'pac.pessoa_id', '=', 'p.id')
            ->leftJoin('responsaveis as  r', 'pac.responsavel_id', '=', 'r.id')
            ->leftJoin('pessoas as ps', 'r.pessoa_id', '=', 'ps.id')
            ->select('os.id', 'p.nome', 'p.observacoes', 'ps.nome as responsavel')
            // ->where('homecares.ativo', true)
            ->groupBy('os.id', 'p.nome', 'p.observacoes', 'ps.nome')
            ->orderBy('p.nome')
            ->get();
        return $escalas;
    }
}
