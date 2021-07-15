<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValorTaxasEmpresaPrestador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresa_prestador', function (Blueprint $table) {
            $table->double('valorinss')->nullable()->after('dataFim');
            $table->string('tipovalorinss')->nullable()->after('valorinss');
            $table->double('valoriss')->nullable()->after('tipovalorinss');
            $table->string('tipovaloriss')->nullable()->after('valoriss');
            $table->double('taxaadm')->nullable()->after('tipovaloriss');
            $table->string('tipotaxaadm')->nullable()->after('taxaadm');
            $table->double('adionalextra')->nullable()->after('tipotaxaadm');
            $table->double('adionaloutros')->nullable()->after('adionalextra');
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
            $table->dropColumn('valorinss');
            $table->dropColumn('tipovalorinss');
            $table->dropColumn('valoriss');
            $table->dropColumn('tipovaloriss');
            $table->dropColumn('taxaadm');
            $table->dropColumn('tipotaxaadm');
            $table->dropColumn('adionalextra');
            $table->dropColumn('adionaloutros');
        });
    }
}
