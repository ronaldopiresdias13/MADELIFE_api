<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnHorascuidadoToOrcamentoServicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orcamento_servico', function (Blueprint $table) {
            $table->float('horascuidado')->default(1)->after('adicionalnoturno');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orcamento_servico', function (Blueprint $table) {
            $table->dropColumn('horascuidado');
        });
    }
}
