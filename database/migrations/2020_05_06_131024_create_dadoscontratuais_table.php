<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDadoscontratuaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dadoscontratuais', function (Blueprint $table) {
            $table->id();
            $table->string('tiposalario')->nullable();
            $table->float('salario')->nullable();
            $table->float('cargahoraria')->nullable();
            $table->boolean('insalubridade')->default(false);
            $table->float('percentualinsalubridade')->nullable();
            $table->string('admissao')->nullable();
            $table->string('demissao')->nullable();
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
        Schema::dropIfExists('dadoscontratuais');
    }
}
