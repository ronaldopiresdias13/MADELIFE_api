<?php

namespace App\Http\Controllers\Api\Web\AreaClinica;

use App\Escala;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Boolean;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function relatorioDiario(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa->id;

        $request->data_ini = '2020-10-01';
        $request->data_fim = '2020-10-05';

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
            'formacao',
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
            ->where('dataentrada', '>=', $request->data_ini ? $request->data_ini : $data)
            ->where('dataentrada', '<=', $request->data_fim ? $request->data_fim : $data)
            ->where('prestador_id', 'like', $request->prestador_id ? $request->prestador_id : '%')
            ->where('servico_id', 'like', $request->servico_id ? $request->servico_id : '%')
            ->where('empresa_id', 'like', $request->empresa_id ? $request->empresa_id : '%')
            // ->limit(5)
            ->orderBy('dataentrada')
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
                'formacao_id',
                'periodo',
                'tipo',
                'prestador_id',
                'ordemservico_id',
                'status'
            ]);

        $relatorio = [];

        foreach ($escalas as $key => $escala) {
            switch ($escala->formacao->descricao) {
                case 'Cuidador':
                case 'Técnico de Enfermagem':
                case 'Auxiliar de Enfermagem':
                case 'Enfermagem':
                    $relatorio = $this->push($relatorio, $escala, true);
                    break;
                default:
                    $relatorio = $this->push($relatorio, $escala, false);
                    break;
            }
        }

        return $relatorio;
        // return $escalas;
    }

    private function push($array, $item, $enfermagem)
    {
        if ($enfermagem) {
            if (!key_exists('Enfermagem', $array)) {
                $array['Enfermagem'] = [];
            }
            if (!key_exists($item->dataentrada, $array['Enfermagem'])) {
                $array['Enfermagem'][$item->dataentrada] = [];
            }
            array_push($array['Enfermagem'][$item->dataentrada], $item);
        } else {
            if (!key_exists($item->formacao->descricao, $array)) {
                $array[$item->formacao->descricao] = [];
            }
            if (!key_exists($item->dataentrada, $array[$item->formacao->descricao])) {
                $array[$item->formacao->descricao][$item->dataentrada] = [];
            }
            array_push($array[$item->formacao->descricao][$item->dataentrada], $item);
        }
        return $array;
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
}
