<?php

use App\Models\Escala;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrestadorProprietarioToEscalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('escalas', function (Blueprint $table) {
             $table->unsignedBigInteger('prestador_proprietario')->nullable()->after('ordemservico_id');
             $table->foreign('prestador_proprietario')->references('id')->on('prestadores');
         });

        $escalas = Escala::all();
        foreach ($escalas as $key => $escala) {
            if ($escala->substituto) {
                $escala->prestador_proprietario = $escala->substituto;
                $escala->folga = true;
            } else {
                $escala->prestador_proprietario = $escala->prestador_id;
            }
            $escala->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('escalas', function (Blueprint $table) {
            $table->dropForeign(['prestador_proprietario']);
            $table->dropColumn('prestador_proprietario');
        });
    }
}
