<?php

use App\Escala;
use App\OrdemservicoServico;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefatoracaoValoresToEscalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('escalas', function (Blueprint $table) {
            $escalas = Escala::all();
            foreach ($escalas as $key => $escala) {
                $ordemservicoServico = OrdemservicoServico::where('ordemservico_id', $escala->ordemservico_id)->where('servico_id', $escala->servico_id)->first();
                if ($ordemservicoServico) {
                    $escala->tipo             = $ordemservicoServico->descricao;
                    $escala->valorhoradiurno  = $ordemservicoServico->valordiurno;
                    $escala->valorhoranoturno = $ordemservicoServico->valornoturno;
                    $escala->valoradicional   = 0;
                    $escala->save();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('escalas', function (Blueprint $table) {
            $escalas = Escala::all();
            foreach ($escalas as $key => $escala) {
                $escala->tipo             = null;
                $escala->valorhoradiurno  = null;
                $escala->valorhoranoturno = null;
                $escala->valoradicional   = null;
                $escala->save();
            }
        });
    }
}
