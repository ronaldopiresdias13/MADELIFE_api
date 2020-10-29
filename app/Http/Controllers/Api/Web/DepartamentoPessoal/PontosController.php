<?php

namespace App\Http\Controllers\Api\Web\DepartamentoPessoal;

use App\Escala;
use App\Http\Controllers\Controller;
use App\Ponto;
use Illuminate\Http\Request;

class PontosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pontosPrestadores(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa->id;

        // $entrada   = gmmktime(19, 30, 00, 10, 01, 2020);
        // $saida     = gmmktime(07, 34, 00, 10, 02, 2020);
        // $intervalo = abs($saida - $entrada);
        // $minutos   = round($intervalo / 60, 2);
        // $horas     = round($minutos / 60, 2);

        // return $horas;

        $hoje = getdate();
        $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . $hoje['mday'];
        // $data = '2020-10-01';

        $dados = Escala::with([
            'pontos',
            'servico',
            'prestador.pessoa'
        ])
            ->where('empresa_id', $empresa_id)
            ->where('ordemservico_id', 'like', $request->ordemservico_id ? $request->ordemservico_id : '%')
            ->where('dataentrada', '>=', $request->data_ini ? $request->data_ini : $data)
            ->where('dataentrada', '<=', $request->data_fim ? $request->data_fim : $data)
            ->where('ativo', true)
            ->orderBy("dataentrada")
            ->get([
                "id",
                "empresa_id",
                "ordemservico_id",
                "prestador_id",
                "servico_id",
                "horaentrada",
                "horasaida",
                "dataentrada",
                "datasaida",
                "periodo",
                // "observacao",
                "status",
                // "folga",
                // "substituto",
                // "tipo",
                "valorhoradiurno",
                "valorhoranoturno",
                "valoradicional",
                // "motivoadicional"
            ]);

        // return $dados;

        $escalas = [];

        foreach ($dados as $key => $dado) {
            $escala = [];
            $servico = $dado->servico ? $dado->servico->descricao : '';
            switch ($servico) {
                case 'CUIDADOR':
                case 'TECNICO EM ENFERMAGEM':
                case 'Auxiliar de Enfermagem':
                case 'ENFERMEIRO (A)':
                    $escala = $this->calcularPontos($dado, false);
                    break;
                default:
                    $escala = $this->calcularPontos($dado, true);
                    break;
            }
            if (!array_key_exists($dado->dataentrada, $escalas)) {
                $escalas[$dado->dataentrada] = [];
            }
            array_push($escalas[$dado->dataentrada], $escala);
        }
        return $escalas;
    }

    private function calcularPontos(Escala $dado, bool $mult)
    {
        $escala['escala_id'] = $dado->id;
        $escala['prestador_id'] = $dado->prestador_id;
        $escala['prestador'] = $dado->prestador->pessoa->nome;
        $escala['periodo'] = $dado->periodo;
        $escala['servico_id'] = $dado->servico ? $dado->servico->id : null;
        $escala['servico'] = $dado->servico ? $dado->servico->descricao : null;
        $escala['valorhora'] = (float)($dado->periodo == 'DIURNO' ?
            ($dado->valorhoradiurno ? $dado->valorhoradiurno : 0)
            : ($dado->valorhoranoturno ? $dado->valorhoranoturno : 0));
        foreach ($dado->pontos as $key => $ponto) {
            $dataPonto  = $ponto->data;
            $horaPonto  = $ponto->hora;
            if ($ponto->tipo == "Check-in") {
                $dataEscala = $dado->dataentrada;
                $horaEscala = $dado->horaentrada;
                if ($dataEscala == $dataPonto && $horaEscala == $horaPonto) {
                    $escala['checkin']['data'] = $dado->dataentrada;
                    $escala['checkin']['hora'] = $dado->horaentrada;
                } else {
                    $horaPrevista   = gmmktime((int)substr($horaEscala, 0, 2), (int)substr($horaEscala, 3, 2), 00, (int)substr($dataEscala, 5, 2), (int)substr($dataEscala, 8, 2), (int)substr($dataEscala, 0, 4));
                    $horaRealizada = gmmktime((int)substr($horaPonto, 0, 2), (int)substr($horaPonto, 3, 2), 00, (int)substr($dataPonto, 5, 2), (int)substr($dataPonto, 8, 2), (int)substr($dataPonto, 0, 4));
                    if ($horaRealizada > $horaPrevista + 900 || $horaRealizada < $horaPrevista - 900) {
                        $escala['checkin']['data'] = $ponto->data;
                        $escala['checkin']['hora'] = $ponto->hora;
                    } else {
                        $escala['checkin']['data'] = $dado->dataentrada;
                        $escala['checkin']['hora'] = $dado->horaentrada;
                    }
                }
                $escala['checkin']['alterado'] = $ponto->status;
            } else {
                $dataEscala = $dado->datasaida;
                $horaEscala = $dado->horasaida;
                if ($dataEscala == $dataPonto && $horaEscala == $horaPonto) {
                    $escala['checkout']['data'] = $dado->datasaida;
                    $escala['checkout']['hora'] = $dado->horasaida;
                } else {
                    $horaPrevista   = gmmktime((int)substr($horaEscala, 0, 2), (int)substr($horaEscala, 3, 2), 00, (int)substr($dataEscala, 5, 2), (int)substr($dataEscala, 8, 2), (int)substr($dataEscala, 0, 4));
                    $horaRealizada = gmmktime((int)substr($horaPonto, 0, 2), (int)substr($horaPonto, 3, 2), 00, (int)substr($dataPonto, 5, 2), (int)substr($dataPonto, 8, 2), (int)substr($dataPonto, 0, 4));
                    if ($horaRealizada > $horaPrevista + 900 || $horaRealizada < $horaPrevista - 900) {
                        $escala['checkout']['data'] = $ponto->data;
                        $escala['checkout']['hora'] = $ponto->hora;
                    } else {
                        $escala['checkout']['data'] = $dado->datasaida;
                        $escala['checkout']['hora'] = $dado->horasaida;
                    }
                }
                $escala['checkout']['alterado'] = $ponto->status;
            }
        }
        if (array_key_exists('checkin', $escala) && array_key_exists('checkout', $escala)) {
            $dataEntrada = $escala['checkin']['data'];
            $horaEntrada = $escala['checkin']['hora'];
            $dataSaida = $escala['checkout']['data'];
            $horaSaida = $escala['checkout']['hora'];
            $entrada = gmmktime((int)substr($horaEntrada, 0, 2), (int)substr($horaEntrada, 3, 2), 00, (int)substr($dataEntrada, 5, 2), (int)substr($dataEntrada, 8, 2), (int)substr($dataEntrada, 0, 4));
            $saida   = gmmktime((int)substr($horaSaida, 0, 2), (int)substr($horaSaida, 3, 2), 00, (int)substr($dataSaida, 5, 2), (int)substr($dataSaida, 8, 2), (int)substr($dataSaida, 0, 4));
            $intervalo = abs($saida - $entrada);
            $minutos   = round($intervalo / 60, 2);
            $horas     = round($minutos / 60, 2);
            $escala['totalhoras'] = $horas;
        } else {
            $escala['totalhoras'] = 0;
        }
        $escala['valoradicional'] = (float)$dado->valoradicional;
        $escala['status'] = $dado->status;
        if ($mult) {
            $escala['valortotal'] = $escala['valorhora'] + (float)$dado->valoradicional;
        } else {
            $escala['valortotal'] = ($escala['valorhora'] * $escala['totalhoras']) + (float)$dado->valoradicional;
        }

        return $escala;
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
     * @param  \App\Ponto  $ponto
     * @return \Illuminate\Http\Response
     */
    public function show(Ponto $ponto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ponto  $ponto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ponto $ponto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ponto  $ponto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ponto $ponto)
    {
        //
    }
}
