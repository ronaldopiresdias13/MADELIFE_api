<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndicacaoClinicaToOrcamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->string('indicacaoClinica')->nullable()->after('tipoatentendimento');
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
            $table->dropColumn('indicacaoClinica');
        });
    }
}
