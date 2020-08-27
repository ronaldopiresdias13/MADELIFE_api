<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPacienteIdToHomecaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homecares', function (Blueprint $table) {
            $table->unsignedBigInteger('paciente_id')->nullable()->after('orcamento_id');
            $table->foreign('paciente_id')->references('id')->on('pacientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homecares', function (Blueprint $table) {
            $table->dropForeign(['paciente_id']);
            $table->dropColumn('paciente_id');
        });
    }
}
