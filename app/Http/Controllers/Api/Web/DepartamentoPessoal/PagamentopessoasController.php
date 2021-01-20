<?php

namespace App\Http\Controllers\Api\Web\DepartamentoPessoal;

use App\Http\Controllers\Controller;
use App\Pagamentopessoa;
use Illuminate\Http\Request;

class PagamentopessoasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buscarPagamentosPessoaPorPeriodoEmpresaId(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;

        return Pagamentopessoa::with(['pessoa.prestador.formacoes', 'ordemservico.orcamento.homecare.paciente.pessoa', 'ordemservico.orcamento.cliente.pessoa'])
            ->where('empresa_id', $empresa_id)
            ->whereBetween('periodo1', [$request->data_ini, $request->data_fim])
            ->get();
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
     * @param  \App\Pagamentopessoa  $pagamentopessoa
     * @return \Illuminate\Http\Response
     */
    public function show(Pagamentopessoa $pagamentopessoa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pagamentopessoa  $pagamentopessoa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pagamentopessoa $pagamentopessoa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pagamentopessoa  $pagamentopessoa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pagamentopessoa $pagamentopessoa)
    {
        //
    }
}
