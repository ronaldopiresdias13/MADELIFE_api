<?php

namespace App\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTipoAndDescricaoToTelefonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telefones', function (Blueprint $table) {
            $table->dropColumn('tipo');
            $table->dropColumn('descricao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telefones', function (Blueprint $table) {
            $table->string('descricao')->nullable()->after('telefone');
            $table->string('tipo')->nullable()->after('telefone');
        });
    }
}
