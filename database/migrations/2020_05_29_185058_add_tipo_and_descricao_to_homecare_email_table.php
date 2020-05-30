<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoAndDescricaoToHomecareEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homecare_email', function (Blueprint $table) {
            $table->string('descricao')->nullable()->after('email_id');
            $table->string('tipo')->nullable()->after('email_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homecare_email', function (Blueprint $table) {
            $table->dropColumn('tipo');
            $table->dropColumn('descricao');
        });
    }
}
