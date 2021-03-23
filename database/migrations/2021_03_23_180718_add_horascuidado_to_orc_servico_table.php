<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHorascuidadoToOrcServicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orc_servico', function (Blueprint $table) {
            $table->float('horascuidadonoturno')->nullable()->after('valorcustomensal');
            $table->float('horascuidadodiurno')->nullable()->after('valorcustomensal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orc_servico', function (Blueprint $table) {
            $table->dropColumn(['horascuidadodiurno','horascuidadonoturno']);
        });
    }
}
