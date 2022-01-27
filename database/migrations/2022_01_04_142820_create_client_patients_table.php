<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPatientsTable extends Migration
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
        Schema::create('clients_patients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome');
            $table->string('sexo')->nullable();
            $table->string('cpf')->nullable();
            $table->string('rg')->nullable();
            $table->string('rua')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('nome_responsavel')->nullable();
            $table->string('parentesco_responsavel')->nullable();
            $table->string('cpf_responsavel')->nullable();
            $table->string('telefone_responsavel')->nullable();

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
        Schema::dropIfExists('clients_patients');
    }
}
