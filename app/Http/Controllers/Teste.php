<?php

namespace App\Http\Controllers;

use App\Models\Escala;
use App\Models\Pagamentoexterno;
use App\Models\Pessoa;
use App\Models\Tipopessoa;
use App\Services\PontoService;
use Dompdf\Dompdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Teste extends Controller
{
    public function teste(Request $request)
    {
        $array =
            '[
            {
                "codigo_ibge": 5200050,
                "nome": "Abadia de Goiás",
                "latitude": -16.7573,
                "longitude": -49.4412,
                "capital": false,
                "codigo_uf": "GO"
            },
            {
                "codigo_ibge": 3100104,
                "nome": "Abadia dos Dourados",
                "latitude": -18.4831,
                "longitude": -47.3916,
                "capital": false,
                "codigo_uf": "MG"
            },
            {
                "codigo_ibge": 5200100,
                "nome": "Abadiânia",
                "latitude": -16.197,
                "longitude": -48.7057,
                "capital": false,
                "codigo_uf": "GO"
            },
            {
                "codigo_ibge": 3100203,
                "nome": "Abaeté",
                "latitude": -19.1551,
                "longitude": -45.4444,
                "capital": false,
                "codigo_uf": "MG"
            },
            {
                "codigo_ibge": 1500107,
                "nome": "Abaetetuba",
                "latitude": -1.72183,
                "longitude": -48.8788,
                "capital": false,
                "codigo_uf": "PA"
            },
            {
                "codigo_ibge": 2300101,
                "nome": "Abaiara",
                "latitude": -7.34588,
                "longitude": -39.0416,
                "capital": false,
                "codigo_uf": "CE"
            },
            {
                "codigo_ibge": 2900108,
                "nome": "Abaíra",
                "latitude": -13.2488,
                "longitude": -41.6619,
                "capital": false,
                "codigo_uf": "BA"
            },
            {
                "codigo_ibge": 2900207,
                "nome": "Abaré",
                "latitude": -8.72073,
                "longitude": -39.1162,
                "capital": false,
                "codigo_uf": "BA"
            },
            {
                "codigo_ibge": 4100103,
                "nome": "Abatiá",
                "latitude": -23.3049,
                "longitude": -50.3133,
                "capital": false,
                "codigo_uf": "PR"
            },
            {
                "codigo_ibge": 4200051,
                "nome": "Abdon Batista",
                "latitude": -27.6126,
                "longitude": -51.0233,
                "capital": false,
                "codigo_uf": "SC"
            },
            {
                "codigo_ibge": 1500131,
                "nome": "Abel Figueiredo",
                "latitude": -4.95333,
                "longitude": -48.3933,
                "capital": false,
                "codigo_uf": "PA"
            },
            {
                "codigo_ibge": 4200101,
                "nome": "Abelardo Luz",
                "latitude": -26.5716,
                "longitude": -52.3229,
                "capital": false,
                "codigo_uf": "SC"
            },
            {
                "codigo_ibge": 3100302,
                "nome": "Abre Campo",
                "latitude": -20.2996,
                "longitude": -42.4743,
                "capital": false,
                "codigo_uf": "MG"
            },
            {
                "codigo_ibge": 2600054,
                "nome": "Abreu e Lima",
                "latitude": -7.90072,
                "longitude": -34.8984,
                "capital": false,
                "codigo_uf": "PE"
            },
            {
                "codigo_ibge": 1700251,
                "nome": "Abreulândia",
                "latitude": -9.62101,
                "longitude": -49.1518,
                "capital": false,
                "codigo_uf": "TO"
            },
            {
                "codigo_ibge": 3100401,
                "nome": "Acaiaca",
                "latitude": -20.359,
                "longitude": -43.1439,
                "capital": false,
                "codigo_uf": "MG"
            },
            {
                "codigo_ibge": 2100055,
                "nome": "Açailândia",
                "latitude": -4.94714,
                "longitude": -47.5004,
                "capital": false,
                "codigo_uf": "MA"
            },
            {
                "codigo_ibge": 2900306,
                "nome": "Acajutiba",
                "latitude": -11.6575,
                "longitude": -38.0197,
                "capital": false,
                "codigo_uf": "BA"
            },
            {
                "codigo_ibge": 1500206,
                "nome": "Acará",
                "latitude": -1.95383,
                "longitude": -48.1985,
                "capital": false,
                "codigo_uf": "PA"
            },
            {
                "codigo_ibge": 2300150,
                "nome": "Acarape",
                "latitude": -4.22083,
                "longitude": -38.7055,
                "capital": false,
                "codigo_uf": "CE"
            },
            {
                "codigo_ibge": 2300200,
                "nome": "Acaraú",
                "latitude": -2.88769,
                "longitude": -40.1183,
                "capital": false,
                "codigo_uf": "CE"
            },
            {
                "codigo_ibge": 2400109,
                "nome": "Acari",
                "latitude": -6.4282,
                "longitude": -36.6347,
                "capital": false,
                "codigo_uf": "RN"
            },
            {
                "codigo_ibge": 2200053,
                "nome": "Acauã",
                "latitude": -8.21954,
                "longitude": -41.0831,
                "capital": false,
                "codigo_uf": "PI"
            },
            {
                "codigo_ibge": 4300034,
                "nome": "Aceguá",
                "latitude": -31.8665,
                "longitude": -54.1615,
                "capital": false,
                "codigo_uf": "RS"
            },
            {
                "codigo_ibge": 2300309,
                "nome": "Acopiara",
                "latitude": -6.08911,
                "longitude": -39.448,
                "capital": false,
                "codigo_uf": "CE"
            },
            {
                "codigo_ibge": 5100102,
                "nome": "Acorizal",
                "latitude": -15.194,
                "longitude": -56.3632,
                "capital": false,
                "codigo_uf": "MT"
            },
            {
                "codigo_ibge": 1200013,
                "nome": "Acrelândia",
                "latitude": -9.82581,
                "longitude": -66.8972,
                "capital": false,
                "codigo_uf": "AC"
            },
            {
                "codigo_ibge": 5200134,
                "nome": "Acreúna",
                "latitude": -17.396,
                "longitude": -50.3749,
                "capital": false,
                "codigo_uf": "GO"
            },
            {
                "codigo_ibge": 2400208,
                "nome": "Açu",
                "latitude": -5.58362,
                "longitude": -36.914,
                "capital": false,
                "codigo_uf": "RN"
            },
            {
                "codigo_ibge": 3100500,
                "nome": "Açucena",
                "latitude": -19.0671,
                "longitude": -42.5419,
                "capital": false,
                "codigo_uf": "MG"
            },
            {
                "codigo_ibge": 3500105,
                "nome": "Adamantina",
                "latitude": -21.682,
                "longitude": -51.0737,
                "capital": false,
                "codigo_uf": "SP"
            },
            {
                "codigo_ibge": 5200159,
                "nome": "Adelândia",
                "latitude": -16.4127,
                "longitude": -50.1657,
                "capital": false,
                "codigo_uf": "GO"
            },
            {
                "codigo_ibge": 3500204,
                "nome": "Adolfo",
                "latitude": -21.2325,
                "longitude": -49.6451,
                "capital": false,
                "codigo_uf": "SP"
            },
            {
                "codigo_ibge": 4100202,
                "nome": "Adrianópolis",
                "latitude": -24.6606,
                "longitude": -48.9922,
                "capital": false,
                "codigo_uf": "PR"
            }
            
        ]';
        $a = json_decode($array, true);
        return $a;
        $result = Pessoa::with('enderecos.cidade');

        $result->whereHas('enderecos.cidade', function (Builder $query) {
            $query->where('nome', 'Santa Fé do Sul');
        });

        $result = $result->get();

        return $result;

        return 'Stop';







        $request['ordemservico_id'] = 149;
        $itens = $this->relatorioDiario($request);
        $dompdf = new Dompdf();
        // $dompdf->loadHtml("<h1>Olá Mundo!</h1><p>Esse é meu primeiro PDF!</p>");

        // return view('teste3', ['itens' => $array]);

        // $dompdf = new Dompdf(["enable_remote" => true]);
        $dompdf->loadHtml(view('relatorio', ['itens' => $itens]));

        // ob_start();
        // require __DIR__. "../../../../resources/views/teste.php";
        // $dompdf->loadHtml(ob_get_clean());

        $dompdf->setPaper("A4");

        $dompdf->render();

        // $dompdf->stream("file.pdf");
        $dompdf->stream("file.pdf", ["Attachment" => false]);

        // var_dump($dompdf->output());
        // return 'Hello World!';
        // return view('teste');

        // return $request;

        // $hoje = getdate();
        // $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . ($hoje['mday'] < 10 ? '0' . $hoje['mday'] : $hoje['mday']);

        // $datainicio = $request['datainicio'] ? $request['datainicio'] : date("Y-m-01", strtotime($data));
        // $datafim    = $request['datafim']    ? $request['datafim']    : date("Y-m-t", strtotime($data));

        // return $datainicio;
        // return $datafim;


        // $hoje = getdate();
        // $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . ($hoje['mday'] < 10 ? '0' . $hoje['mday'] : $hoje['mday']);

        // return 'First day : '. date("Y-m-01", strtotime($data)).' - Last day : '. date("Y-m-t", strtotime($data));

        // return $data;

        // return response()->json([
        //     'success' => [
        //         'text' => 'Salvo com sucesso!',
        //         'duration' => 2000
        //     ]
        // ], 200)
        //     ->header('Content-Type', 'application/json');

        // return response()->json([
        //     'error' => [
        //         'text' => 'Erro ao salvar!'
        //     ]
        // ], 400)
        //     ->header('Content-Type', 'application/json');

        // return "Teste Back";
        // // DB::select('select * from users where active = ?', [1])

        // $hoje = getdate();
        // // $data = $hoje['year'] . '-' . (($hoje['mon'] - 11) < 10 ? '0' . ($hoje['mon'] - 11) : $hoje['mon']) . '-' . $hoje['mday'];
        // $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . $hoje['mday'];

        // return DB::select(
        //     'SELECT p.descricao, pes.nome, e.dataentrada FROM transcricao_produto tp
        //     INNER JOIN produtos p ON p.id = tp.produto_id
        //     INNER JOIN transcricoes t ON t.id = tp.transcricao_id
        //     INNER JOIN ordemservicos os ON os.id = t.ordemservico_id
        //     INNER JOIN orcamentos o ON o.id = os.orcamento_id
        //     INNER JOIN homecares h ON h.orcamento_id = o.id
        //     INNER JOIN pacientes pac ON pac.id = h.paciente_id
        //     INNER JOIN pessoas pes ON pes.id = pac.pessoa_id
        //     INNER JOIN escalas e ON e.ordemservico_id = os.id
        //     WHERE NOT EXISTS (SELECT * FROM acaomedicamentos am WHERE tp.id = am.transcricao_produto_id)
        //     AND os.id LIKE ?
        //     AND e.dataentrada BETWEEN ? AND ?',
        //     [
        //         $request['os'] ? $request['os'] : '%',
        //         $request['dataIni'] ? $request['dataIni'] : $data,
        //         $request['dataFim'] ? $request['dataFim'] : $data
        //     ]
        // );
        // $prestadores = Prestador::with([
        //     'pessoa:id,nome',
        //     'formacoes:formacoes.id,descricao',
        //     'pessoa.conselhos:conselhos.id,instituicao,numero,uf,pessoa_id',
        //     'pessoa.enderecos.cidade',
        //     'pessoa.telefones:telefones.id,telefone'
        // ])
        //     ->get(['id', 'pessoa_id']);

        // $result = [];

        // foreach ($prestadores as $key => $prestador) {
        //     $inserir = true;

        //     if ($inserir && $request['nome']) {
        //         if (str_contains(strtolower($prestador->pessoa->nome), strtolower($request['nome']))) {
        //             $inserir = true;
        //         } else {
        //             $inserir = false;
        //         }
        //     }
        //     if ($inserir && $request['formacao']) {
        //         $contain = false;
        //         foreach ($prestador->formacoes as $key => $formacao) {
        //             if (str_contains(strtolower($formacao->descricao), strtolower($request['formacao']))) {
        //                 $contain = true;
        //             }
        //         }
        //         if ($contain) {
        //             $inserir = true;
        //         } else {
        //             $inserir = false;
        //         }
        //     }
        //     if ($inserir && $request['cidade']) {
        //         if ($prestador->pessoa->enderecos) {
        //             $contain = false;
        //             foreach ($prestador->pessoa->enderecos as $key => $endereco) {
        //                 if ($endereco->cidade) {
        //                     if (str_contains(strtolower($endereco->cidade->nome), strtolower($request['cidade']))) {
        //                         $contain = true;
        //                     }
        //                 }
        //             }
        //             if ($contain) {
        //                 $inserir = true;
        //             } else {
        //                 $inserir = false;
        //             }
        //         } else {
        //             $inserir = false;
        //         }
        //     }

        //     if ($inserir && ($request['nome'] || $request['formacao'] || $request['cidade'])) {
        //         array_push($result, $prestador);
        //     }
        // }

        // return $result;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function relatorioDiario(Request $request)
    {
        $request['data_ini'] = '2021-02-01';
        $request['data_fim'] = '2021-02-28';
        // $user = $request->user();
        // $empresa_id = $user->pessoa->profissional->empresa->id;

        // $request->data_ini = '2020-10-01';
        // $request->data_fim = '2020-10-02';



        $hoje = getdate();
        $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . ($hoje['mday'] < 10 ? '0' . $hoje['mday'] : $hoje['mday']);

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
            // ->where('empresa_id', $empresa_id)
            ->where('ordemservico_id', 'like', $request->ordemservico_id ? $request->ordemservico_id : '%')
            ->where('dataentrada', '>=', $request->data_ini ? $request->data_ini : $data)
            ->where('dataentrada', '<=', $request->data_fim ? $request->data_fim : $data)
            ->where('prestador_id', 'like', $request->prestador_id ? $request->prestador_id : '%')
            ->where('servico_id', 'like', $request->servico_id ? $request->servico_id : '%')
            // ->where('empresa_id', 'like', $request->empresa_id ? $request->empresa_id : '%')
            ->limit(20)
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
}
