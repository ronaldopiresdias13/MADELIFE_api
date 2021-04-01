<?php

namespace App\Http\Controllers\Api\Web\Responsavel;

use App\Models\Escala;
use App\Http\Controllers\Controller;
use App\Models\Ordemservico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EscalasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listEscalasByIdResponsavel(Request $request)
    {
        $user = $request->user();
        $responsavel = $user->pessoa->responsavel;

        // $pacientes = Paciente::with(['homecares.orcamento.ordemservico.escalas' => function ($query) {
        //     $query->with('prestador.pessoa')->where('assinaturaresponsavel', '');
        // }])
        //     ->where('responsavel_id', $responsavel->id)
        //     ->get();

        // return Ordemservico::where('ordemservicos.empresa_id', $empresa['id'])->where('ordemservicos.ativo', 1)->where('ordemservicos.status', 1)
        //     ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
        //     ->join('orcamento_servico', 'orcamento_servico.orcamento_id', '=', 'orcamentos.id')
        //     ->join('servicos', 'servicos.id', '=', 'orcamento_servico.servico_id')
        //     ->select('servicos.descricao', DB::raw('count(servicos.id) as count'))
        //     ->groupBy('servicos.descricao')
        //     ->orderBy('count', 'desc')
        //     ->get();

        // $pacientes = Escala::where('responsavel_id', '%')
        $pacientes = Escala::where('escalas.id', 'like', '%')
            ->whereBetween('dataentrada', [$request->datainicio, $request->datafim])
            ->join('ordemservicos', 'ordemservicos.id', '=', 'escalas.ordemservico_id')
            ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
            ->join('homecares', 'homecares.orcamento_id', '=', 'orcamentos.id')
            ->join('pacientes', 'pacientes.id', '=', 'homecares.paciente_id')
            ->join('prestadores', 'prestadores.id', '=', 'escalas.prestador_id')
            ->join('pessoas', 'pessoas.id', '=', 'prestadores.pessoa_id')
            ->where('pacientes.responsavel_id', "=", $responsavel->id)
            ->where('escalas.assinaturaresponsavel', "=", '')
            ->where('escalas.ativo', "=", true)
            // ->orWhere('escalas.assinaturaresponsavel', "=", null)
            ->orWhere(function ($query) use ($request, $responsavel) {
                $query
                    ->where('pacientes.responsavel_id', "=", $responsavel->id)
                    ->whereBetween('dataentrada', [$request->datainicio, $request->datafim])
                    ->where('escalas.assinaturaresponsavel', "=", null)
                    ->where('escalas.ativo', "=", true);
            })
            ->orderBy('escalas.dataentrada')
            ->select('escalas.*', 'pessoas.nome')
            ->get();

        return $pacientes;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listEscalasByIdOrdemServico(Ordemservico $ordemservico)
    {
        return Escala::with(['prestador.pessoa.conselhos', 'prestador.formacoes'])
            ->where('ativo', true)
            ->where('ordemservico_id', $ordemservico->id)
            ->get();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assinar(Request $request)
    {
        foreach ($request['escalas'] as $key => $escala) {
            $e = Escala::find($escala['id']);
            $e->assinaturaresponsavel = $request['assinatura'];
            $e->update();
        }
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
    /**
     * Display a listing of the resource.dashboardPegarTodosOsRegistrosPorIdDaEmpresa
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->responsavel->empresa_id;
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
            ->where('ativo', true)
            ->where('empresa_id', $empresa_id)
            ->where('ordemservico_id', 'like', $request->ordemservico_id ? $request->ordemservico_id : '%')
            ->where(DB::raw("date_format(str_to_date(escalas.dataentrada, '%Y-%m-%d'), '%Y-%m')"), "=", $request['periodo'])
            ->orderBy('dataentrada')
            ->get([
                'id', 'dataentrada', 'datasaida', 'horaentrada', 'horasaida', 'valorhoradiurno', 'valorhoranoturno', 'valoradicional', 'motivoadicional', 'servico_id', 'periodo', 'tipo', 'prestador_id', 'ordemservico_id', 'status'
            ]);
        return $escalas;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            ->join('homecares', 'homecares.orcamento_id', '=', 'orcamentos.id')
            ->join('pacientes', 'pacientes.id', '=', 'homecares.paciente_id')
            ->join('prestadores', 'prestadores.id', '=', 'escalas.prestador_id')
            ->join('pessoas', 'pessoas.id', '=', 'prestadores.pessoa_id')
            ->where('pacientes.responsavel_id', "=", $user->pessoa->responsavel->id)
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
}
