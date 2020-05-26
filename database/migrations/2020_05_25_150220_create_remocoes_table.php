<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemocoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remocoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orcamento_id');
            $table->foreign('orcamento_id')->references('id')->on('orcamentos')->onDelete('cascade');
            $table->string('nome')->nullable();
            $table->string('sexo')->nullable();
            $table->string('nascimento')->nullable();
            $table->string('cpfcnpj')->nullable();
            $table->string('rgie')->nullable();
            $table->string('enderecoorigem')->nullable();
            $table->unsignedBigInteger('cidadeorigem');
            $table->foreign('cidadeorigem')->references('id')->on('cidades')->onDelete('cascade');
            $table->string('enderecodestino')->nullable();
            $table->unsignedBigInteger('cidadedestino');
            $table->foreign('cidadedestino')->references('id')->on('cidades')->onDelete('cascade');
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
        Schema::dropIfExists('remocoes');
    }
}
