<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orcs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->string('numero')->nullable();
            $table->string('tipo')->nullable();
            $table->string('data')->nullable();
            $table->integer('quantidade')->nullable();
            $table->string('unidade')->nullable();
            $table->unsignedBigInteger('cidade_id')->nullable();
            $table->foreign('cidade_id')->references('id')->on('cidades');
            $table->string('processo')->nullable();
            $table->string('situacao')->nullable();
            $table->string('descricao')->nullable();
            $table->float('valortotalproduto')->nullable();
            $table->float('valortotalcusto')->nullable();
            $table->float('valortotalservico')->nullable();
            $table->text('observacao')->nullable();
            $table->boolean('status')->nullable()->default(false);

            $table->boolean('venda_realizada')->nullable()->default(false);
            $table->string('venda_data')->nullable();

            $table->unsignedBigInteger('homecare_paciente_id')->nullable();
            $table->foreign('homecare_paciente_id')->references('id')->on('pacientes');

            $table->text('aph_descricao')->nullable();
            $table->string('aph_endereco', 500)->nullable();
            $table->string('aph_cep')->nullable();
            $table->unsignedBigInteger('aph_cidade_id')->nullable();
            $table->foreign('aph_cidade_id')->references('id')->on('cidades');

            $table->string('evento_nome')->nullable();
            $table->string('evento_endereco', 500)->nullable();
            $table->string('evento_cep')->nullable();
            $table->unsignedBigInteger('evento_cidade_id')->nullable();
            $table->foreign('evento_cidade_id')->references('id')->on('cidades');

            $table->string('remocao_nome')->nullable();
            $table->string('remocao_sexo')->nullable();
            $table->string('remocao_nascimento')->nullable();
            $table->string('remocao_cpfcnpj')->nullable();
            $table->string('remocao_rgie')->nullable();
            $table->string('remocao_enderecoorigem', 500)->nullable();
            $table->unsignedBigInteger('remocao_cidadeorigem_id')->nullable();
            $table->foreign('remocao_cidadeorigem_id')->references('id')->on('cidades');
            $table->string('remocao_enderecodestino', 500)->nullable();
            $table->unsignedBigInteger('remocao_cidadedestino_id')->nullable();
            $table->foreign('remocao_cidadedestino_id')->references('id')->on('cidades');
            $table->string('remocao_observacao')->nullable();

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
        Schema::dropIfExists('orcs');
    }
}
