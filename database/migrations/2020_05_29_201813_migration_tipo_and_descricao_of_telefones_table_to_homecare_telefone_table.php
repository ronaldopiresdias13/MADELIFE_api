<?php

namespace App\database\migrations;

use App\Homecare;
use App\HomecareTelefone;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrationTipoAndDescricaoOfTelefonesTableToHomecareTelefoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $homecares = Homecare::all();
        foreach ($homecares as $key => $homecare) {
            $telefones = $homecare->telefones;
            foreach ($telefones as $key => $telefone) {
                HomecareTelefone::where('telefone_id', $telefone->id)->update(['tipo' => $telefone->tipo, 'descricao' => $telefone->descricao]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
