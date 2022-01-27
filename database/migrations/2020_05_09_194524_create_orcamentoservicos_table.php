<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrcamentoservicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orcamentoservicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servico_id')->constrained()->onDelete('cascade');
            $table->integer('quantidade');
            $table->string('basecobranca')->nullable();
            $table->string('frequencia')->nullable();
            $table->float('valorunitario');
            $table->float('subtotal');
            $table->float('custo');
            $table->float('subtotalcusto');
            $table->float('valorresultadomensal');
            $table->float('valorcustomensal');
            $table->float('icms');
            $table->float('iss');
            $table->float('inss');
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
        Schema::dropIfExists('orcamentoservicos');
    }
}
