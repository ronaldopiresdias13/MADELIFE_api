<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntradaProdutoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrada_produto', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('entrada_id');
            $table->foreign('entrada_id')->references('id')->on('entradas')->onDelete('cascade');
            $table->unsignedBigInteger('produto_id');
            $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
            $table->float('quantidade')->nullable();
            $table->string('lote')->nullable();
            $table->string('validade')->nullable();
            $table->float('valor')->nullable();
            $table->boolean('ativo')->default(true);
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
        Schema::dropIfExists('entrada_produto');
    }
}
