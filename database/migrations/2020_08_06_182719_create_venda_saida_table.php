<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendaSaidaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venda_saida', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('venda_id');
            $table->foreign('venda_id')->references('id')->on('vendas')->onDelete('cascade');
            $table->unsignedBigInteger('saida_id');
            $table->foreign('saida_id')->references('id')->on('saidas')->onDelete('cascade');
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
        Schema::dropIfExists('venda_saida');
    }
}
