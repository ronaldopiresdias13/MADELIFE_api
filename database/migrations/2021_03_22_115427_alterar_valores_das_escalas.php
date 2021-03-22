<?php

use App\Models\Escala;
use App\Models\OrdemservicoServico;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterarValoresDasEscalas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $data = \Carbon\Carbon::parse($suaData)->format('d/m/Y')
        $escalas = Escala::all();
        foreach ($escalas as $key => $escala) {
            $os_s = OrdemservicoServico::where('ordemservico_id', $escala['ordemservico_id'])
                ->where('servico_id', $escala['servico_id'])
                ->first();
            if ($os_s) {
                $escala->valorhoradiurno = $os_s->valordiurno;
                $escala->valorhoranoturno = $os_s->valornoturno;
                $escala->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
