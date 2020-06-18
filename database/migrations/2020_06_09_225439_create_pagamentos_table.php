<?php

namespace App\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->unsignedBigInteger('conta_id')->nullable();
            $table->foreign('conta_id')->references('id')->on('contas')->onDelete('cascade');
            $table->unsignedBigInteger('contasbancaria_id')->nullable();
            $table->foreign('contasbancaria_id')->references('id')->on('contasbancarias')->onDelete('cascade');
            $table->string('numeroboleto')->nullable();
            $table->string('formapagamento')->nullable();
            $table->string('datavencimento')->nullable();
            $table->string('datapagamento')->nullable();
            $table->float('valorconta')->nullable();
            $table->boolean('status')->nullable();
            $table->string('tipopagamento')->nullable();
            $table->float('valorpago')->nullable();
            $table->boolean('pagamentoparcial')->nullable();
            $table->longText('observacao')->nullable();
            $table->string('numeroconta')->nullable();
            $table->string('anexo')->nullable();
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
        Schema::dropIfExists('pagamentos');
    }
}
