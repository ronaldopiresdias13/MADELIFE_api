<?php

use App\Models\OrcamentoServico;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOrcamentoServicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orcamento_servico', function (Blueprint $table) {
            $table->float('custonoturno')->default(0)->after('custo');
            $table->float('custodiurno')->default(0)->after('custo');
            $table->float('horascuidadonoturno')->default(0)->after('horascuidado');
            $table->float('horascuidadodiurno')->default(0)->after('horascuidado');
        });

        $servicos = OrcamentoServico::all();

        foreach ($servicos as $key => $servico) {
            if ($servico->basecobranca == 'Plantão') {
                $servico->custodiurno = $servico->custo / 2;
                $servico->custonoturno = $servico->custo / 2 + $servico->adicionalnoturno;
                $servico->horascuidadodiurno = $servico->horascuidado / 2;
                $servico->horascuidadonoturno = $servico->horascuidado / 2;
            } else {
                $servico->custodiurno = $servico->custo;
                $servico->horascuidadodiurno = $servico->horascuidado;
            }
            $servico->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orcamento_servico', function (Blueprint $table) {
            $table->dropColumn('custodiurno');
            $table->dropColumn('custonoturno');
            $table->dropColumn('horascuidadodiurno');
            $table->dropColumn('horascuidadonoturno');
        });
    }
}
