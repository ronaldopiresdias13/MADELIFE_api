<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdemservicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordemservicos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable();
            $table->string('tipo');
            $table->unsignedBigInteger('orcamento_id');
            $table->foreign('orcamento_id')->references('id')->on('orcamentos')->onDelete('cascade');
            $table->string('inicio')->nullable();
            $table->string('fim')->nullable();
            $table->boolean('status');
            $table->boolean('montagemequipe');
            $table->boolean('realizacaoprocedimento');
            $table->string('nome')->nullable();
            $table->string('sexo')->nullable();
            $table->string('nascimento')->nullable();
            $table->string('cpfcnpj')->nullable();
            $table->string('rgie')->nullable();
            $table->string('endereco1')->nullable();
            $table->string('cidade1')->nullable();
            $table->string('cep1')->nullable();
            $table->string('endereco2')->nullable();
            $table->string('cidade2')->nullable();
            $table->string('cep2')->nullable();
            $table->string('contato')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('profissional_id')->nullable();
            $table->foreign('profissional_id')->references('id')->on('profissionais')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordemservicos');
    }
}
