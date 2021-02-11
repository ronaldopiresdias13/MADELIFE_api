<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutoMedicaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produto_medicaos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medicoes_id');
            $table->foreign('medicoes_id')->references('id')->on('medicoes')->onDelete('cascade');
            $table->unsignedBigInteger('produto_id');
            $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
            $table->string('quantidade')->nullable();
            $table->string('atendido')->nullable();
            $table->string('valor')->nullable();
            $table->string('subtotal')->nullable();
            $table->string('situacao')->nullable();
            $table->string('observacao')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('produto_medicaos');
    }
}
