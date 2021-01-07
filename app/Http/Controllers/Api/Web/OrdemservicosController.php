<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Ordemservico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdemservicosController extends Controller
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
     * @param  \App\Ordemservico  $ordemservico
     * @return \Illuminate\Http\Response
     */
    public function show(Ordemservico $ordemservico)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ordemservico  $ordemservico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ordemservico $ordemservico)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ordemservico  $ordemservico
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ordemservico $ordemservico)
    {
        //
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboardGroupByMotivoDesativados(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        return Ordemservico::select(DB::raw('motivo, count(motivo) AS total'))
            ->where('status', 0)
            ->where('empresa_id', $empresa_id)
            ->groupBy('motivo')
            ->orderByDesc('total')
            ->get();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboardGroupByStatusAtivadosDesativados(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        return Ordemservico::select(DB::raw("case status when 1 then 'Ativados' when 0 then 'Desativados' end as situacao, count(status) AS total"))
            ->where('empresa_id', $empresa_id)
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listaOrdemServicosEscalas(Request $request)
    {
        $user = $request->user();
        $profissional = $user->pessoa->profissional;

        $escalas = Ordemservico::with([
            'servicos',
            'acessos',
            'profissional.pessoa',
            'orcamento.cidade', 'orcamento' => function ($query) {
                $query->with(['homecare' => function ($query) {
                    $query->with(['paciente.pessoa', 'paciente.responsavel.pessoa']);
                }]);
                $query->with(['cliente' => function ($query) {
                    $query->select('id', 'pessoa_id');
                    $query->with(['pessoa' => function ($query) {
                        $query->select('id', 'nome');
                    }]);
                }]);
            }
        ])
            ->withCount('prestadores')
            ->withCount('escalas')
            ->where('empresa_id', $profissional->empresa_id)
            ->where('ativo', true)
            ->get(['id', 'orcamento_id']);

        return $escalas;
    }
}
