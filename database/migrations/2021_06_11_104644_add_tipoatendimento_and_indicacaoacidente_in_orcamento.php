<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoatendimentoAndIndicacaoacidenteInOrcamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->string('tipoatentendimento')->nullable()->after('tipo');
            $table->string('indicacaoacidente')->nullable('observacao');
        });
        Schema::table('orcs', function (Blueprint $table) {
            $table->string('tipoatentendimento')->nullable()->after('tipo');
            $table->string('indicacaoacidente')->nullable('observacao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->dropColumn('tipoatentendimento');
            $table->dropColumn('indicacaoacidente');
        });
        Schema::table('orcs', function (Blueprint $table) {
            $table->dropColumn('tipoatentendimento');
            $table->dropColumn('indicacaoacidente');
        });
    }
}
