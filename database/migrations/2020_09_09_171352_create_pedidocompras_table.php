<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidocomprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidocompras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->unsignedBigInteger('pessoa_id')->nullable();
            $table->foreign('pessoa_id')->references('id')->on('pessoas')->onDelete('cascade');
            $table->string('data')->nullable();
            $table->string('observacao')->nullable();
            $table->string('numeropedido')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('pedidocompras');
    }
}
