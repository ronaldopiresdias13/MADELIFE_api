<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnsToOrdemservicoServicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordemservico_servico', function (Blueprint $table) {
            $table->renameColumn('valor', 'valordiurno');
            $table->renameColumn('adicionalnoturno', 'valornoturno');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordemservico_servico', function (Blueprint $table) {
            $table->renameColumn('valordiurno', 'valor');
            $table->renameColumn('valornoturno', 'adicionalnoturno');
        });
    }
}
