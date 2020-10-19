<?php

namespace App\Http\Controllers\Api\Web\GestaoOrcamentaria;

use App\Empresa;
use App\Http\Controllers\Controller;
use App\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Empresa $empresa)
    {
        return Servico::select(['id', 'codigo', 'descricao', 'valor', 'empresa_id'])
            ->where('empresa_id', $empresa->id)
            ->where('ativo', true)
            ->orderBy('descricao')
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
        DB::transaction(function () use ($request) {
            $servico = new Servico();
            $servico->descricao  = $request->descricao;
            $servico->codigo     = $request->codigo;
            $servico->valor      = $request->valor;
            $servico->empresa_id = $request->empresa_id;
            $servico->save();
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Servico  $servico
     * @return \Illuminate\Http\Response
     */
    public function show(Servico $servico)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Servico  $servico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Servico $servico)
    {
        DB::transaction(function () use ($request, $servico) {
            $servico->descricao  = $request->descricao;
            $servico->codigo     = $request->codigo;
            $servico->valor      = $request->valor;
            $servico->empresa_id = $request->empresa_id;
            $servico->save();
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Servico  $servico
     * @return \Illuminate\Http\Response
     */
    public function destroy(Servico $servico)
    {
        $servico->ativo = false;
        $servico->save();
    }
}
