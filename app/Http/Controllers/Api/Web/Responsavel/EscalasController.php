<?php

namespace App\Http\Controllers\Api\Web\Responsavel;

use App\Escala;
use App\Http\Controllers\Controller;
use App\Paciente;
use Illuminate\Http\Request;

class EscalasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listEscalasByIdResponsavel(Request $request)
    {
        // $user = $request->user();
        // $responsavel = $user->pessoa->responsavel;

        // $pacientes = Paciente::with(['homecares.orcamento.ordemservico.escalas' => function ($query) {
        //     $query->with('prestador.pessoa')->where('assinaturaresponsavel', '');
        // }])
        //     ->where('responsavel_id', $responsavel->id)
        //     ->get();

        // return Ordemservico::where('ordemservicos.empresa_id', $empresa['id'])->where('ordemservicos.ativo', 1)->where('ordemservicos.status', 1)
        //     ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
        //     ->join('orcamento_servico', 'orcamento_servico.orcamento_id', '=', 'orcamentos.id')
        //     ->join('servicos', 'servicos.id', '=', 'orcamento_servico.servico_id')
        //     ->select('servicos.descricao', DB::raw('count(servicos.id) as count'))
        //     ->groupBy('servicos.descricao')
        //     ->orderBy('count', 'desc')
        //     ->get();

        // $pacientes = Escala::where('responsavel_id', '%')
        $pacientes = Escala::where('escalas.id', 'like', '%')
            ->join('ordemservicos', 'ordemservicos.id', '=', 'escalas.ordemservico_id')
            ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
            ->join('homecares', 'homecares.orcamento_id', '=', 'orcamentos.id')
            ->join('pacientes', 'pacientes.id', '=', 'homecares.paciente_id')
            // ->join('ordenservico', '', '=', '')
            // ->select('escalas.*')
            ->limit(10)
            ->get();

        return $pacientes;
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
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function show(Escala $escala)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Escala $escala)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function destroy(Escala $escala)
    {
        //
    }
}
