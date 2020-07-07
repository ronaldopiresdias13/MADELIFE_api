<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToEmpresaPrestadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresa_prestador', function (Blueprint $table) {
            $table->string('status')->nullable()->after('prestador_id');
            $table->string('dataFim')->nullable()->after('prestador_id');
            $table->string('dataInicio')->nullable()->after('prestador_id');
            $table->string('contrato')->nullable()->after('prestador_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresa_prestador', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('dataFim');
            $table->dropColumn('dataInicio');
            $table->dropColumn('contrato');
        });
    }
}
