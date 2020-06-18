<?php

namespace App\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoricoorcamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historicoorcamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamento_id')->constrained()->onDelete('cascade');
            $table->string('data')->nullable();
            $table->float('valortotalservico')->nullable();
            $table->float('valortotalproduto')->nullable();
            $table->float('valortotalcusto')->nullable();
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
        Schema::dropIfExists('historicoorcamentos');
    }
}
