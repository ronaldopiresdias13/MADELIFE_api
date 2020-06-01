<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrationTipoAndDescricaoOfEmailsTableToHomecareEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $homecares = App\Homecare::all();
        foreach ($homecares as $key => $homecare) {
            $emails = $homecare->emails;
            foreach ($emails as $key => $email) {
                App\HomecareEmail::where('email_id', $email->id)->update(['tipo' => $email->tipo, 'descricao' => $email->descricao]);
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