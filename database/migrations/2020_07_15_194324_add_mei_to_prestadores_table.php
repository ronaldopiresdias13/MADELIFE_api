<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMeiToPrestadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prestadores', function (Blueprint $table) {
            $table->string('dataverificacaomei')->nullable()->after('certificado');
            $table->boolean('meiativa')->default(false)->after('certificado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prestadores', function (Blueprint $table) {
            $table->dropColumn('meiativa');
            $table->dropColumn('dataverificacaomei');
        });
    }
}
