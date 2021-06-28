<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsTissToProdutoMedicaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('produto_medicao', function (Blueprint $table) {
            $table->string('horaFinal')->nullable()->after('produto_id');
            $table->string('horaInicial')->nullable()->after('produto_id');
            $table->string('dataExecucao')->nullable()->after('produto_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produto_medicao', function (Blueprint $table) {
            $table->dropColumn('dataExecucao');
            $table->dropColumn('horaInicial');
            $table->dropColumn('horaFinal');
        });
    }
}
