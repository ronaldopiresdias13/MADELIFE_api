<?php

namespace App\Http\Controllers\Api;

use App\Models\Escala;
use App\Models\Empresa;
use App\Models\Cuidado;
use App\Models\CuidadoEscala;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ordemservico;
use App\Models\OrdemservicoServico;
use App\Models\Prestador;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class EscalasController extends Controller
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
            $itens = Escala::with($with)->where('ativo', true);
        } else {
            $itens = Escala::where('ativo', true);
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

        // if ($request['take']) {
        //     $itens->take($request['take']);
        // }

        // if ($request['limit']) {
        //     $itens->limit($request['limit']);
        // }

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

        // return $itens->paginate(5);
        return $itens;
        // return '$itens';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ordemservico = Ordemservico::with(
            [
                'orcamento.servicos' => function ($query) use ($request) {
                    $query->with('servico')->whereHas('servico', function (Builder $builder) use ($request) {
                        $builder->where('id', $request->servico_id);
                    });
                }
            ]
        )
            ->find($request->ordemservico_id);

        $tipo   = $ordemservico->orcamento->servicos[0]->basecobranca;
        $horasD = $ordemservico->orcamento->servicos[0]->horascuidadodiurno;
        $horasN = $ordemservico->orcamento->servicos[0]->horascuidadonoturno;
        $valorD = $ordemservico->orcamento->servicos[0]->custodiurno;
        $valorN = $ordemservico->orcamento->servicos[0]->custonoturno;

        $escala = new Escala();
        if ($ordemservico) {
            $escala->tipo             = $tipo;
            $escala->valorhoradiurno  = $horasD ? ($valorD ? ($tipo == 'Plantão' ? $valorD / $horasD : $valorD) : $valorD) : 0;
            $escala->valorhoranoturno = $horasN ? ($valorN ? ($tipo == 'Plantão' ? $valorN / $horasN : $valorN) : $valorN) : 0;
            $escala->valoradicional   = 0;
        }
        $escala->empresa_id            = $request->empresa_id;
        $escala->ordemservico_id       = $request->ordemservico_id;
        $escala->prestador_id          = $request->prestador_id;
        $escala->servico_id            = $request->servico_id;
        $escala->formacao_id           = $request->formacao_id ? $request->formacao_id : null;
        $escala->horaentrada           = $request->horaentrada;
        $escala->horasaida             = $request->horasaida;
        $escala->dataentrada           = $request->dataentrada;
        $escala->datasaida             = $request->datasaida;
        $escala->periodo               = $request->periodo;
        $escala->assinaturaprestador   = $request->assinaturaprestador   ?: $escala->assinaturaprestador;
        $escala->assinaturaresponsavel = $request->assinaturaresponsavel ?: $escala->assinaturaresponsavel;
        $escala->observacao            = $request->observacao;
        $escala->status                = $request->status;
        $escala->folga                 = $request->folga;
        $escala->substituto            = $request->substituto;
        $escala->prestador_proprietario           = $request->prestador_proprietario;
        $escala->save();

        foreach ($request->cuidados as $key => $cuidado) {
            CuidadoEscala::create([
                'escala_id'  => $escala->id,
                'cuidado_id' => Cuidado::find($cuidado['cuidado']['id'])->id,
                'data'       => $cuidado['data'],
                'hora'       => $cuidado['hora'],
                'status'     => $cuidado['status'],
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Escala $escala)
    {
        $iten = $escala;

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
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Escala $escala)
    {
        $escala->empresa_id            = $request->empresa_id;
        $escala->ordemservico_id       = $request->ordemservico_id;
        $escala->prestador_id          = $request->prestador_id;
        $escala->servico_id            = $request->servico_id;
        $escala->horaentrada           = $request->horaentrada;
        $escala->horasaida             = $request->horasaida;
        $escala->dataentrada           = $request->dataentrada;
        $escala->datasaida             = $request->datasaida;
        $escala->periodo               = $request->periodo;
        $escala->assinaturaprestador   = $request->assinaturaprestador ?: $escala->assinaturaprestador;
        $escala->assinaturaresponsavel = $request->assinaturaresponsavel ?: $escala->assinaturaresponsavel;
        $escala->observacao            = $request->observacao;
        $escala->status                = $request->status;
        $escala->editavel              = $request->editavel;
        $escala->folga                 = $request->folga;
        $escala->substituto            = $request->substituto;
        $escala->tipo                  = $request->tipo;
        $escala->valorhoradiurno       = $request->valorhoradiurno;
        $escala->valorhoranoturno      = $request->valorhoranoturno;
        $escala->valoradicional        = $request->valoradicional;
        $escala->motivoadicional       = $request->motivoadicional;
        $escala->valordesconto         = $request->valordesconto;
        $escala->motivodesconto        = $request->motivodesconto;
        $escala->observacoes           = $request->observacoes;
        $escala->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function destroy(Escala $escala)
    {
        $escala->ativo = false;
        $escala->save();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEscalasMesApp(Request $request)
    {
        $user = $request->user();
        $prestador = $user->pessoa->prestador;
        $hoje = getdate();
        $dias = cal_days_in_month(CAL_GREGORIAN, $hoje['mon'], $hoje['year']); // 31

        // $escalas = Escala::with('ordemservico.orcamento.homecare.paciente.pessoa')
        $escalas = Escala::with([
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
            }
        ])
            ->where('ativo', true)
            ->where('prestador_id', $prestador->id)
            ->where(
                'datasaida',
                '>=',
                $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-01'
            )
            ->where(
                'dataentrada',
                '<=',
                $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . $dias
            )
            ->orderBy('dataentrada')
            ->get([
                'id',
                'ordemservico_id',
                'periodo',
                'dataentrada',
                'datasaida',
                'horaentrada',
                'horasaida',
                'status',
            ]);

        return $escalas;
    }

    public function buscaescalasdodia(Empresa $empresa)
    {
        // return DB::select('select * from escalas e inner join pontos p on p.escala_id = e.id limit 3');
        // return Escala::all();
        // return Escala::With(['servico', 'prestador.formacoes', 'pontos', 'prestador.pessoa.conselhos', 'ordemservico.orcamento.homecare.paciente.pessoa'])->where('ativo', true)->where('dataentrada', date('Y-m-d'))->get();
        return Escala::With([
            'cuidados',
            'monitoramentos',
            'relatorios',
            'servico',
            'prestador.formacoes',
            'pontos',
            'prestador.pessoa.conselhos',
            'ordemservico.orcamento.homecare.paciente.pessoa'
        ])
            ->where('ativo', true)
            ->where('empresa_id', $empresa->id)
            ->where('dataentrada', date('Y-m-d'))
            ->get();
        // return DB::table('escalas')->join('pontos', 'pontos.escala_id', '=', 'escalas.id')->where('ativo', true)->limit(1)->get();
    }

    public function buscaPontosPorPeriodoEPaciente(string $paciente, string $data1, string $data2)
    {
        return Escala::With([
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
            },
            'servico' => function ($query) {
                $query->select('id', 'descricao');
            },
            'prestador' => function ($query) {
                $query->select('id', 'pessoa_id');
                $query->with(['formacoes' => function ($query) {
                    $query->select('prestador_id', 'descricao');
                }]);
                $query->with(['pessoa' => function ($query) {
                    $query->select('id', 'nome');
                    $query->with(['conselhos' => function ($query) {
                        $query->select('pessoa_id', 'instituicao', 'uf', 'numero');
                    }]);
                }]);
            },
            'pontos',
            'cuidados',
        ])->where('ativo', true)
            ->where('ordemservico_id', $paciente)
            ->where('empresa_id', 1)
            ->where('dataentrada', '>=', $data1)
            ->where('dataentrada', '<=', $data2)
            ->get([
                'id', 'dataentrada', 'servico_id', 'periodo', 'tipo', 'prestador_id', 'status'
            ]);
    }

    public function dashboardClonarEscalas(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;

        foreach ($request->escalas as $key => $escala) {
            $prof = Prestador::find($escala['prestador_id'])->formacoes;
            $formacao = $prof->first();
            $cuidados = Escala::find($escala['escala_id'])->cuidados;
            $ordemservico = Ordemservico::with(
                [
                    'orcamento.servicos' => function ($query) use ($escala) {
                        $query->with('servico')->whereHas('servico', function (Builder $builder) use ($escala) {
                            $builder->where('id', $escala['servico_id']);
                        });
                    }
                ]
            )
                ->find($escala['ordemservico_id']);
            $e = Escala::create([

                'empresa_id'             => $empresa_id,
                'ordemservico_id'        => $escala['ordemservico_id'],
                'prestador_id'           => $escala['prestador_id'],
                'servico_id'             => $escala['servico_id'],
                'formacao_id'            => $formacao ? $formacao->id : null,
                'horaentrada'            => $escala['horaentrada'],
                'horasaida'              => $escala['horasaida'],
                'dataentrada'            => $escala['dataentrada'],
                'datasaida'              => $escala['datasaida'],
                'periodo'                => $escala['periodo'],
                'tipo'                   => $escala['tipo'] ? $escala['tipo'] :  $ordemservico->orcamento->servicos[0]->basecobranca,
                'assinaturaprestador'    => '',
                'assinaturaresponsavel'  => '',
                'observacao'             => "",
                'status'                 => false,
                'folga'                  => false,
                'substituto'             => null,
                'prestador_proprietario' => $escala['prestador_proprietario'],

            ]);
            // return $cuidados;
            foreach ($cuidados as $key => $cuidado) {
                CuidadoEscala::create([
                    'escala_id'  => $e->id,
                    'cuidado_id' => $cuidado['id'],
                    'data'       => null,
                    'hora'       => null,
                    'status'     => false,
                ]);
            }
        }
    }
}
