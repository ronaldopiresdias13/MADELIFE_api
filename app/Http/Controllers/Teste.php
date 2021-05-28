<?php

namespace App\Http\Controllers;

use App\Models\Orc;
use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Teste extends Controller
{
    public function teste(Request $request)
    {
        $empresa_id = 1;
        $o = Orc::where('empresa_id', $empresa_id)
        ->count('id');

        $numero = null;

        switch($request->versao){

            case 'orcamento':

                $numero = "o" . ($o + 1);
                break;
            case 'aditivo':

                $a = Orc::where('empresa_id', 'versao')
                        ->where('orc_id', $request->orc_id)
                        ->count('id');

                $numero = "a" . $request->orc_id . "-" . ($a + 1);

                break;
            case 'prorrogacao':

                $p = Orc::where('empresa_id', 'versao')
                        ->where('orc_id', $request->orc_id)
                        ->count('id');

                $numero = "p" . $request->orc_id . "-" . ($p + 1);

                break;
            default:

            break;

            }
        return $numero;

    }
}
