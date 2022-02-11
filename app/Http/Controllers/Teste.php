<?php

namespace App\Http\Controllers;

use App\Models\CuidadoEscala;
use App\Models\Escala;
use App\Models\Itemtabelapreco;
use App\Models\Tabelapreco;
use App\Models\Versaotabelapreco;
use App\Models\OrdemservicoServico;
use App\Models\Servico;
use App\Models\Telefone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Teste extends Controller
{
    protected $empresa_id;
    protected $tabela;
    protected $versao;
    protected $file;

    public function teste(Request $request)
    {
        $this->empresa_id = 1;
        $this->tabela = $request->tabela;
        $this->versao = $request->versao;
        $this->file   = $request->file;

        $func = $this->tabela;
        return $this->$func();

        return 'ok';
    }

    private function brasindice()
    {
        $tabela = Tabelapreco::firstOrCreate([
            'nome'       => $this->tabela,
            'empresa_id' => $this->empresa_id
        ]);

        $versao = Versaotabelapreco::firstOrCreate([
            'versao'         => $this->versao,
            'tabelapreco_id' => $tabela->id
        ]);

        $md5 = md5_file($this->file);
        $caminho = 'temp/';
        $nome = $md5 . '.' . explode(';', explode('/', $this->file)[1])[0];
        $file = explode(',', $this->file)[1];
        Storage::put($caminho . $nome, base64_decode($file));

        $file = fopen(storage_path("app/public/temp/") . $nome, "r");

        while (!feof($file)) {
            $linha = fgets($file);
            $array = explode('","', $linha);
            foreach ($array as $key => $item) {
                $array[$key] = str_replace(['"', "\r", "\n"], "", $item);
            }

            if ($array[0]) {
                Itemtabelapreco::updateOrCreate(
                    [
                        'versaotabelapreco_id' => $versao->id,
                        'codigo'               => $array[0] . '.' . $array[2] . '.' . $array[4] // 18
                    ],
                    [
                        'tiss'  => '',
                        'tuss'  => '',
                        'nome'  => utf8_encode($array[3]) . ' ' . $array[5], // 2
                        'preco' => $array[9] //
                    ]
                );
            }
        }

        fclose($file);
        Storage::delete([$caminho . $nome]);

        return 'Importado Brasindice!!!';
    }

    private function simpro()
    {
        $tabela = Tabelapreco::firstOrCreate([
            'nome'       => $this->tabela,
            'empresa_id' => $this->empresa_id
        ]);

        $versao = Versaotabelapreco::firstOrCreate([
            'versao'         => $this->versao,
            'tabelapreco_id' => $tabela->id
        ]);

        $md5 = md5_file($this->file);
        $caminho = 'temp/';
        $nome = $md5 . '.' . explode(';', explode('/', $this->file)[1])[0];
        $file = explode(',', $this->file)[1];
        Storage::put($caminho . $nome, base64_decode($file));

        $file = fopen(storage_path("app/public/temp/") . $nome, "r");

        while (!feof($file)) {
            $linha = fgets($file);

            $codigo = substr($linha, 267, 10);
            $name = substr($linha, 32, 100);
            $preco = substr($linha, 155, 8) . '.' . substr($linha, 163, 2);

            if ($linha) {
                Itemtabelapreco::updateOrCreate(
                    [
                        'versaotabelapreco_id' => $versao->id,
                        'codigo'               => $codigo // $array[18]
                    ],
                    [
                        'tiss'  => '',
                        'tuss'  => '',
                        'nome'  => utf8_encode($name), // $array[2]),
                        'preco' => $preco // $array[6]
                    ]
                );
            }
        }

        fclose($file);
        Storage::delete([$caminho . $nome]);

        return 'Importado Simpro!!!';
    }

    public function teste2(Request $request)
    {
        $mesDe = '2021-10';
        $mesPara = '2021-11';

        $diaInicio = $mesDe . '-01';
        $diaFinal = $mesDe . '-28';

        $escalas = Escala::with('cuidados')->whereBetween('dataentrada', [$diaInicio, $diaFinal])->get();

        $esc = [];

        foreach ($escalas as $key => $escala) {
            if (!array_key_exists($escala->dataentrada, $esc)) {
                $esc[$escala->dataentrada] = [];
            }
            array_push($esc[$escala->dataentrada], $escala);
        }

        $quantidadeDias = date('d', strtotime('last day of next month', strtotime($diaInicio)));

        $datas = [];

        $d = 1;
        $dataPadrao = $mesDe . '-01';
        $dataClonagem = $mesPara . '-01';

        for ($i = 1; $i <= $quantidadeDias; $i++) {
            while (date("w", strtotime($dataPadrao)) != date("w", strtotime($dataClonagem))) {
                $dataPadrao = date('Y-m-d', strtotime('+1 day', strtotime($dataPadrao)));
                $d++;
            }

            $datas[$dataClonagem] = $dataPadrao;

            $dataClonagem = date('Y-m-d', strtotime('+1 day', strtotime($dataClonagem)));
            $dataPadrao = date('Y-m-d', strtotime('+1 day', strtotime($dataPadrao)));
            if ($d != 28) {
                $d++;
            } else {
                $d = 1;
                $dataPadrao = $mesDe . '-01';
            }
        }

        DB::transaction(function () use ($datas, $esc) {
            foreach ($datas as $key => $data) {
                foreach ($esc[$data] as $k => $e) {
                    $escala = new Escala();
                    $escala->empresa_id             = $e->empresa_id;
                    $escala->ordemservico_id        = $e->ordemservico_id;
                    $escala->prestador_proprietario = $e->prestador_proprietario;
                    $escala->prestador_id           = $e->prestador_proprietario;
                    $escala->servico_id             = $e->servico_id;
                    $escala->formacao_id            = $e->formacao_id;
                    $escala->horaentrada            = $e->horaentrada;
                    $escala->horasaida              = $e->horasaida;
                    $escala->dataentrada            = $key;
                    $dif = substr($e->datasaida, -2) - substr($e->dataentrada, -2);
                    $dia = substr($key, -2) + $dif;
                    $escala->datasaida              = substr($key, 0, -2) . ($dia <= 9 ? '0' . $dia : $dia);
                    $escala->periodo                = $e->periodo;
                    $escala->tipo                   = $e->tipo;
                    $escala->valorhoradiurno        = $e->valorhoradiurno;
                    $escala->valorhoranoturno       = $e->valorhoranoturno;
                    $escala->save();

                    foreach ($e->cuidados as $key => $cuidado) {
                        CuidadoEscala::create([
                            'escala_id'  => $escala->id,
                            'cuidado_id' => $cuidado['id'],
                            'data'       => null,
                            'hora'       => null,
                            'status'     => false,
                        ]);
                    }
                }
            }
        });

        return 'Clonado';

        // 179617

        // $d = 1;
        // $dataPadrao = $mesDe . '-01';
        // $dataClonagem = $mesPara . '-01';

        // $datas = [];

        // while ($d <= 28) {
        //     if (date("w", strtotime($dataPadrao)) != date("w", strtotime($dataClonagem))) {
        //         $dataPadrao = date('Y-m-d', strtotime('+1 day', strtotime($dataPadrao)));
        //         $d == 28 ? $d = 1 : $d ++;
        //     }

        //     $datas [$dataPadrao] = $dataClonagem;

        //     if (count($datas) == $quantidadeDias) {
        //         break;
        //     }

        //     $dataClonagem = date('Y-m-d', strtotime('+1 day', strtotime($dataClonagem)));
        //     $dataPadrao = date('Y-m-d', strtotime('+1 day', strtotime($dataPadrao)));
        //     $d == 28 ? $d = 1 : $d ++;
        // }

        return $datas;

        $primeiroDiaDe = '2021-10';





        // $user = $request->user();
        // $empresa_id = $user->pessoa->profissional->empresa_id;
        $empresa_id = 1;

        $escalas = Escala::where('ativo', true)
            ->where('empresa_id', $empresa_id)
            ->whereBetween('dataentrada', [$request->data_ini, $request->data_fim,])
            ->where('ordemservico_id', 'like', $request->ordemservico_id ? $request->ordemservico_id : '%')
            // ->limit(10)
            ->get();
        $teste = date('Y-m-d', strtotime('+1 month', strtotime('2021-02-28')));
        $last_day = date('d', strtotime('last day of this month', strtotime($request->data_ini)));
        $last_date = date('Y-m-d', strtotime('last day of this month', strtotime($request->data_ini)));
        $next_month_end = date('Y-m-d', strtotime('last day of next month', strtotime($request->data_ini)));
        $last_day_next_mont = date('d', strtotime('last day of next month', strtotime($request->data_ini)));

        foreach ($escalas as $key => $escala) {
            $escala->dataentrada = date('Y-m-d', strtotime($last_day % 2 == 1 ? '30 days' : '+1 month', strtotime($escala->dataentrada)));
            $escala->datasaida = date('Y-m-d', strtotime($last_day % 2 == 1 ? '30 days' : '+1 month', strtotime($escala->datasaida)));
        }
        $this->salvarEscalasClonadas($escalas);
    }

    public function salvarEscalasClonadas($escalas)
    {
        foreach ($escalas as $key => $escala) {
            $e = Escala::create([
                'empresa_id'             => $escala->empresa_id,
                'ordemservico_id'        => $escala->ordemservico_id,
                'prestador_proprietario' => $escala->prestador_proprietario,
                'prestador_id'           => $escala->prestador_proprietario,
                'servico_id'             => $escala->servico_id,
                'formacao_id'            => $escala->formacao_id,
                'horaentrada'            => $escala->horaentrada,
                'horasaida'              => $escala->horasaida,
                'dataentrada'            => $escala->dataentrada,
                'datasaida'              => $escala->datasaida,
                'periodo'                => $escala->periodo,
                'tipo'                   => $escala->tipo,
                'valorhoradiurno'        => $escala->valorhoradiurno,
                'valorhoranoturno'       => $escala->valorhoranoturno,
            ]);
            foreach ($escala->cuidados as $key => $cuidado) {
                CuidadoEscala::create([
                    'escala_id'  => $e->id,
                    'cuidado_id' => $cuidado['id'],
                    'data'       => null,
                    'hora'       => null,
                    'status'     => false,
                ]);
            }
        }
        return $escalas;
    }
}
