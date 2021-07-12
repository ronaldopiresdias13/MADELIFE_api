<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medicao;
use App\Models\ProdutoMedicao;
use App\Models\ServicoMedicao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return $request;
        $user = $request->user()->pessoa;
        $empresa_id = $user->profissional ? $user->profissional->empresa_id : $user->cliente->empresa_id;
        return Medicao::with([
            'medicao_servicos.servico',
            'medicao_produtos.produto',
            'cliente.pessoa', 'ordemservico.orcamento.homecare.paciente.pessoa'
        ])
            ->where('empresa_id', $empresa_id)
            ->where(DB::raw("DATE_FORMAT(data1, '%Y-%m')"), $request->periodo)
            ->where('cliente_id', 'like', $request->cliente_id ? $request->cliente_id : '%')
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
        $medicao = Medicao::create([
            'empresa_id' => $request['empresa_id'],
            'cliente_id' => $request['cliente_id'],
            'profissional_id' => $request['profissional_id'],
            'ordemservico_id' => $request['ordemservico_id'],
            'data1' => $request['data1'],
            'data2' => $request['data2'],
            'dataSolicitacao' => $request['dataSolicitacao'],
            'numeroGuiaPrestador' => $request['numeroGuiaPrestador'],
            'valor' => $request['valor'],
            'adicional' => $request['adicional'],
            'desconto' => $request['desconto'],
            'situacao' => $request['situacao'],
            'observacao' => $request['observacao'],
            'status' => $request['status']
        ])->id;
        foreach ($request['servicos'] as $key => $servico) {
            $servico_medicao = ServicoMedicao::create([
                'medicoes_id' => $medicao,
                'servico_id' => $servico['servico_id'],
                'quantidade' => $servico['quantidade'],
                'atendido' => $servico['atendido'],
                'dataExecucao' => $servico['dataExecucao'],
                'horaInicial' => $servico['horaInicial'],
                'horaFinal' => $servico['horaFinal'],
                'reducaoAcrescimo' => $servico['reducaoAcrescimo'],
                'valor' => $servico['valor'],
                'subtotal' => $servico['subtotal'],
                'situacao' => $servico['situacao'],
                'observacao' => $servico['observacao'],
                'status' => $servico['status'],
            ]);
        }
        foreach ($request['produtos'] as $key => $produto) {
            $produto_medicao = ProdutoMedicao::create([
                'medicoes_id' => $medicao,
                'produto_id' => $produto['produto_id'],
                'quantidade' => $produto['quantidade'],
                'atendido' => $produto['atendido'],
                'dataExecucao' => $produto['dataExecucao'],
                'horaInicial' => $produto['horaInicial'],
                'horaFinal' => $produto['horaFinal'],
                'reducaoAcrescimo' => $produto['reducaoAcrescimo'],
                'valor' => $produto['valor'],
                'subtotal' => $produto['subtotal'],
                'situacao' => $produto['situacao'],
                'observacao' => $produto['observacao'],
                'status' => $produto['status'],
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Medicao  $medicao
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Medicao $medicao)
    {
        $iten = $medicao;

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['adicionais']) {
            foreach ($request['adicionais'] as $key => $adicional) {
                if (is_string($adicional)) {
                    $iten[$adicional];
                } else {
                    $iten2 = $iten;
                    foreach ($adicional as $key => $a) {
                        if ($key == 0) {
                            if ($iten[0] == null) {
                                $iten2 = $iten[$a];
                            } else {
                                foreach ($iten as $key => $i) {
                                    $i[$a];
                                }
                            }
                        } else {
                            if ($iten2 != null) {
                                if ($iten2->count() > 0) {
                                    if ($iten2[0] == null) {
                                        $iten2 = $iten2[$a];
                                    } else {
                                        foreach ($iten2 as $key => $i) {
                                            $i[$a];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $iten;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Medicao  $medicao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Medicao $medicao)
    {
        DB::transaction(function () use ($request, $medicao) {
            $medicao->update(
                [
                    'empresa_id'      => $request['empresa_id'],
                    'cliente_id'      => $request['cliente_id'],
                    'profissional_id'    => $request['profissional_id'],
                    'ordemservico_id' => $request['ordemservico_id'],
                    'dataSolicitacao' => $request['dataSolicitacao'],
                    'numeroGuiaPrestador' => $request['numeroGuiaPrestador'],
                    'data1'           => $request['data1'],
                    'data2'           => $request['data2'],
                    'valor'           => $request['valor'],
                    'adicional'       => $request['adicional'],
                    'desconto'        => $request['desconto'],
                    'situacao'        => $request['situacao'],
                    'observacao'      => $request['observacao'],
                    'status'          => $request['status'],
                ]
            );
            if ($request['medicao_servicos']) {
                foreach ($request['medicao_servicos'] as $key => $servico) {
                    $medicao_sevico = ServicoMedicao::updateOrCreate(
                        [
                            'id' => $servico['id'],
                        ],
                        [
                            'medicoes_id' => $servico['medicoes_id'],
                            'servico_id'  => $servico['servico_id'],
                            'quantidade'  => $servico['quantidade'],
                            'atendido'    => $servico['atendido'],
                            'valor'       => $servico['valor'],
                            'subtotal'    => $servico['subtotal'],
                            'situacao'    => $servico['situacao'],
                            'observacao'  => $servico['observacao'],
                            'status'      => $servico['status'],
                        ]
                    );
                }
            }
            if ($request['medicao_produtos']) {
                foreach ($request['medicao_produtos'] as $key => $produto) {
                    $medicao_produto = ProdutoMedicao::updateOrCreate(
                        [
                            'id' => $produto['id'],
                        ],
                        [
                            'medicoes_id' => $produto['medicoes_id'],
                            'produto_id'  => $produto['produto_id'],
                            'quantidade'  => $produto['quantidade'],
                            'atendido'    => $produto['atendido'],
                            'valor'       => $produto['valor'],
                            'subtotal'    => $produto['subtotal'],
                            'situacao'    => $produto['situacao'],
                            'observacao'  => $produto['observacao'],
                            'status'      => $produto['status'],
                        ]
                    );
                }
            }
        });

        return response()->json('Medição atualizada com sucesso!', 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Medicao  $medicao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Medicao $medicao)
    {
        $medicao->ativo = false;
        $medicao->save();
    }
}
