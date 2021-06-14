<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidocompraProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidocompra_produto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedidocompra_id')->nullable();
            $table->foreign('pedidocompra_id')->references('id')->on('pedidocompras')->onDelete('cascade');
            $table->unsignedBigInteger('produto_id')->nullable();
            $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
            $table->string('quantidade')->nullable();
            $table->string('observacao')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('pedidocompra_produto');
    }
}
