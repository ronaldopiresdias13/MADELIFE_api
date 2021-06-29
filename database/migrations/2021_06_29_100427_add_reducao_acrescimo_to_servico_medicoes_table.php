<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReducaoAcrescimoToServicoMedicoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servico_medicoes', function (Blueprint $table) {
            $table->string('reducaoAcrescimo')->nullable()->after('valor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servico_medicoes', function (Blueprint $table) {
            $table->dropColumn('reducaoAcrescimo');
        });
    }
}
