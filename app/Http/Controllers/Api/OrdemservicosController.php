<?php

namespace App\Http\Controllers\Api;

use App\Pessoa;
use App\Empresa;
use App\Responsavel;
use App\Ordemservico;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Orcamento;
use App\OrdemservicoServico;
use Illuminate\Support\Facades\DB;

class OrdemservicosController extends Controller
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
            $itens = Ordemservico::with($with)->where('ativo', true);
        } else {
            $itens = Ordemservico::where('ativo', true);
        }

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                // if ($key == 0) {
                //     $itens = Ordemservico::where(
                //         ($where['coluna']) ? $where['coluna'] : 'id',
                //         ($where['expressao']) ? $where['expressao'] : 'like',
                //         ($where['valor']) ? $where['valor'] : '%'
                //     );
                // } else {
                $itens->where(
                    ($where['coluna']) ? $where['coluna'] : 'id',
                    ($where['expressao']) ? $where['expressao'] : 'like',
                    ($where['valor']) ? $where['valor'] : '%'
                );
                // }
            }
            // } else {
            //     $itens = Ordemservico::where('id', 'like', '%');
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
            $ordemservico = Ordemservico::updateOrCreate(
                [
                    'orcamento_id' => $request['orcamento_id'],
                ],
                [
                    'empresa_id'             => $request['empresa_id'],
                    'responsavel_id'         => null,
                    'profissional_id'        => $request['profissional_id'],
                    'codigo'                 => $request['codigo'],
                    'inicio'                 => $request['inicio'],
                    'fim'                    => $request['fim'],
                    'status'                 => $request['status'],
                    'montagemequipe'         => $request['montagemequipe'],
                    'realizacaoprocedimento' => $request['realizacaoprocedimento'],
                ]
            );

            $orcamento = Orcamento::Where('id', $request['orcamento_id'])->first();

            foreach ($orcamento->servicos as $key => $servico) {
                if ($servico['pivot']['basecobranca'] == 'Plantão') {
                    OrdemservicoServico::create(
                        [
                            'ordemservico_id'  => $ordemservico->id,
                            'servico_id'       => $servico->id,
                            'descricao'        => $servico['pivot']['basecobranca'],
                            'valordiurno'      => ($servico['pivot']['custo'] / 2),
                            'valornoturno'     => ($servico['pivot']['custo'] / 2) + $servico['pivot']['adicionalnoturno'],
                        ]
                    );
                } else {
                    OrdemservicoServico::create(
                        [
                            'ordemservico_id'  => $ordemservico->id,
                            'servico_id'       => $servico->id,
                            'descricao'        => $servico['pivot']['basecobranca'],
                            'valordiurno'      => ($servico['pivot']['custo']),
                            'valornoturno'     => ($servico['pivot']['custo']) + $servico['pivot']['adicionalnoturno'],
                        ]
                    );
                }
            }
        });

        return response()->json('Ordem de Serviço cadastrado com sucesso!', 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ordemservico  $ordemservico
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Ordemservico $ordemservico)
    {
        $iten = $ordemservico;

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

        return $iten;
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
        $ordemservico->empresa_id             = $request['empresa_id'];
        $ordemservico->codigo                 = $request['codigo'];
        $ordemservico->orcamento_id           = $request['orcamento_id'];
        $ordemservico->responsavel_id         = $request['responsavel_id'];
        $ordemservico->inicio                 = $request['inicio'];
        $ordemservico->fim                    = $request['fim'];
        $ordemservico->status                 = $request['status'];
        $ordemservico->montagemequipe         = $request['montagemequipe'];
        $ordemservico->realizacaoprocedimento = $request['realizacaoprocedimento'];
        $ordemservico->descricaomotivo        = $request['descricaomotivo'];
        $ordemservico->dataencerramento       = $request['dataencerramento'];
        $ordemservico->motivo                 = $request['motivo'];
        $ordemservico->profissional_id        = $request['profissional_id'];
        $ordemservico->ativo                  = $request['ativo'];
        $ordemservico->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ordemservico  $ordemservico
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ordemservico $ordemservico)
    {
        $ordemservico->ativo = false;
        $ordemservico->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ordemservico  $ordemservico
     * @return \Illuminate\Http\Response
     */
    public function horariomedicamentos(Request $request, Ordemservico $ordemservico)
    {
        $iten = $ordemservico;
        foreach ($iten->transcricoes as $key => $transcricao) {
            foreach ($transcricao->produtos as $key => $produto) {
                $produto->transcricao_produto->horariomedicamentos;
            }
        }
        return $iten;
    }
    public function quantidadeordemservicos(Empresa $empresa)
    {
        //return Ordemservico::where('empresa_id',$empresa['id'])->count();
        // return DB::select("SELECT count(os.id) FROM ordemservicos os inner join orcamentos o on o.id = os.orcamento_id
        // where o.tipo = 'Home Care'");

        // $count = Ordemservico::with('orcamento')
        // ->where('empresa_id',$empresa['id'])
        // ->where('orcamentos.tipo','Home Care')
        // ->get();

        // dd($count);
        return Ordemservico::with('orcamento')
            ->where('empresa_id', $empresa['id'])
            ->where('status', 1)
            ->get()->where('orcamento.tipo', 'Home Care')->count();
    }

    public function groupbyservicos(Empresa $empresa)
    {
        return Ordemservico::where('ordemservicos.empresa_id', $empresa['id'])->where('ordemservicos.ativo', 1)->where('ordemservicos.status', 1)
            ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
            ->join('orcamento_servico', 'orcamento_servico.orcamento_id', '=', 'orcamentos.id')
            ->join('servicos', 'servicos.id', '=', 'orcamento_servico.servico_id')
            ->select('servicos.descricao', DB::raw('count(servicos.id) as count'))
            ->groupBy('servicos.descricao')
            ->orderBy('count', 'desc')
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
            'prestadores' => function ($query) {
                $query->select(DB::raw('prestadores.id', 'COUNT(prestadores.id) as count'));
                // $query->select(DB::raw('prestadores.id', "COUNT(prestadores.id) as total"))->groupBy("prestadores.id")->get();
            },
            'orcamento.cidade', 'orcamento' => function ($query) {
                // $query->select('id', 'cliente_id');
                $query->with(['homecare' => function ($query) {
                    // $query->select('id', 'orcamento_id', 'paciente_id');
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
            ->where('empresa_id', $profissional->empresa_id)
            ->where('ativo', true)
            ->get(['id', 'orcamento_id']);

        return $escalas;
    }
}
