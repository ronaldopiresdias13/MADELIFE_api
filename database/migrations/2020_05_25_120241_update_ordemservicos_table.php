<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrdemservicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordemservicos', function (Blueprint $table) {
            $table->dropColumn('tipo');
            $table->dropColumn('nome');
            $table->dropColumn('sexo');
            $table->dropColumn('nascimento');
            $table->dropColumn('cpfcnpj');
            $table->dropColumn('rgie');
            $table->dropColumn('endereco1');
            $table->dropColumn('cidade1');
            $table->dropColumn('cep1');
            $table->dropColumn('endereco2');
            $table->dropColumn('cidade2');
            $table->dropColumn('cep2');
            $table->dropColumn('contato');
            $table->dropColumn('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordemservicos', function (Blueprint $table) {
            $table->string('email')->after('orcamento_id')->nullable();
            $table->string('contato')->after('orcamento_id')->nullable();
            $table->string('cep2')->after('orcamento_id')->nullable();
            $table->string('cidade2')->after('orcamento_id')->nullable();
            $table->string('endereco2')->after('orcamento_id')->nullable();
            $table->string('cep1')->after('orcamento_id')->nullable();
            $table->string('cidade1')->after('orcamento_id')->nullable();
            $table->string('endereco1')->after('orcamento_id')->nullable();
            $table->string('rgie')->after('orcamento_id')->nullable();
            $table->string('cpfcnpj')->after('orcamento_id')->nullable();
            $table->string('nascimento')->after('orcamento_id')->nullable();
            $table->string('sexo')->after('orcamento_id')->nullable();
            $table->string('nome')->after('orcamento_id')->nullable();
            $table->string('tipo')->after('orcamento_id');
        });
    }
}
