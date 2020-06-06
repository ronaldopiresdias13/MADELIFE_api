<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranscricaoProdutoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transcricao_produto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transcricao_id');
            $table->foreign('transcricao_id')->references('id')->on('transcricoes')->onDelete('cascade');
            $table->unsignedBigInteger('produto_id');
            $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
            $table->string('quantidade')->nullable();
            $table->string('apresentacao')->nullable();
            $table->string('via')->nullable();
            $table->string('frequencia')->nullable();            
            $table->string('tempo')->nullable();                        
            $table->boolean('status')->nullable();                        
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
        Schema::dropIfExists('transcricao_produto');
    }
}
