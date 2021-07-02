<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medicao;
use App\Models\ProdutoMedicao;
use App\Models\ServicoMedicao;
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
        $with = [];

        if ($request['adicionais']) {
            foreach ($request['adicionais'] as $key => $adicional) {
                if (is_string($adicional)) {
                    array_push($with, $adicional);
                } else {
                    $filho = '';
                    foreach ($adicional as $key => $a) {
                        if ($key == 0) {
                            $filho = $a;
                        } else {
                            $filho = $filho . '.' . $a;
                        }
                    }
                    array_push($with, $filho);
                }
            }
            $itens = Medicao::with($with)->where('ativo', true);
        } else {
            $itens = Medicao::where('ativo', true);
        }

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                $itens->where(
                    ($where['coluna']) ? $where['coluna'] : 'id',
                    ($where['expressao']) ? $where['expressao'] : 'like',
                    ($where['valor']) ? $where['valor'] : '%'
                );
            }
        }

        if ($request['order']) {
            foreach ($request['order'] as $key => $order) {
                $itens->orderBy(
                    ($order['coluna']) ? $order['coluna'] : 'id',
                    ($order['tipo']) ? $order['tipo'] : 'asc'
                );
            }
        }

        $itens = $itens->get();

        if ($request['adicionais']) {
            foreach ($itens as $key => $iten) {
                foreach ($request['adicionais'] as $key => $adicional) { // Percorrer os adicionais
                    if (is_string($adicional)) { // Se String, chama o adicional
                        $iten[$adicional];
                    } else { // Se Array Percorrer o array
                        $iten2 = $iten;
                        foreach ($adicional as $key => $a) {
                            if ($key == 0) { // Se primeiro item
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
        }

        return $itens;
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
