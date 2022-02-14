<?php

namespace App\Http\Controllers\Api\App\v3_1_4;

use App\Models\Escala;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EscalasController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listEscalasHoje(Request $request)
    {
        $user = $request->user();
        $prestador = $user->pessoa->prestador;

        $hoje = getdate();
        $dataAtual =
            $hoje['year']
            . '-' .
            ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon'])
            . '-' .
            ($hoje['mday'] < 10 ? '0' . $hoje['mday'] : $hoje['mday']);
        // return $dataAtual;
        $escalas = Escala::with([
            'pontos',
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
        ->join('ordemservicos', 'ordemservicos.id', '=', 'escalas.ordemservico_id')
            ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
            ->join('homecares', 'homecares.orcamento_id', '=', 'orcamentos.id')
            ->join('pacientes', 'homecares.paciente_id', '=', 'pacientes.id')
            ->where('escalas.ativo', true)
            ->where('escalas.prestador_id', $prestador->id)
            ->where('escalas.dataentrada', $dataAtual)
            ->orWhere(function ($query) use ($prestador, $dataAtual) {
                        $query
                            ->where('escalas.ativo', true)
                            ->where('escalas.prestador_id', $prestador->id)
                            ->where('escalas.datasaida', $dataAtual);
                    })
            ->select('escalas.id',
            'escalas.ordemservico_id',
            'escalas.periodo',
            'escalas.dataentrada',
            'escalas.datasaida',
            'escalas.horaentrada',
            'escalas.horasaida',
            'escalas.folga',
            'escalas.editavel',
            'escalas.status', DB::raw("IF(exists(SELECT it.id FROM internacoes it WHERE it.paciente_id = pacientes.id AND escalas.dataentrada BETWEEN it.data_inicio  AND  if(it.data_final IS NULL OR it.data_final = '', CURDATE(), it.data_final) AND it.deleted_at IS null), 1, 0 ) AS internacao"))
            ->orderBy('escalas.dataentrada')
            ->get();

        return $escalas;
        //     ->where('ativo', true)
        //     ->where('prestador_id', $prestador->id)
        //     ->where('dataentrada', $dataAtual)
        //     ->orWhere(function ($query) use ($prestador, $dataAtual) {
        //         $query
        //             ->where('ativo', true)
        //             ->where('prestador_id', $prestador->id)
        //             ->where('datasaida', $dataAtual);
        //     })
        //     ->get([
        //         'id',
        //         'ordemservico_id',
        //         'periodo',
        //         'dataentrada',
        //         'datasaida',
        //         'horaentrada',
        //         'horasaida',
        //         'editavel',
        //         'status'
        //     ]);

        // return $escalas;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listEscalasMes(Request $request)
    {
        // return $request;
        $user = $request->user();
        $prestador = $user->pessoa->prestador;
        // $hoje = getdate();
        // $dias = cal_days_in_month(CAL_GREGORIAN, $hoje['mon'], $hoje['year']); // 31

        // $escalas = Escala::with('ordemservico.orcamento.homecare.paciente.pessoa')
        $escalas = Escala::with([
            'pontos',
            'ordemservico' => function ($query){
                $query->select('id', 'orcamento_id');
                $query->with(['orcamento' => function ($query) {
                    $query->select('id');
                    $query->with(['homecare' => function ($query) {
                        $query->select('id', 'orcamento_id', 'paciente_id');
                        $query->with(['paciente' => function ($query){
                            $query->select('id', 'pessoa_id');
                            $query->with(['pessoa' => function ($query) {
                                $query->select('id', 'nome');
                            }]);
                        }]);
                    }]);
                }]);
            },
        ])
            ->join('ordemservicos', 'ordemservicos.id', '=', 'escalas.ordemservico_id')
            ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
            ->join('homecares', 'homecares.orcamento_id', '=', 'orcamentos.id')
            ->join('pacientes', 'homecares.paciente_id', '=', 'pacientes.id')
            ->where('escalas.ativo', true)
            ->where('escalas.prestador_id', $prestador->id)
            ->whereBetween('escalas.dataentrada', [$request->data_ini, $request->data_fim])
            ->select('escalas.id',
            'escalas.ordemservico_id',
            'escalas.periodo',
            'escalas.dataentrada',
            'escalas.datasaida',
            'escalas.horaentrada',
            'escalas.horasaida',
            'escalas.folga',
            'escalas.editavel',
            'escalas.status', DB::raw("IF(exists(SELECT it.id FROM internacoes it WHERE it.paciente_id = pacientes.id AND escalas.dataentrada BETWEEN it.data_inicio  AND  if(it.data_final IS NULL OR it.data_final = '', CURDATE(), it.data_final) AND it.deleted_at IS null), 1, 0 ) AS internacao"))
            ->orderBy('escalas.dataentrada')
            ->get();

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
    public function getEscalaId(Escala $escala)
    {
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
            },
            'pontos'
        ])
            ->where('id', $escala->id)
            ->first();

        return $escalas;
    }

    public function getCuidadosByEscalaId(Escala $escala)
    {
        return $escala->cuidados;
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
}
