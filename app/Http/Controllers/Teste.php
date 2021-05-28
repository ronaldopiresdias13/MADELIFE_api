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
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;

        $o = null;
        $numero = null;

        switch ($request->versao) {
            case 'Orçamento':
                $o = Orc::where('empresa_id', $empresa_id)
                    ->count('id');
                $numero = "O" . ($o + 1);
                break;
            case 'Aditivo':
                $o = Orc::find($request->orc_id)->numero;
                if (substr($o, 0, 1) == 'O') {
                    $o = substr($o, 1);
                } elseif (substr($o, 0, 1) == 'A' || substr($o, 0, 1) == 'P') {
                    $o = substr(explode('-', $o)[0], 1);
                }
                $a = Orc::where('empresa_id', $empresa_id)
                    ->where('versao', $request->versao)
                    ->where('orc_id', $request->orc_id)
                    ->count('id');
                $numero = "A" . $o . "-" . ($a + 1);
                break;
            case 'Prorrogação':
                $o = Orc::find($request->orc_id)->numero;
                if (substr($o, 0, 1) == 'O') {
                    $o = substr($o, 1);
                } elseif (substr($o, 0, 1) == 'A' || substr($o, 0, 1) == 'P') {
                    $o = substr(explode('-', $o)[0], 1);
                }
                $p = Orc::where('empresa_id', $empresa_id)
                    ->where('versao', $request->versao)
                    ->where('orc_id', $request->orc_id)
                    ->count('id');
                $numero = "P" . $o . "-" . ($p + 1);
                break;
        }

        return $numero;
    }
}
