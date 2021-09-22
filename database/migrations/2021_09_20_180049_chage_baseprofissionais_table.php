<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChageBaseprofissionaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baseprofissionais', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('id')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->text('complexidade')->nullable()->change();
            $table->boolean('conta_terceiro')->default(false)->change();
            $table->text('bloqueio_tomador')->nullable()->change();
            $table->text('documentos')->nullable()->change();
            $table->text('foto')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('baseprofissionais', function (Blueprint $table) {
            $table->dropForeign(['emppresa_id']);
            $table->dropColumn('emppresa_id');
            $table->string('complexidade')->nullable()->change();
            $table->string('conta_terceiro')->default(false)->change();
            $table->string('bloqueio_tomador')->nullable()->change();
            $table->string('documentos')->nullable()->change();
            $table->string('foto')->nullable()->change();
        });
    }
}
