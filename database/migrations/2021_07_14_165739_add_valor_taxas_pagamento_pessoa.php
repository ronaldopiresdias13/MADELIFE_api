<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValorTaxasPagamentoPessoa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagamentopessoas', function (Blueprint $table) {
            $table->double('INSS')->nullable()->after('descontos');
            $table->double('ISS')->nullable()->after('INSS');
            $table->double('taxaADM')->nullable()->after('ISS');
            $table->string('adicionalExtra')->nullable()->after('taxaADM');
            $table->string('adicionalOutros')->nullable()->after('adicionalExtra');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagamentopessoas', function (Blueprint $table) {
            $table->dropColumn('INSS');
            $table->dropColumn('ISS');
            $table->dropColumn('taxaADM');
            $table->dropColumn('adicionalExtra');
            $table->dropColumn('adicionalOutros');
        });
    }
}
