<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseProfissionaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baseprofissionais', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->boolean('active')->default(true);
            // $table->string('activation_code')->nullable();
            // $table->string('priv_admin')->nullable();
            // $table->string('crypto')->nullable();
            $table->string('cep')->nullable();
            $table->string('endereco')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf')->nullable();
            $table->string('cpf')->nullable();
            $table->string('rg')->nullable();
            $table->string('pis')->nullable();
            $table->string('coren')->nullable();
            $table->string('ccm')->nullable();
            $table->string('cnpj1')->nullable();
            $table->string('tipo_inss')->nullable();
            $table->string('funcao')->nullable();
            $table->string('tel1')->nullable();
            $table->string('tel2')->nullable();
            $table->string('tel3')->nullable();
            $table->string('tel4')->nullable();
            $table->string('tel5')->nullable();
            $table->string('tel6')->nullable();
            $table->string('complexidade')->nullable();
            $table->string('comp_grau')->nullable();
            $table->string('disponib')->nullable();
            $table->string('bloqueio')->nullable();
            $table->string('bloqueio_obs')->nullable();
            $table->string('bloqueio_tomador')->nullable();
            $table->string('regiao')->nullable();
            $table->string('obs')->nullable();
            $table->string('banco')->nullable();
            $table->string('agencia')->nullable();
            $table->string('conta')->nullable();
            $table->string('conta_digito')->nullable();
            $table->string('tipo_conta')->nullable();
            $table->string('conta_terceiro')->nullable();
            $table->string('nome_terceiro')->nullable();
            $table->string('cpf_terceiro')->nullable();
            $table->string('obs1')->nullable();
            $table->string('endereco_terceiro')->nullable();
            $table->string('numero_contrato')->nullable();
            $table->string('dt_inclusao')->nullable();
            $table->string('dt_nascimento')->nullable();
            $table->string('quem_inclui')->nullable();
            $table->string('foto')->nullable();
            $table->string('documentos')->nullable();
            $table->string('nome_documentos')->nullable();
            $table->string('tam_documentos')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('baseprofissionais');
    }
}
