<?php

use App\Models\Custo;
use App\Models\Orcamento;
use App\Models\Orcamentocusto;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrationOrcamentocustosToCustosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            $orcamentocustos = Orcamentocusto::all();
            foreach ($orcamentocustos as $key => $orcamentocusto) {
                $empresa_id = Orcamento::find($orcamentocusto->orcamento_id)->empresa_id;
                $custo = Custo::firstOrCreate(
                    [
                        'empresa_id' => $empresa_id,
                        'descricao' => $orcamentocusto->descricao
                    ],
                    [
                        'codigo' => Custo::where('empresa_id', $empresa_id)->max('codigo') + 1
                    ]
                );
                $orcamentocusto->custo_id = $custo->id;
                $orcamentocusto->save();
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
            $orcamentocustos = Orcamentocusto::with('custo')->get();
            foreach ($orcamentocustos as $key => $orcamentocusto) {
                $orcamentocusto->descricao = $orcamentocusto->custo->descricao;
                $orcamentocusto->save();
            }
        });
    }
}
