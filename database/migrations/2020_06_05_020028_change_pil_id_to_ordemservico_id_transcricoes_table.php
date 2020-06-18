<?php

namespace App\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePilIdToOrdemservicoIdTranscricoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transcricoes', function (Blueprint $table) {
            $table->unsignedBigInteger('ordemservico_id')->after('profissional_id')->nullable();
            $table->foreign('ordemservico_id')->references('id')->on('ordemservicos')->onDelete('cascade');
            $table->dropForeign('transcricoes_pil_id_foreign');
            $table->dropColumn('pil_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transcricoes', function (Blueprint $table) {
            $table->unsignedBigInteger('pil_id')->after('profissional_id')->nullable();
            $table->foreign('pil_id')->references('id')->on('pils')->onDelete('cascade');
            $table->dropForeign('transcricoes_ordemservico_id_foreign');
            $table->dropColumn('ordemservico_id');
        });
    }
}
