<?php

namespace App\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropHistoricosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('historico_orcamento_custo');
        Schema::dropIfExists('historico_orcamento_produto');
        Schema::dropIfExists('historico_orcamento_servico');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('historico_orcamento_produto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamentoproduto_id')->constrained()->onDelete('cascade');
            $table->foreignId('historicoorcamento_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('historico_orcamento_servico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamentoservico_id')->constrained()->onDelete('cascade');
            $table->foreignId('historicoorcamento_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('historico_orcamento_custo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamentocusto_id')->constrained()->onDelete('cascade');
            $table->foreignId('historicoorcamento_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
}
