<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagamentointernosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagamentointernos', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->unsignedBigInteger('pessoa_id')->nullable();
            $table->foreign('pessoa_id')->references('id')->on('pessoas');
            $table->string('datainicio')->nullable();
            $table->string('datafim')->nullable();
            $table->float('salario')->nullable();
            $table->float('proventos')->nullable();
            $table->float('descontos')->nullable();
            $table->boolean('status')->default(false);
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
