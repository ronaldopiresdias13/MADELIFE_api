<?php

use App\Models\Escala;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeEspecialidadeToEscalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            $escalas = Escala::with('prestador.formacoes')->get();

            foreach ($escalas as $key => $escala) {
                $escala->formacao_id = $escala->prestador->formacoes[0]->id;
                $escala->save();
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
        DB::transaction(function () {
            $escalas = Escala::with('prestador.formacoes')->get();

            foreach ($escalas as $key => $escala) {
                $escala->formacao_id = null;
                $escala->save();
            }
        });
    }
}
