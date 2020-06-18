<?php

namespace App\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToContasbancariasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contasbancarias', function (Blueprint $table) {
            $table->string('descricao')->nullable()->after('saldo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contasbancarias', function (Blueprint $table) {
            $table->dropColumn('descricao');
        });
    }
}
