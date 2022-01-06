<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PrescricaoEmpresaId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 'nome', 'sexo','cpf','rg','rua','numero','complemento','bairro','cidade', 'estado','latitude','longitude','nome_responsavel',
        // 'parentesco_responsavel','cpf_responsavel','telefone_responsavel'
        Schema::table('prescricoes_a', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prescricoes_a');
    }
}
