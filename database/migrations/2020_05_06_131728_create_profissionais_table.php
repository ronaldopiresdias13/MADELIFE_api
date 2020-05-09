<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfissionaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profissionais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->constrained()->onDelete('cascade');
            $table->boolean('pessoafisica');
            $table->string('sexo')->nullable();
            $table->unsignedBigInteger('setor_id')->nullable();
            $table->foreign('setor_id')->references('id')->on('setores')->onDelete('cascade');
            $table->foreignId('cargo_id')->constrained()->onDelete('cascade');
            $table->string('pis')->nullable();
            $table->string('numerocarteiratrabalho')->nullable();
            $table->string('numerocnh')->nullable();
            $table->string('categoriacnh')->nullable();
            $table->string('validadecnh')->nullable();
            $table->string('numerotituloeleitor')->nullable();
            $table->string('zonatituloeleitor')->nullable();
            $table->string('secaotituloeleitor')->nullable();
            $table->unsignedBigInteger('dadoscontratuais_id')->nullable();
            $table->foreign('dadoscontratuais_id')->references('id')->on('dadoscontratuais')->onDelete('cascade');
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
        Schema::dropIfExists('profissionais');
    }
}
