<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnsEmpresas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('quantidadead');
            $table->dropColumn('quantidadeid');
            $table->dropColumn('valorid');
            $table->renameColumn('valorad', 'valor');
            $table->float('valorimplantacao')->nullable()->after('situacao');
            $table->string('dataimplantacao')->nullable()->after('valorimplantacao');
            $table->string('datapagamento')->nullable()->after('dataimplantacao');
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
            $table->renameColumn('valor', 'valorad');
            $table->integer('quantidadead')->nullable()->after('quantidadepaciente');
            $table->integer('quantidadeid')->nullable();
            $table->integer('valorid')->nullable()->after('quantidadeid');
            $table->dropColumn('valorimplantacao');
            $table->dropColumn('dataimplantacao');
            $table->dropColumn('datapagamento');
        });
    }
}
