<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObservacaoRemocoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('remocoes', function (Blueprint $table) {
            $table->string('observacao')->nullable()->after('cidadedestino');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('remocoes', function (Blueprint $table) {
            $table->dropColumn('observacao');
        });
    }
}
