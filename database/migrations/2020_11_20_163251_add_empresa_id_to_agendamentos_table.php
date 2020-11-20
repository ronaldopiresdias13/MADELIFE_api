<?php

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
            $table->dropColumn('ativo');
            $table->softDeletes();
        });

        // Schema::table('categoriadocumentos', function (Blueprint $table) {
        //     $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
        //     $table->foreign('empresa_id')->references('id')->on('empresas');
        //     $table->softDeletes();
        // });
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
            $table->dropSoftDeletes();
        });

        // Schema::table('categoriadocumentos', function (Blueprint $table) {
        //     $table->dropForeign(['empresa_id']);
        //     $table->dropColumn('empresa_id');
        //     $table->dropColumn('deleted_at');
        // });
    }
}
