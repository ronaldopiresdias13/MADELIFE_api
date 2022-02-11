<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSituacaoToPagamentointerno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagamentointernos', function (Blueprint $table) {
            $table->string('situacao')->nullable()->after('status')->default("Pendente");
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
            $table->dropColumn('situacao');
        });
    }
}
