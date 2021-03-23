<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPacoteIdToOrcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orcs', function (Blueprint $table) {
            $table->uuid('pacote_id')->nullable()->after('cliente_id');
            $table->foreign('pacote_id')->references('id')->on('pacotes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orcs', function (Blueprint $table) {
            $table->dropForeign(['pacote_id']);
            $table->dropColumn('paciente_id');
        });
    }
}
