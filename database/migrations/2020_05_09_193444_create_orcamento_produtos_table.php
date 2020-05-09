<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrcamentoProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orcamento_produtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto')->constrained()->onDelete('cascade');
            $table->int('quantidade');
            $table->float('valorunitario');
            $table->float('subtotal');
            $table->float('custo');
            $table->float('subtotalcusto');
            $table->float('valorresultadomensal');
            $table->float('valorcustomensal');
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
        Schema::dropIfExists('orcamento_produtos');
    }
}
