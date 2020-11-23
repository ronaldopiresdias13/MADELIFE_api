<?php

use App\Agendamento;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmpresaIdToAgendamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropColumn('uuid');
            $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->softDeletes();
        });

        $agendamentos = Agendamento::all();

        foreach ($agendamentos as $key => $agendamento) {
            $agendamento->empresa_id = $agendamento->sala->empresa_id;
            $agendamento->save();
            if (!$agendamento->ativo) {
                $agendamento->delete();
            }
        }

        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique()->nullable();
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
            $table->boolean('ativo')->default(true)->after('horafim');
        });

        $agendamentos = Agendamento::all();

        foreach ($agendamentos as $key => $agendamento) {
            if ($agendamento->deleted_at != null) {
                $agendamento->ativo = false;
                $agendamento->save();
            }
        }

        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
