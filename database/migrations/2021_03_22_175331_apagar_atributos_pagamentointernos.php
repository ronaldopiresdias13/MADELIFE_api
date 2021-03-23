<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApagarAtributosPagamentointernos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagamentointernos', function (Blueprint $table) {
            $table->unsignedBigInteger('pagamentopessoa_id')->nullable()->after('empresa_id');
            $table->foreign('pagamentopessoa_id')->references('id')->on('pagamentopessoas');
        });
        // Table PAGAMENTOEXTENOS
        Schema::table('pagamentointernos', function (Blueprint $table) {
            $table->dropForeign(['pessoa_id']);
            $table->dropColumn('pessoa_id');
            $table->dropColumn('datainicio');
            $table->dropColumn('datafim');
            $table->dropColumn('proventos');
            $table->dropColumn('descontos');
            $table->dropColumn('status');
            $table->dropColumn('situacao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagamentointernos', function (Blueprint $table) {
            $table->dropForeign(['pagamentopessoa_id']);
            $table->dropColumn('pagamentopessoa_id');
        });
        Schema::table('pagamentointernos', function (Blueprint $table) {
            $table->unsignedBigInteger('pessoa_id')->nullable();
            $table->foreign('pessoa_id')->references('id')->on('pessoas');
            $table->string('datainicio')->nullable();
            $table->string('datafim')->nullable();
            $table->boolean('status')->default(0);
            $table->string('situacao')->nullable();
            $table->float('proventos')->nullable();
            $table->float('descontos')->nullable();
        });
    }
}
