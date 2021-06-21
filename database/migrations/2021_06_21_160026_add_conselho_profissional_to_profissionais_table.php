<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConselhoProfissionalToProfissionaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->string('numeroConselhoProfissional')->nullable()->after('dataverificacaomei');
            $table->string('conselhoProfissional')->nullable()->after('dataverificacaomei');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->dropColumn('conselhoProfissional');
            $table->dropColumn('numeroConselhoProfissional');
        });
    }
}
