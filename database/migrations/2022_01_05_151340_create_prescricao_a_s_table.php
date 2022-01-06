<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescricaoASTable extends Migration
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
        Schema::create('prescricoes_a', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('nome');
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
        Schema::dropIfExists('prescricoes_a');
    }
}
