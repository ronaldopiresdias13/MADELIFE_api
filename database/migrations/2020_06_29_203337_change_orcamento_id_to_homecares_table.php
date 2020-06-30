<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOrcamentoIdToHomecaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homecares', function (Blueprint $table) {
            $table->unsignedBigInteger('orcamento_id')->nullable()->change();
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
            $table->unsignedBigInteger('orcamento_id')->nullable(false)->change();
        });
    }
}
