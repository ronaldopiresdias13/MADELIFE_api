<?php

namespace App\Http\Controllers;

use App\Models\Escala;
use App\Models\Orc;
use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Teste extends Controller
{
    public function teste(Request $request)
    {
        $escala = Escala::find(1);
        $escala->ativo = $request->ativo;
        $escala->save();

        return $escala;
    }
}
