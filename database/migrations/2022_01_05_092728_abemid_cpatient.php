<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AbemidCpatient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planilhas_abmids', function (Blueprint $table) {
            $table->foreignUuid('cpatient_id')->nullable()->references('id')->on('clients_patients')->onDelete('cascade');
            $table->unsignedBigInteger('paciente_id')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planilhas_abmids', function (Blueprint $table) {
            $table->dropForeign(['cpatient_id']);

            $table->dropColumn('cpatient_id');
        });
    }
}
