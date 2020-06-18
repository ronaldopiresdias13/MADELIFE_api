<?php

namespace App\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmpresaIdToAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('id')->default(1);
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
        Schema::table('emails', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('id')->default(1);
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
        Schema::table('acessos', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('id')->default(1);
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
        Schema::table('telefones', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('id')->default(1);
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
        Schema::table('pessoas', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('id')->default(1);
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
        Schema::table('bancos', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('id')->default(1);
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
        Schema::table('dadosbancarios', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('id')->default(1);
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
        Schema::table('profissionais', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('id')->default(1);
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
        Schema::table('pacientes', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('id')->default(1);
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
        Schema::table('pils', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('id')->default(1);
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
        Schema::table('transcricoes', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('id')->default(1);
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
        Schema::table('diagnosticosecundarios', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('id')->default(1);
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
        Schema::table('prescricoesb', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('id')->default(1);
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
        Schema::table('responsaveis', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('id')->default(1);
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
        Schema::table('naturezas', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('id')->default(1);
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_empresa_id_foreign');
            $table->dropColumn('empresa_id');
        });
        Schema::table('emails', function (Blueprint $table) {
            $table->dropForeign('emails_empresa_id_foreign');
            $table->dropColumn('empresa_id');
        });
        Schema::table('acessos', function (Blueprint $table) {
            $table->dropForeign('acessos_empresa_id_foreign');
            $table->dropColumn('empresa_id');
        });
        Schema::table('telefones', function (Blueprint $table) {
            $table->dropForeign('telefones_empresa_id_foreign');
            $table->dropColumn('empresa_id');
        });
        Schema::table('pessoas', function (Blueprint $table) {
            $table->dropForeign('pessoas_empresa_id_foreign');
            $table->dropColumn('empresa_id');
        });
        Schema::table('bancos', function (Blueprint $table) {
            $table->dropForeign('bancos_empresa_id_foreign');
            $table->dropColumn('empresa_id');
        });
        Schema::table('dadosbancarios', function (Blueprint $table) {
            $table->dropForeign('dadosbancarios_empresa_id_foreign');
            $table->dropColumn('empresa_id');
        });
        Schema::table('profissionais', function (Blueprint $table) {
            $table->dropForeign('profissionais_empresa_id_foreign');
            $table->dropColumn('empresa_id');
        });
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropForeign('pacientes_empresa_id_foreign');
            $table->dropColumn('empresa_id');
        });
        Schema::table('pils', function (Blueprint $table) {
            $table->dropForeign('pils_empresa_id_foreign');
            $table->dropColumn('empresa_id');
        });
        Schema::table('transcricoes', function (Blueprint $table) {
            $table->dropForeign('transcricoes_empresa_id_foreign');
            $table->dropColumn('empresa_id');
        });
        Schema::table('diagnosticosecundarios', function (Blueprint $table) {
            $table->dropForeign('diagnosticosecundarios_empresa_id_foreign');
            $table->dropColumn('empresa_id');
        });
        Schema::table('prescricoesb', function (Blueprint $table) {
            $table->dropForeign('prescricoesb_empresa_id_foreign');
            $table->dropColumn('empresa_id');
        });
        Schema::table('responsaveis', function (Blueprint $table) {
            $table->dropForeign('responsaveis_empresa_id_foreign');
            $table->dropColumn('empresa_id');
        });
        Schema::table('naturezas', function (Blueprint $table) {
            $table->dropForeign('naturezas_empresa_id_foreign');
            $table->dropColumn('empresa_id');
        });
    }
}
