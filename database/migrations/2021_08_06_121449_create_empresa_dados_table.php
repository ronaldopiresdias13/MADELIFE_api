<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresaDadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas_dados', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('agencia');
            $table->string('digito_agencia');
            $table->string('conta');
            $table->string('digito_conta');
            $table->string('convenio')->nullable();
            $table->string('convenio_externo')->nullable();
            $table->string('nome');
            $table->string('nome_empresa');
            $table->string('cnpj');
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas');
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
        Schema::dropIfExists('empresas_dados');
    }
}
