<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->string('tipopessoa')->nullable();
            $table->unsignedBigInteger('pessoa_id')->nullable();
            $table->foreign('pessoa_id')->references('id')->on('pessoas')->onDelete('cascade');
            $table->unsignedBigInteger('natureza_id')->nullable();
            $table->foreign('natureza_id')->references('id')->on('naturezas')->onDelete('cascade');
            $table->string('valortotalconta')->nullable();
            $table->string('tipoconta')->nullable();
            $table->longText('historico')->nullable();
            $table->boolean('status');
            $table->string('nfe')->nullable();
            $table->string('quantidadeconta')->nullable();
            $table->string('valorpago')->nullable();
            $table->string('tipocontapagamento')->nullable();
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
        Schema::dropIfExists('contas');
    }
}
