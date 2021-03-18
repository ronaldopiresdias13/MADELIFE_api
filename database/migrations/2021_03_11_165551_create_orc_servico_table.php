<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrcServicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orc_servico', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('orc_id')->nullable();
            $table->foreign('orc_id')->references('id')->on('orcs');
            $table->unsignedBigInteger('servico_id')->nullable();
            $table->foreign('servico_id')->references('id')->on('servicos');
            $table->integer('quantidade')->nullable();
            $table->string('basecobranca')->nullable();
            $table->string('frequencia')->nullable();
            $table->float('valorunitario')->nullable();
            $table->float('subtotal')->nullable();
            $table->float('custo')->nullable();
            $table->float('custodiurno')->nullable();
            $table->float('custonoturno')->nullable();
            $table->float('subtotalcusto')->nullable();
            $table->float('valorresultadomensal')->nullable();
            $table->float('valorcustomensal')->nullable();
            $table->float('icms')->nullable();
            $table->float('iss')->nullable();
            $table->float('inss')->nullable();
            $table->string('descricao')->nullable();
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
        Schema::dropIfExists('orc_servico');
    }
}
