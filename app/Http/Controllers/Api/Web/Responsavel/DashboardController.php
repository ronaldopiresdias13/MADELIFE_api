<?php

namespace App\Http\Controllers\Api\Web\Responsavel;

use App\Models\Escala;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $empresa_id = $user->pessoa->responsavel->empresa_id;

        // $request->data_ini = '2020-10-01';
        // $request->data_fim = '2020-10-02';

        $hoje = getdate();
        $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . $hoje['mday'];

        $escalas = Escala::with([
            'ordemservico' => function ($query) {
                $query->select('id', 'orcamento_id', 'profissional_id');
                $query->with(['orcamento' => function ($query) {
                    $query->select('id', 'cliente_id');
                    $query->with([
                        'cliente' => function ($query) {
                            $query->select('id', 'pessoa_id');
                            $query->with(['pessoa' => function ($query) {
                                $query->select('id', 'nome');
                            }]);
                        },
                        'homecare' => function ($query) {
                            $query->select('id', 'orcamento_id', 'paciente_id');
                            $query->with(['paciente' => function ($query) {
                                $query->select('id', 'pessoa_id', 'responsavel_id', 'sexo');
                                $query->with(['pessoa.enderecos']);
                                $query->with(['responsavel' => function ($query) {
                                    $query->select('id', 'pessoa_id', 'parentesco');
                                    $query->with(['pessoa' => function ($query) {
                                        $query->select('id', 'nome', 'nascimento', 'cpfcnpj', 'rgie');
                                    }]);
                                }]);
                            }]);
                        }
                    ]);
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
            // 'pontos',
            // 'cuidados',
            'relatorios',
            'monitoramentos',
            'relatorioescalas',
            // 'acaomedicamentos.transcricaoProduto.produto'
        ])
            ->where('ativo', true)
            ->where('empresa_id', $empresa_id)
            ->where('ordemservico_id', 'like', $request->ordemservico_id ? $request->ordemservico_id : '%')
            ->where('dataentrada', '>=', $request->data_ini ? $request->data_ini : $data)
            ->where('dataentrada', '<=', $request->data_fim ? $request->data_fim : $data)
            ->where('prestador_id', 'like', $request->prestador_id ? $request->prestador_id : '%')
            ->where('servico_id', 'like', $request->servico_id ? $request->servico_id : '%')
            // ->where('empresa_id', 'like', $request->empresa_id ? $request->empresa_id : '%')
            // ->limit(5)
            ->orderBy('dataentrada')->orderBy('periodo')
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
                'assinaturaprestador',
                'assinaturaresponsavel',
                'status'
            ]);

        $relatorio = [];

        foreach ($escalas as $key1 => $escala) {
            if (isset($escala['relatorioescalas'][0])) {
                foreach ($escala->relatorioescalas as $key2 => $relatorioescala) {
                    $file = '';
                    if (Storage::exists($relatorioescala['caminho'])) {
                        $file = Storage::get($relatorioescala['caminho']);
                    }
                    $escalas[$key1]['relatorioescalas'][$key2]['file'] = base64_encode($file);
                }
            }

            // if ($escala->formacao) {
            //     switch ($escala->formacao->descricao) {
            //         case 'Cuidador':
            //         case 'Técnico de Enfermagem':
            //         case 'Auxiliar de Enfermagem':
            //         case 'Enfermagem':
            //             $relatorio = $this->pushDiario($relatorio, $escala, true);
            //             break;
            //         default:
            //             $relatorio = $this->pushDiario($relatorio, $escala, false);
            //             break;
            //     }
            // }

            if ($escala->tipo) {
                switch ($escala->tipo) {
                    case 'Plantão':
                        $relatorio = $this->pushDiario($relatorio, $escala, true);
                        break;
                    default:
                        $relatorio = $this->pushDiario($relatorio, $escala, false);
                        break;
                }
            }
        }

        return $relatorio;
        // return $escalas;
    }

    private function pushDiario($array, $item, $enfermagem)
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
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function relatorioProdutividade(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->responsavel->empresa->id;

        // $request->data_ini = '2020-10-01';
        // $request->data_fim = '2020-10-02';

        $hoje = getdate();
        $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . $hoje['mday'];

        $escalas = Escala::with([
            'ordemservico' => function ($query) {
                $query->select('id', 'orcamento_id', 'profissional_id');
                $query->with(['orcamento' => function ($query) {
                    $query->select('id', 'cliente_id');
                    $query->with([
                        'cliente' => function ($query) {
                            $query->select('id', 'pessoa_id');
                            $query->with(['pessoa' => function ($query) {
                                $query->select('id', 'nome');
                            }]);
                        },
                        'homecare' => function ($query) {
                            $query->select('id', 'orcamento_id', 'paciente_id');
                            $query->with(['paciente' => function ($query) {
                                $query->select('id', 'pessoa_id');
                                $query->with(['pessoa' => function ($query) {
                                    $query->select('id', 'nome', 'nascimento', 'cpfcnpj', 'rgie');
                                }]);
                            }]);
                        }
                    ]);
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
            // 'cuidados',
            // 'relatorios',
            // 'monitoramentos',
            // 'acaomedicamentos.transcricaoProduto.produto'
        ])
            ->where('ativo', true)
            ->where('empresa_id', $empresa_id)
            ->where('ordemservico_id', 'like', $request->ordemservico_id ? $request->ordemservico_id : '%')
            ->where('dataentrada', '>=', $request->data_ini ? $request->data_ini : $data)
            ->where('dataentrada', '<=', $request->data_fim ? $request->data_fim : $data)
            ->where('prestador_id', 'like', $request->prestador_id ? $request->prestador_id : '%')
            ->where('servico_id', 'like', $request->servico_id ? $request->servico_id : '%')
            // ->where('empresa_id', 'like', $request->empresa_id ? $request->empresa_id : '%')
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
                'assinaturaprestador',
                'assinaturaresponsavel',
                'status'
            ]);

        $relatorio = [];

        foreach ($escalas as $key => $escala) {
            switch ($escala->formacao->descricao) {
                case 'Cuidador':
                case 'Técnico de Enfermagem':
                case 'Auxiliar de Enfermagem':
                case 'Enfermagem':
                    $relatorio = $this->pushProdutividade($relatorio, $escala, true);
                    break;
                default:
                    $relatorio = $this->pushProdutividade($relatorio, $escala, false);
                    break;
            }
        }

        return $relatorio;
    }

    private function pushProdutividade($array, $item, $enfermagem)
    {
        if ($enfermagem) {
            if (!key_exists('Enfermagem', $array)) {
                $array['Enfermagem'] = [];
            }
            array_push($array['Enfermagem'], $item);
        } else {
            if (!key_exists($item->formacao->descricao, $array)) {
                $array[$item->formacao->descricao] = [];
            }
            array_push($array[$item->formacao->descricao], $item);
        }
        return $array;
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function relatorioMedicamentos(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa->id;

        // $request->data_ini = '2020-10-01';
        // $request->data_fim = '2020-10-02';

        $hoje = getdate();
        $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . $hoje['mday'];

        $escalas = Escala::with([
            'ordemservico' => function ($query) {
                $query->select('id', 'orcamento_id', 'profissional_id');
                $query->with(['orcamento' => function ($query) {
                    $query->select('id', 'cliente_id');
                    $query->with([
                        'cliente' => function ($query) {
                            $query->select('id', 'pessoa_id');
                            $query->with(['pessoa' => function ($query) {
                                $query->select('id', 'nome');
                            }]);
                        },
                        'homecare' => function ($query) {
                            $query->select('id', 'orcamento_id', 'paciente_id');
                            $query->with(['paciente' => function ($query) {
                                $query->select('id', 'pessoa_id');
                                $query->with(['pessoa' => function ($query) {
                                    $query->select('id', 'nome', 'nascimento', 'cpfcnpj', 'rgie');
                                }]);
                            }]);
                        }
                    ]);
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
            'acaomedicamentos.transcricaoProduto.produto',
            'acaomedicamentos' => function ($query) {
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
        ])
            ->where('ativo', true)
            ->where('empresa_id', $empresa_id)
            ->where('ordemservico_id', 'like', $request->ordemservico_id ? $request->ordemservico_id : '%')
            ->where('dataentrada', '>=', $request->data_ini ? $request->data_ini : $data)
            ->where('dataentrada', '<=', $request->data_fim ? $request->data_fim : $data)
            ->where('prestador_id', 'like', $request->prestador_id ? $request->prestador_id : '%')
            ->where('servico_id', 'like', $request->servico_id ? $request->servico_id : '%')
            // ->where('empresa_id', 'like', $request->empresa_id ? $request->empresa_id : '%')
            // ->limit(5)
            ->orderBy('dataentrada')
            ->get([
                'id',
                'dataentrada',
                'datasaida',
                'horaentrada',
                'horasaida',
                // 'valorhoradiurno',
                // 'valorhoranoturno',
                // 'valoradicional',
                // 'motivoadicional',
                'servico_id',
                'formacao_id',
                'periodo',
                'tipo',
                'prestador_id',
                'ordemservico_id',
                'assinaturaprestador',
                'assinaturaresponsavel',
                'status'
            ]);

        $relatorio = [];

        foreach ($escalas as $key => $escala) {
            switch ($escala->formacao->descricao) {
                case 'Cuidador':
                case 'Técnico de Enfermagem':
                case 'Auxiliar de Enfermagem':
                case 'Enfermagem':
                    $relatorio = $this->pushMedicamentos($relatorio, $escala, true);
                    break;
                default:
                    $relatorio = $this->pushMedicamentos($relatorio, $escala, false);
                    break;
            }
        }

        return $relatorio;
    }
    private function pushMedicamentos($array, $item, $enfermagem)
    {
        if ($enfermagem) {
            if (!key_exists('Enfermagem', $array)) {
                $array['Enfermagem'] = [];
            }
            array_push($array['Enfermagem'], $item);
        } else {
            if (!key_exists($item->formacao->descricao, $array)) {
                $array[$item->formacao->descricao] = [];
            }
            array_push($array[$item->formacao->descricao], $item);
        }
        return $array;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
