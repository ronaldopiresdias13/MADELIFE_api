<?php

use App\OrdemservicoServico;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ScriptAtribuicoesOrdemservicoServico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            $ordemservico_servico = OrdemservicoServico::all();

            foreach ($ordemservico_servico as $key => $oss) {
                $oss->valornoturno = $oss->valordiurno + $oss->valornoturno;
                $oss->save();
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
        //
    }
}
