<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CnabPessoa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cnabpessoas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cnab_id');
            $table->foreign('cnab_id')->references('id')->on('registrocnabs')->onDelete('cascade');

            $table->unsignedBigInteger('pessoa_id');
            $table->foreign('pessoa_id')->references('id')->on('pessoas')->onDelete('cascade');
            $table->double('valor',2);
            $table->string('agencia');
            $table->string('conta');
            $table->string('digito');
            $table->string('banco');
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
        Schema::dropIfExists('cnabpessoas');
    }
}
