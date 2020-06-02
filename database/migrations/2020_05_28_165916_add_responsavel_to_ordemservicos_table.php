<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResponsavelToOrdemservicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordemservicos', function (Blueprint $table) {
            $table->unsignedBigInteger('responsavel_id')->after('orcamento_id')->nullable();
            $table->foreign('responsavel_id')->references('id')->on('responsaveis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordemservicos', function (Blueprint $table) {
            $table->dropForeign('ordemservicos_responsavel_id_foreign');
            $table->dropColumn('responsavel_id');
        });
    }
}

