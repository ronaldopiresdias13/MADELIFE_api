<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoricoOrcamentoCustosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_orcamento_custos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamentocusto')->constrained()->onDelete('cascade');
            $table->foreignId('historicoorcamento')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('historico_orcamento_custos');
    }
}
