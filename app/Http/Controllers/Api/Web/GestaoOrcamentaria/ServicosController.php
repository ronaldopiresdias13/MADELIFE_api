<?php

namespace App\Http\Controllers\Api\Web\GestaoOrcamentaria;

use App\Models\Empresa;
use App\Http\Controllers\Controller;
use App\Models\Servico;
use App\Models\ServicoFormacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        return Servico::where('empresa_id', $empresa_id)
            ->where('ativo', true)
            ->orderBy('descricao')
            ->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listServicosFormacoes(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa->id;

        $servicos = Servico::with('formacoes')
            ->where('empresa_id', $empresa_id)
            ->where('ativo', true)
            ->orderBy('descricao')
            ->get();

        return $servicos;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        DB::transaction(function () use ($request, $empresa_id) {
            $servico = Servico::updateOrCreate(
                [
                    'codigo'     => $request->codigo,
                    'empresa_id' => $empresa_id,
                    'descricao' => $request->descricao,
                ],
                [
                    'valor'     => $request->valor,
                    'ativo'     => true
                ]
            );

            if ($request->formacoes) {
                foreach ($request->formacoes as $key => $formacao) {
                    ServicoFormacao::updateOrCreate(
                        [
                            'servico_id'  => $servico->id,
                            'formacao_id' => $formacao['id']
                        ],
                        [
                            'ativo' => true
                        ]
                    );
                }
            }

            // $servico = new Servico();
            // $servico->descricao  = $request->descricao;
            // $servico->codigo     = $request->codigo;
            // $servico->valor      = $request->valor;
            // $servico->empresa_id = $request->empresa_id;
            // $servico->save();
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
        $servico->formacoes;
        return $servico;
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

            foreach ($servico->servicoFormacao as $key => $servicoFormacao) {
                $servicoFormacao->ativo = false;
                $servicoFormacao->save();
            }

            foreach ($request['formacoes'] as $key => $formacao) {
                ServicoFormacao::updateOrCreate(
                    [
                        'servico_id' => $servico->id,
                        'formacao_id' => $formacao['id']
                    ],
                    [
                        'ativo' => true
                    ]
                );
            }
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
