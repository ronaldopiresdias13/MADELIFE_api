<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChageIdToUuidToFormacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* DROP FOREIGNS */
        // Schema::table('cuidado_paciente', function (Blueprint $table) {
        //     $table->dropForeign(['formacao_id']);
        // });
        // Schema::table('escalas', function (Blueprint $table) {
        //     $table->dropForeign(['formacao_id']);
        // });
        // Schema::table('prestador_formacao', function (Blueprint $table) {
        //     $table->dropForeign(['formacao_id']);
        // });
        // Schema::table('profissional_formacao', function (Blueprint $table) {
        //     $table->dropForeign(['formacao_id']);
        // });
        // Schema::table('servico_formacao', function (Blueprint $table) {
        //     $table->dropForeign(['formacao_id']);
        // });

        /* CHANGE FOREIGN TO UUID */
        // Schema::table('cuidado_paciente', function (Blueprint $table) {
        //     $table->uuid('formacao_id')->change();
        // });
        Schema::table('escalas', function (Blueprint $table) {
            $table->uuid('formacao_id')->nullable()->change();
        });
        Schema::table('prestador_formacao', function (Blueprint $table) {
            $table->uuid('formacao_id')->change();
        });
        Schema::table('profissional_formacao', function (Blueprint $table) {
            $table->uuid('formacao_id')->change();
        });
        Schema::table('servico_formacao', function (Blueprint $table) {
            $table->uuid('formacao_id')->change();
        });

        /* CHANGE ID TO UUID */
        Schema::table('formacoes', function (Blueprint $table) {
            $table->uuid('id')->change();
        });

        /* RECREATE FOREIGNS */
        Schema::table('cuidado_paciente', function (Blueprint $table) {
            $table->foreign('formacao_id')->references('id')->on('formacoes');
        });
        Schema::table('escalas', function (Blueprint $table) {
            $table->foreign('formacao_id')->references('id')->on('formacoes');
        });
        Schema::table('prestador_formacao', function (Blueprint $table) {
            $table->foreign('formacao_id')->references('id')->on('formacoes');
        });
        Schema::table('profissional_formacao', function (Blueprint $table) {
            $table->foreign('formacao_id')->references('id')->on('formacoes');
        });
        Schema::table('servico_formacao', function (Blueprint $table) {
            $table->foreign('formacao_id')->references('id')->on('formacoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /* DROP FOREIGNS */
        Schema::table('cuidado_paciente', function (Blueprint $table) {
            $table->dropForeign(['formacao_id']);
        });
        Schema::table('escalas', function (Blueprint $table) {
            $table->dropForeign(['formacao_id']);
        });
        Schema::table('prestador_formacao', function (Blueprint $table) {
            $table->dropForeign(['formacao_id']);
        });
        Schema::table('profissional_formacao', function (Blueprint $table) {
            $table->dropForeign(['formacao_id']);
        });
        Schema::table('servico_formacao', function (Blueprint $table) {
            $table->dropForeign(['formacao_id']);
        });

        /* CHANGE FOREIGN TO BIGINCREMENTS */
        Schema::table('cuidado_paciente', function (Blueprint $table) {
            $table->unsignedBigInteger('formacao_id')->change();
        });
        Schema::table('escalas', function (Blueprint $table) {
            $table->unsignedBigInteger('formacao_id')->change();
        });
        Schema::table('prestador_formacao', function (Blueprint $table) {
            $table->unsignedBigInteger('formacao_id')->change();
        });
        Schema::table('profissional_formacao', function (Blueprint $table) {
            $table->unsignedBigInteger('formacao_id')->change();
        });
        Schema::table('servico_formacao', function (Blueprint $table) {
            $table->unsignedBigInteger('formacao_id')->change();
        });

        /* CHANGE ID TO UUID */
        Schema::table('formacoes', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });

        /* RECREATE FOREIGNS */
        Schema::table('cuidado_paciente', function (Blueprint $table) {
            $table->foreign('formacao_id')->references('id')->on('formacoes');
        });
        Schema::table('escalas', function (Blueprint $table) {
            $table->foreign('formacao_id')->references('id')->on('formacoes');
        });
        Schema::table('prestador_formacao', function (Blueprint $table) {
            $table->foreign('formacao_id')->references('id')->on('formacoes');
        });
        Schema::table('profissional_formacao', function (Blueprint $table) {
            $table->foreign('formacao_id')->references('id')->on('formacoes');
        });
        Schema::table('servico_formacao', function (Blueprint $table) {
            $table->foreign('formacao_id')->references('id')->on('formacoes');
        });
    }
}
