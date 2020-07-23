<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsFromCertificateToPrestadorFormacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prestador_formacao', function (Blueprint $table) {
            $table->string('nome')->nullable()->after('formacao_id');
            $table->string('caminho')->nullable()->after('formacao_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prestador_formacao', function (Blueprint $table) {
            $table->dropColumn('caminho');
            $table->dropColumn('nome');
        });
    }
}
