<?php

namespace App\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCpfcnpjHomecaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homecares', function (Blueprint $table) {
            $table->renameColumn('cpjcnpj', 'cpfcnpj');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homecares', function (Blueprint $table) {
            $table->renameColumn('cpfcnpj', 'cpjcnpj');
        });
    }
}
