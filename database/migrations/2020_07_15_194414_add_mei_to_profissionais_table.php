<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMeiToProfissionaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->string('dataverificacaomei')->nullable()->after('dadoscontratuais_id');
            $table->boolean('meiativa')->default(false)->after('dadoscontratuais_id');
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
            $table->dropColumn('meiativa');
            $table->dropColumn('dataverificacaomei');
        });
    }
}
