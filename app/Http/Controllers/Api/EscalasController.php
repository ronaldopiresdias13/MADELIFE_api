<?php

namespace App\Http\Controllers\Api;

use App\Escala;
use App\Empresa;
use App\Cuidado;
use App\CuidadoEscala;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrdemservicoServico;
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
        $ordemservicoServico = OrdemservicoServico::where('ordemservico_id', $request->ordemservico_id)
            ->where('servico_id', $request->servico_id)
            ->first();

        $escala = new Escala();
        if ($ordemservicoServico) {
            $escala->tipo             = $ordemservicoServico->descricao;
            $escala->valorhoradiurno  = $ordemservicoServico->valordiurno;
            $escala->valorhoranoturno = $ordemservicoServico->valornoturno;
            $escala->valoradicional   = 0;
        }
        $escala->empresa_id            = $request->empresa_id;
        $escala->ordemservico_id       = $request->ordemservico_id;
        $escala->prestador_id          = $request->prestador_id;
        $escala->servico_id            = $request->servico_id;
        $escala->horaentrada           = $request->horaentrada;
        $escala->horasaida             = $request->horasaida;
        $escala->dataentrada           = $request->dataentrada;
        $escala->datasaida             = $request->datasaida;
        $escala->periodo               = $request->periodo;
        $escala->assinaturaprestador   = $request->assinaturaprestador;
        $escala->assinaturaresponsavel = $request->assinaturaresponsavel;
        $escala->observacao            = $request->observacao;
        $escala->status                = $request->status;
        $escala->folga                 = $request->folga;
        $escala->substituto            = $request->substituto;
        $escala->save();

        foreach ($request->cuidados as $key => $cuidado) {
            $cuidado_escala = CuidadoEscala::create([
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
        $escala->assinaturaprestador   = $request->assinaturaprestador;
        $escala->assinaturaresponsavel = $request->assinaturaresponsavel;
        $escala->observacao            = $request->observacao;
        $escala->status                = $request->status;
        $escala->folga                 = $request->folga;
        $escala->substituto            = $request->substituto;
        $escala->tipo                  = $request->tipo;
        $escala->valorhoradiurno       = $request->valorhoradiurno;
        $escala->valorhoranoturno      = $request->valorhoranoturno;
        $escala->valoradicional        = $request->valoradicional;
        $escala->motivoadicional       = $request->motivoadicional;
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
    public function getEscalasHojeApp(Request $request)
    {
        // $user = $request->user();
        // $prestador = $user->pessoa->prestador;

        // $hoje = getdate();

        // $escalasHoje = Escala::where('prestador_id', $prestador->id)
        //     ->where(
        //         'dataentrada',
        //         $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . $hoje['mday']
        //     )->select(
        //         'escalas.id',
        //         'escalas.periodo',
        //         'escalas.dataentrada',
        //         'escalas.datasaida',
        //         'escalas.horaentrada',
        //         'escalas.horasaida',
        //         'escalas.status'
        //     )
        //     ->orderBy('dataentrada', 'asc')
        //     ->get();

        // return $escalasHoje;

        $user = $request->user();
        $prestador = $user->pessoa->prestador;

        $hoje = getdate();

        $escalas = Escala::with(['ordemservico' => function (BelongsTo $query) {
            $query->select('id', 'orcamento_id');
            $query->with(['orcamento' => function (BelongsTo $query) {
                $query->select('id');
                $query->with(['homecare' => function ($query) {
                    $query->select('id', 'orcamento_id', 'nome');
                }]);
            }]);
        }])
            ->where('prestador_id', $prestador->id)
            ->where(
                'dataentrada',
                $hoje['year'] . '-' . ($hoje['mon'] <= 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . $hoje['mday']
            )
            ->get([
                'id',
                'ordemservico_id',
                'periodo',
                'dataentrada',
                'datasaida',
                'horaentrada',
                'horasaida',
                'status'
            ]);

        return $escalas;
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

        $escalas = Escala::with(['ordemservico' => function (BelongsTo $query) {
            $query->select('id', 'orcamento_id');
            $query->with(['orcamento' => function (BelongsTo $query) {
                $query->select('id');
                $query->with(['homecare' => function (HasOne $query) {
                    $query->select('id', 'orcamento_id', 'nome');
                }]);
            }]);
        }])
            ->where('prestador_id', $prestador->id)
            ->where(
                'dataentrada',
                '>=',
                $hoje['year'] . '-' . ($hoje['mon'] <= 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . 1
            )
            ->where(
                'dataentrada',
                '<=',
                $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . $dias
            )
            ->get([
                'id',
                'ordemservico_id',
                'periodo',
                'dataentrada',
                'datasaida',
                'horaentrada',
                'horasaida',
                'status'
            ]);

        return $escalas;
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function getEscalaIdApp(Request $request, Escala $escala)
    {
        return $escala;
    }

    public function buscaescalasdodia(Empresa $empresa)
    {
        // return DB::select('select * from escalas e inner join pontos p on p.escala_id = e.id limit 3');
        // return Escala::all();
        // return Escala::With(['servico', 'prestador.formacoes', 'pontos', 'prestador.pessoa.conselhos', 'ordemservico.orcamento.homecare.paciente.pessoa'])->where('ativo', true)->where('dataentrada', date('Y-m-d'))->get();
        return Escala::With(['cuidados', 'monitoramentos', 'relatorios', 'servico', 'prestador.formacoes', 'pontos', 'prestador.pessoa.conselhos', 'ordemservico.orcamento.homecare.paciente.pessoa'])->where('ativo', true)->where('dataentrada', date('Y-m-d'))->get();
        // return DB::table('escalas')->join('pontos', 'pontos.escala_id', '=', 'escalas.id')->where('ativo', true)->limit(1)->get();
    }
}
