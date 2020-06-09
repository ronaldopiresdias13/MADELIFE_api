<?php

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
            $table->unsignedBigInteger('contabancaria_id')->nullable();
            $table->foreign('contabancaria_id')->references('id')->on('contabancarias')->onDelete('cascade');
            $table->string('numeroboleto')->nullable();
            $table->string('formapagamento')->nullable();
            $table->string('datavencimento')->nullable();
            $table->string('datapagamento');
            $table->string('valorconta')->nullable();
            $table->boolean('status')->nullable();
            $table->string('tipopagamento')->nullable();
            $table->string('valorpago')->nullable();
            $table->boolean('pagamentoparcial');
            $table->longText('observacao')->nullable();
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
