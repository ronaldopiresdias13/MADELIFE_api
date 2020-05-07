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
            $table->foreignId('pessoa')->constrained()->onDelete('cascade');
            $table->boolean('pessoafisica');
            $table->string('sexo');
            $table->unsignedBigInteger('setor');
            $table->foreign('setor')->references('id')->on('setores')->onDelete('cascade');
            $table->foreignId('cargo')->constrained()->onDelete('cascade');
            $table->string('pis');
            $table->string('numerocarteiratrabalho');
            $table->string('numerocnh');
            $table->string('categoriacnh');
            $table->string('validadecnh');
            $table->string('numerotituloeleitor');
            $table->string('zonatituloeleitor');
            $table->string('secaotituloeleitor');
            $table->unsignedBigInteger('dadoscontratuais');
            $table->foreign('dadoscontratuais')->references('id')->on('dadoscontratuais')->onDelete('cascade');
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
