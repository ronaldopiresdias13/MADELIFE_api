<?php

namespace App\Http\Controllers\Api\Web;

use App\Models\Escala;
use App\Models\Homecare;
use App\Http\Controllers\Controller;
use App\Models\Paciente;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EscalasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa->id;

        $hoje = getdate();
        $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . $hoje['mday'];

        $escalas = Escala::with([
            'ordemservico' => function ($query) {
                $query->select('id', 'orcamento_id', 'profissional_id');
                
                $query->with(['profissional.pessoa', 'orcamento' => function ($query) {
                    $query->select('id', 'cliente_id');
                    $query->with(['homecare' => function ($query) {
                        $query->select('id', 'orcamento_id', 'paciente_id');
                        $query->with(['paciente' => function ($query) {
                            $query->select('id', 'pessoa_id', 'responsavel_id');
                            $query->with(['pessoa' => function ($query) {
                                $query->select('id', 'nome', 'observacoes');
                                
                            }]);
                            $query->with([ 'responsavel' => function ($query) {
                                $query->select('id', 'pessoa_id');
                                $query->with(['pessoa' => function ($query) {
                                    $query->select('id', 'nome');
                                }]);
                                $query->with(['pessoa.telefones' => function ($query) {
                                    $query->select('telefone_id', 'telefone');
                                }]);
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
                    $query->with(['telefones', 'conselhos' => function ($query) {
                        $query->select('pessoa_id', 'instituicao', 'uf', 'numero');
                    }]);
                }]);
            },
            'pontos',
            'cuidados',
            'relatorios',
            'monitoramentos',
            'relatorioescalas',
            'acaomedicamentos.transcricaoProduto.produto'
            
        ]);
        if ($request->supervisor == true) {
            $escalas = $escalas->whereHas('ordemservico', function (Builder $builder) use ($user) {
                $builder->where('profissional_id', $user->pessoa->profissional->id);
            });
        }
        if ($request->cliente_id) {
            $escalas = $escalas->whereHas('ordemservico.orcamento', function (Builder $builder) use ($request) {
                $builder->where('cliente_id', $request->cliente_id);
            });
        }
        $escalas = $escalas->where('ativo', true);
        $escalas = $escalas->where('empresa_id', $empresa_id);
        $escalas = $escalas->where('ordemservico_id', 'like', $request->ordemservico_id ? $request->ordemservico_id : '%');
        // ->where('dataentrada', '>=', $request->data_ini ? $request->data_ini : $data)
        // ->where('dataentrada', '<=', $request->data_fim ? $request->data_fim : $data)
        $escalas = $escalas->whereBetween('dataentrada', [$request->data_ini ? $request->data_ini : $data, $request->data_fim ? $request->data_fim : $data]);
        $escalas = $escalas->where('prestador_id', 'like', $request->prestador_id ? $request->prestador_id : '%');
        $escalas = $escalas->where('servico_id', 'like', $request->servico_id ? $request->servico_id : '%');
        $escalas = $escalas->where('empresa_id', 'like', $request->empresa_id ? $request->empresa_id : '%');
        // ->limit(5)
        $escalas = $escalas->orderBy('dataentrada');
        $escalas = $escalas->get([
            'id',
            'dataentrada',
            'datasaida',
            'horaentrada',
            'horasaida',
            'valorhoradiurno',
            'valorhoranoturno',
            'valoradicional',
            'motivoadicional',
            'servico_id',
            'periodo',
            'tipo',
            'prestador_id',
            'ordemservico_id',
            'status',
            'ativo',
            
        ]);
        return $escalas;
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
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function show(Escala $escala)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function destroy(Escala $escala)
    {
        //
    }
    public function dashboardPegarTodosOsRegistrosPorIdDaEmpresa(Request $request)
    {
        $user = $request->user();
        $hoje = getdate();
        $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . $hoje['mday'];

        $escalas = Escala::with([
            'ordemservico' => function ($query) {
                $query->select('id', 'orcamento_id', 'profissional_id');
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
            'relatorios',
            'monitoramentos',
            'acaomedicamentos.transcricaoProduto.produto'
        ])
            ->join('ordemservicos', 'ordemservicos.id', '=', 'escalas.ordemservico_id')
            ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
            ->where('orcamentos.cliente_id', "=", $user->pessoa->cliente->id)
            ->where('escalas.ativo', true)
            // ->whereIn('ordemservico_id', [2, 131])
            // ->where('ordemservico_id', 'like', $request->ordemservico_id ? $request->ordemservico_id : '%')
            ->where('escalas.dataentrada', '>=', $request->data_ini ? $request->data_ini : $data)
            ->where('escalas.dataentrada', '<=', $request->data_fim ? $request->data_fim : $data)
            // ->where('prestador_id', 'like', $request->prestador_id ? $request->prestador_id : '%')
            ->where('escalas.servico_id', 'like', $request->servico_id ? $request->servico_id : '%')
            // ->where('empresa_id', 'like', $request->empresa_id ? $request->empresa_id : '%')
            // ->limit(5)
            ->orderBy('escalas.dataentrada')
            ->get();
        // ->get([
        //     'id', 'dataentrada', 'datasaida', 'horaentrada', 'horasaida', 'valorhoradiurno', 'valorhoranoturno', 'valoradicional', 'motivoadicional', 'servico_id', 'periodo', 'tipo', 'prestador_id', 'ordemservico_id', 'status'
        // ]);
        return $escalas;
    }


    public function dashboardPegarTodosPacientesporId(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;

        $hoje = getdate();
        $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . $hoje['mday'];

        return Escala::with([
            'ordemservico' => function ($query) {
                $query->select('id', 'orcamento_id');
                $query->with(['profissional.pessoa', 'orcamento' => function ($query) {
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
        ])
            ->where(
                'prestador_id',
                $request->prestador_id,
            )
            ->whereBetween('dataentrada', [$request->data_ini ? $request->data_ini : $data, $request->data_fim ? $request->data_fim : $data])
            ->where('ativo', true)
            ->where('empresa_id', $empresa_id)
            ->get([
                'id',
                'dataentrada',
                'datasaida',
                'horaentrada',
                'horasaida',
                'valorhoradiurno',
                'valorhoranoturno',
                'valoradicional',
                'motivoadicional',
                'servico_id',
                'periodo',
                'tipo',
                'prestador_id',
                'ordemservico_id',
                'status',
                'ativo'
            ]);
    }

    public function dashboardClonarEscalas(Request $request)
    {
        // $user = $request->user();
        // $empresa_id = $user->pessoa->profissional->empresa_id;

        // $hoje = getdate();
        // $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . $hoje['mday'];

        // $escalas =  Escala::where(
        //         'prestador_id',
        //         $request->prestador_id,
        //     )
        //     ->whereBetween('dataentrada', [$request->data_ini ? $request->data_ini : $data, $request->data_fim ? $request->data_fim : $data])
        //     ->where('ativo', true)
        //     ->where('empresa_id', $empresa_id)
        //     ->get();

        //     $novasescalas = [];

        foreach ($request as $key => $escala) {
            $escala->status = false;

            DB::transaction(function () use ($request) {
                Escala::create($request->all());
            });

            array_push($escala);
        }
        return $escala;
    }

    public function EscalasPorPeriodo(Request $request)
    {

        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        // $empresa_id = 1;
        return DB::select(
            "SELECT e.id, e.ordemservico_id, e.servico_id, e.prestador_id, e.horaentrada, e.horasaida, e.dataentrada, e.datasaida, e.periodo, e.status, p.nome AS paciente, pp.nome AS prestador, s.descricao AS servico FROM escalas AS e
            INNER JOIN ordemservicos AS os
            ON e.ordemservico_id = os.id
            INNER JOIN orcamentos AS o
            ON o.id = os.orcamento_id
            INNER JOIN homecares AS hc 
            ON hc.orcamento_id = o.id
            INNER JOIN pacientes AS pc
            ON pc.id = hc.paciente_id
            INNER JOIN pessoas AS p
            ON p.id = pc.pessoa_id
            INNER JOIN prestadores AS pre
            ON e.prestador_id = pre.id
            INNER JOIN pessoas AS pp
            ON pp.id = pre.pessoa_id
            INNER JOIN servicos AS s
            ON e.servico_id = s.id
            WHERE e.dataentrada BETWEEN ? AND ?
            AND e.empresa_id = ?
            and e.ativo = 1",

            [
                $request->data_ini,
                $request->data_fim,
                $empresa_id
            ]
        );
    }
}
