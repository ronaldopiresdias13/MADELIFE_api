<?php

namespace App\Services;

// use App\Models\CnabPessoa;
// use App\Models\Pagamentopessoa;
// use App\Models\RegistroCnab;
// use App\Models\User;
// use Carbon\Carbon;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Str;
// use Illuminate\Validation\ValidationException;

class PontoService
{
    protected $dataEntrada = null;
    protected $dataSaida = null;
    protected $horaEntrada = null;
    protected $horaSaida = null;

    public function __construct($dataEntrada, $dataSaida, $horaEntrada, $horaSaida)
    {
        $this->dataEntrada = $dataEntrada;
        $this->dataSaida = $dataSaida;
        $this->horaEntrada = $horaEntrada;
        $this->horaSaida = $horaSaida;
    }

    public function calcular_horas()
    {
        $entrada = gmmktime((int)substr($this->horaEntrada, 0, 2), (int)substr($this->horaEntrada, 3, 2), 00, (int)substr($this->dataEntrada, 5, 2), (int)substr($this->dataEntrada, 8, 2), (int)substr($this->dataEntrada, 0, 4));
        $saida   = gmmktime((int)substr($this->horaSaida, 0, 2), (int)substr($this->horaSaida, 3, 2), 00, (int)substr($this->dataSaida, 5, 2), (int)substr($this->dataSaida, 8, 2), (int)substr($this->dataSaida, 0, 4));
        $intervalo = abs($saida - $entrada);
        $minutos   = round($intervalo / 60, 2);
        $horas     = round($minutos / 60, 2);
        return $horas;
    }
}
