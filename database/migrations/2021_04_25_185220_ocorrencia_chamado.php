<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OcorrenciaChamado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chamados', function (Blueprint $table) {
            $table->unsignedBigInteger('ocorrencia_id')->nullable();
            $table->foreign('ocorrencia_id')->references('id')->on('ocorrencias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('chamados');
        Schema::table('chamados', function (Blueprint $table) {
            $table->dropForeign(['ocorrencia_id']);
            $table->dropColumn('ocorrencia_id');
        });
    }
}
