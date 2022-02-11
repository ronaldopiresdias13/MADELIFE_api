<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnEscalaIdToAcaomedicamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acaomedicamentos', function (Blueprint $table) {
            $table->unsignedBigInteger('escala_id')->nullable()->after('prestador_id');
            $table->foreign('escala_id')->references('id')->on('escalas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acaomedicamentos', function (Blueprint $table) {
            $table->dropForeign('acaomedicamentos_escala_id_foreign');
            $table->dropColumn('escala_id');
        });
    }
}
