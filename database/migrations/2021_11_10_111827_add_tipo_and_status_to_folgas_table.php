<?php

use App\Models\Escala;
use App\Models\Folga;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoAndStatusToFolgasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('folgas', function (Blueprint $table) {
            $table->string('tipo')->nullable()->after('dataaprovacao');
            $table->string('situacao')->nullable()->after('tipo');
        });
        $folgas = Folga::all();
        $hoje = getdate();
        $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . ($hoje['mday'] < 10 ? '0' . $hoje['mday'] : $hoje['mday']);
        foreach ($folgas as $key => $folga) {
            $escala = Escala::find($folga->escala_id);
            $folga->tipo     = 'Folga';
            $folga->situacao = ($folga->substituto ? 'Substituído' : ($escala->dataentrada == $data ? 'Emergente' : 'Pendente'));
            $folga->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('folgas', function (Blueprint $table) {
            $table->dropColumn('tipo');
            $table->dropColumn('situacao');
        });
    }
}
