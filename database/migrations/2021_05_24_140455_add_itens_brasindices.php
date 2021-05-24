<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItensBrasindices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itens_brasindices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brasindice_id')->nullable();
            $table->foreign('brasindice_id')->references('id')->on('brasindices');
            $table->string('tipo_produto')->nullable();
            $table->string('cod_laboratorio')->nullable();
            $table->string('nome_laboratorio')->nullable();
            $table->string('cod_produto')->nullable();
            $table->string('nome_produto')->nullable();
            $table->string('cod_apresentacao')->nullable();
            $table->string('nome_apresentacao')->nullable();
            $table->float('preco_produto')->nullable();
            $table->float('quant_fracionado')->nullable();
            $table->string('tipo_preco')->nullable();
            $table->float('valor_fracionado_prod')->nullable();
            $table->string('ultima_edicao')->nullable();
            $table->float('ipi_produto')->nullable();
            $table->boolean('flag_portaria')->nullable()->default(false);
            $table->string('cod_ean')->nullable();
            $table->string('cod_tiss')->nullable();
            $table->boolean('generico')->nullable()->default(false);
            $table->string('cod_tuss')->nullable();
            $table->boolean('status')->nullable()->default(false);
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
        Schema::dropIfExists('itensbrasindices');
    }
}
