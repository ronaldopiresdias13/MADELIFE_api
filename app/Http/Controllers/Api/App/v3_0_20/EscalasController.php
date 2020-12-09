<?php

namespace App\Http\Controllers\Api\App\v3_0_20;

use App\Escala;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            ->where('ativo', true)
            ->where('prestador_id', $prestador->id)
            ->where('dataentrada', $dataAtual)
            ->orWhere(function ($query) use ($prestador, $dataAtual) {
                $query
                    ->where('ativo', true)
                    ->where('prestador_id', $prestador->id)
                    ->where('datasaida', $dataAtual);
            })
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
    public function listEscalasMes(Request $request)
    {
        $user = $request->user();
        $prestador = $user->pessoa->prestador;
        $hoje = getdate();
        $dias = cal_days_in_month(CAL_GREGORIAN, $hoje['mon'], $hoje['year']); // 31

        // $escalas = Escala::with('ordemservico.orcamento.homecare.paciente.pessoa')
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
            }
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
