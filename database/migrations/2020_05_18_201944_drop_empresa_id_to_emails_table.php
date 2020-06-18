<?php

namespace App\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropEmpresaIdToEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->dropForeign('emails_empresa_id_foreign');
            $table->dropColumn('empresa_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->after('id')->default(1);
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });
    }
}
