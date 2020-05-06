<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrestadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa')->constrained()->onDelete('cascade');
            $table->string('fantasia')->nullable();
            $table->string('sexo')->nullable();
            $table->string('pis')->nullable();
            $table->foreignId('cargo')->constrained()->onDelete('cascade');
            $table->string('conselho')->nullable();
            $table->string('curriculo')->nullable();
            $table->string('certificado')->nullable();
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
        Schema::dropIfExists('prestadores');
    }
}
