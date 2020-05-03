<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDadoBancariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dado_bancarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banco')->constrained()->onDelete('cascade');
            $table->foreignId('pessoa')->constrained()->onDelete('cascade');
            $table->string('agencia');
            $table->string('conta');
            $table->string('digito');
            $table->string('tipoconta');
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
        Schema::dropIfExists('dado_bancarios');
    }
}
