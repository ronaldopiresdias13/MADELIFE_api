<?php

namespace App\Http\Controllers\Api\Web\GestaoOrcamentaria;

use App\Empresa;
use App\Http\Controllers\Controller;
use App\Orcamento;
use Illuminate\Http\Request;

class OrcamentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Empresa $empresa)
    {
        return Orcamento::with([
            'cliente.pessoa:id,nome',
            'homecare.paciente.pessoa:id,nome',
            'ordemservico:id,status',
            'servicos',
            'orcamentocustos',
            'produtos',
            'cidade'
            // 'produtos' => function ($query) {
            //     $query->select('id');
            // }
        ])
            ->where('empresa_id', $empresa->id)
            ->where('ativo', true)
            ->orderBy('id', 'desc')
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
     * @param  \App\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function show(Orcamento $orcamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orcamento $orcamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orcamento $orcamento)
    {
        //
    }
}
