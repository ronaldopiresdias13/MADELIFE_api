<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAtivoToCuidadoPacienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cuidado_paciente', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('formacao_id');
            $table->index('ativo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cuidado_paciente', function (Blueprint $table) {
            $table->dropIndex(['ativo']);
            $table->dropColumn('ativo');
        });
    }
}
