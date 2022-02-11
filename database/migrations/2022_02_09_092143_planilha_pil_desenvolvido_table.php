<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlanilhaPilDesenvolvidoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planilhas_pils', function (Blueprint $table) {
            $table->text('desenvolvido_por')->nullable();
            $table->date('desenvolvido_por_data')->nullable();

            $table->text('atualizado_por')->nullable();
            $table->date('atualizado_por_data')->nullable();

            $table->text('aprovado_por')->nullable();
            $table->date('aprovado_por_data')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('planilhas_pils');
    }
}
