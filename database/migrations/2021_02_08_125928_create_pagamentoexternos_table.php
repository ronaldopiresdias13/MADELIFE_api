<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagamentoexternosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagamentoexternos', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->unsignedBigInteger('pessoa_id')->nullable();
            $table->foreign('pessoa_id')->references('id')->on('pessoas');
            $table->string('datainicio')->nullable();
            $table->string('datafim')->nullable();
            $table->unsignedBigInteger('ordemservico_id')->nullable();
            $table->foreign('ordemservico_id')->references('id')->on('ordemservicos');
            $table->float('quantidade')->nullable();
            $table->string('turno')->nullable();
            $table->float('valorunitario')->nullable();
            $table->float('subtotal')->nullable();
            $table->boolean('status')->default(false);
            $table->string('observacao')->nullable();
            $table->string('situacao')->nullable();
            $table->float('proventos')->nullable();
            $table->float('descontos')->nullable();
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
        Schema::dropIfExists('pagamentoexternos');
    }
}
