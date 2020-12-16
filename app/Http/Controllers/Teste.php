<?php

namespace App\Http\Controllers;

use App\Banco;
use App\Ordemservico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Teste extends Controller
{
    public function teste(Request $request)
    {
        return Banco::orderBy('codigo')->get();
    }
}
