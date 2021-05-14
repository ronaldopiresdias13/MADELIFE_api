<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCpfCnpjDadosbancarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dadosbancarios', function (Blueprint $table) {
            $table->string('cpfcnpj')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dadosbancarios', function (Blueprint $table) {
            $table->dropColumn('cpfcnpj');
        });
    }
}
