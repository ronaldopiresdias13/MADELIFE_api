<?php

namespace App\Http\Controllers\Api;

use App\Transcricao;
use App\TranscricaoProduto;
use App\Horariomedicamento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TranscricoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
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
            $itens = Transcricao::with($with)->where('ativo', true);
        } else {
            $itens = Transcricao::where('ativo', true);
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
        DB::transaction(function () use ($request) {
            $transcricao = Transcricao::create([
                'empresa_id'      => $request->empresa_id,
                'ordemservico_id' => $request->ordemservico_id,
                'profissional_id' => $request->profissional_id,
                'medico'          => $request->medico,
                'receita'         => $request->receita,
                'crm'             => $request->crm,
            ]);

            foreach ($request->itensTranscricao as $key => $iten) {
                $transcricao_produto = TranscricaoProduto::firstOrCreate([
                    'transcricao_id' => $transcricao->id,
                    'produto_id'     => $iten['produto']['id'],
                    'quantidade'     => $iten['quantidade'],
                    'apresentacao'   => $iten['apresentacao'],
                    'via'            => $iten['via'],
                    'frequencia'     => $iten['frequencia'],
                    'tempo'          => $iten['tempo'],
                    'status'         => $iten['status'],
                    'observacao'     => $iten['observacao'],
                ]);
                foreach ($iten['horariomedicamentos'] as $key => $horario) {
                    $horario_medicamento = Horariomedicamento::create([
                        'transcricao_produto_id' => $transcricao_produto->id,
                        'horario'                => $horario['horario']
                    ]);
                }
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transcricao  $transcricao
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Transcricao $transcricao)
    {
        $iten = $transcricao;

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
     * @param  \App\Transcricao  $transcricao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transcricao $transcricao)
    {
        // $transcricao->update($request->all());
        DB::transaction(function () use ($request, $transcricao) {
            $transcricao->empresa_id      = $request->empresa_id;
            $transcricao->ordemservico_id = $request->ordemservico_id;
            $transcricao->profissional_id = $request->profissional_id;
            $transcricao->medico          = $request->medico;
            $transcricao->receita         = $request->receita;
            $transcricao->crm             = $request->crm;
            $transcricao->save();

            foreach ($request->itensTranscricao as $key => $iten) {
                $transcricao_produto = TranscricaoProduto::updateOrCreate(
                    [
                        'id' => $iten['id'],
                    ],
                    [
                        'produto_id'     => $iten['produto']['id'],
                        'quantidade'     => $iten['quantidade'],
                        'apresentacao'   => $iten['apresentacao'],
                        'via'            => $iten['via'],
                        'frequencia'     => $iten['frequencia'],
                        'tempo'          => $iten['tempo'],
                        'status'         => $iten['status'],
                        'observacao'     => $iten['observacao'],
                    ]
                );
                foreach ($transcricao_produto->horariomedicamentos as $key => $horario) {
                    $horario->delete();
                }
                // $transcricao_produto->horariomedicamentos->delete();
                foreach ($iten['horariomedicamentos'] as $key => $horario) {
                    $horario_medicamento = Horariomedicamento::create([
                        'transcricao_produto_id' => $transcricao_produto->id,
                        'horario'                => $horario['horario']
                    ]);
                }
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transcricao  $transcricao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transcricao $transcricao)
    {
        $transcricao->ativo = false;
        $transcricao->save();
    }


    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listaTranscricoes(Request $request)
    {
        $user = $request->user();
        $profissional = $user->pessoa->profissional;

        $transcricoes = Transcricao::with([
            'ordemservico' => function ($query) {
                $query->select('id', 'orcamento_id');
                $query->with(['orcamento' => function ($query) {
                    $query->select('id');
                    $query->with(['homecare' => function ($query) {
                        $query->select('id', 'orcamento_id', 'paciente_id');
                        $query->with(['paciente' => function ($query) {
                            $query->select('id', 'pessoa_id');
                            $query->with(['pessoa' => function ($query) {
                                $query->select('id', 'nome');
                            }]);
                        }]);
                    }]);
                }]);
            }, 'profissional' => function ($query) {
                $query->select('id', 'pessoa_id');
                $query->with(['pessoa' => function ($query) {
                    $query->select('id', 'nome');
                }]);
            }
        ])
            ->where('empresa_id', $profissional->empresa_id)
            ->where('ativo', true)
            ->get(['id', 'ordemservico_id', 'profissional_id']);

        return $transcricoes;
    }
}
