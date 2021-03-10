<?php

namespace App\Http\Controllers\Web\Ordemservicos;

use App\Http\Controllers\Controller;
use App\Models\Ordemservico;
use Illuminate\Http\Request;

class OrdemservicosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = Ordemservico::with(
            ["orcamento.homecare.paciente.pessoa"],
            ["orcamento.cliente.pessoa"],
            ["orcamento.evento"],
            ["orcamento.cidade"],
            ["orcamento.servicos"],
            ["profissional.pessoa"]
        )
        ->where('empresa_id', 1)
        ->orderByDesc('id')
        ->paginate($request['per_page'] ? $request['per_page'] : 15);

        if (env("APP_ENV", 'production') == 'production') {
            return $result->withPath(str_replace('http:', 'https:', $result->path()));
        } else {
            return $result;
        }
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
     * @param  \App\Models\Ordemservico  $ordemservico
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
     * @param  \App\Models\Ordemservico  $ordemservico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ordemservico $ordemservico)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ordemservico  $ordemservico
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ordemservico $ordemservico)
    {
        //
    }
}
