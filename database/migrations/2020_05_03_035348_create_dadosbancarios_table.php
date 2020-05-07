<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDadosBancariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dadosbancarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banco')->constrained()->onDelete('cascade');
            $table->foreignId('pessoa')->constrained()->onDelete('cascade');
            $table->string('agencia')->nullable();
            $table->string('conta')->nullable();
            $table->string('digito')->nullable();
            $table->string('tipoconta')->nullable();
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
        Schema::dropIfExists('dadosbancarios');
    }
}
