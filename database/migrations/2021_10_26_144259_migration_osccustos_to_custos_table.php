<?php

use App\Models\Custo;
use App\Models\Orc;
use App\Models\Orccusto;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrationOsccustosToCustosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            $orccustos = Orccusto::withTrashed()->get();
            foreach ($orccustos as $key => $orccusto) {
                $empresa_id = Orc::withTrashed()->find($orccusto->orc_id)->empresa_id;
                $custo = Custo::firstOrCreate(
                    [
                        'empresa_id' => $empresa_id,
                        'descricao' => $orccusto->descricao
                    ],
                    [
                        'codigo' => Custo::where('empresa_id', $empresa_id)->max('codigo') + 1
                    ]
                );
                $orccusto->custo_id = $custo->id;
                $orccusto->save();
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
            $orccustos = Orccusto::with('custo')->withTrashed()->get();
            foreach ($orccustos as $key => $orccusto) {
                $orccusto->descricao = $orccusto->custo->descricao;
                $orccusto->save();
            }
        });
    }
}
