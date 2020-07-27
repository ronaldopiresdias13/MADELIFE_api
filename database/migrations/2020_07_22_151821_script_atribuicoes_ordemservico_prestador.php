<?php

use App\OrdemservicoPrestador;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ScriptAtribuicoesOrdemservicoPrestador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            $ordemservico_prestador = OrdemservicoPrestador::all();

            foreach ($ordemservico_prestador as $key => $osp) {
                $osp->valornoturno = $osp->valordiurno + $osp->valornoturno;
                $osp->save();
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
