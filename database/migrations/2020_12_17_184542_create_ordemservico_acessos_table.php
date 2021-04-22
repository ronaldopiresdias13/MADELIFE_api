<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOrdemservicoAcessosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordemservico_acessos', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->unsignedBigInteger('ordemservico_id');
            $table->foreign('ordemservico_id')->references('id')->on('ordemservicos');
            $table->unsignedBigInteger('acesso_id');
            $table->foreign('acesso_id')->references('id')->on('acessos');
            $table->boolean('check')->default(false);
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
        Schema::dropIfExists('ordemservico_acessos');
    }
}
