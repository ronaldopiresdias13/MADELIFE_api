<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoAndDescricaoToRemocaoTelefoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('remocao_telefone', function (Blueprint $table) {
            $table->string('descricao')->nullable()->after('telefone_id');
            $table->string('tipo')->nullable()->after('telefone_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('remocao_telefone', function (Blueprint $table) {
            $table->dropColumn('descricao');
            $table->dropColumn('tipo');
        });
    }
}
