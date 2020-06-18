<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrcamentoIdToOrcamentocustosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orcamentocustos', function (Blueprint $table) {
            $table->unsignedBigInteger('orcamento_id')->after('id');
            $table->foreign('orcamento_id')->references('id')->on('orcamentos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orcamentocustos', function (Blueprint $table) {
            $table->dropForeign('orcamentocustos_orcamento_id_foreign');
            $table->dropColumn('orcamento_id');
        });
    }
}
