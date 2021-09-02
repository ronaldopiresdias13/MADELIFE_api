<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAtributosAdIdEmpresas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->integer('quantidadead')->nullable()->after('quantidadepaciente');
            $table->integer('valorad')->nullable()->after('quantidadead');
            $table->integer('quantidadeid')->nullable()->after('valorad');
            $table->integer('valorid')->nullable()->after('quantidadeid');
            $table->string('telefone')->nullable()->after('valorid');
            $table->string('celular')->nullable()->after('telefone');
            $table->string('endereco')->nullable()->after('celular');
            $table->string('site')->nullable()->after('endereco');
            $table->string('email')->nullable()->after('site');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('quantidadead');
            $table->dropColumn('valorad');
            $table->dropColumn('quantidadeid');
            $table->dropColumn('valorid');
            $table->dropColumn('telefone');
            $table->dropColumn('celular');
            $table->dropColumn('endereco');
            $table->dropColumn('site');
            $table->dropColumn('email');
        });
    }
}
