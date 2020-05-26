<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemocaoTelefoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remocao_telefone', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('remocao_id');
            $table->foreign('remocao_id')->references('id')->on('remocoes')->onDelete('cascade');
            $table->unsignedBigInteger('telefone_id');
            $table->foreign('telefone_id')->references('id')->on('telefones')->onDelete('cascade');
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
        Schema::dropIfExists('remocao_telefone');
    }
}
