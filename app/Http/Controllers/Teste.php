<?php

namespace App\Http\Controllers;

use App\Banco;
use App\Ordemservico;
use App\OrdemservicoAcesso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Teste extends Controller
{
    public function teste()
    {
        return response()->json([
            'success' => [
                'text' => 'Apagado com sucesso!',
                'duration' => 1500
            ]
        ], 200)
            ->header('Content-Type', 'application/json');

        return response()->json([
            'error' => [
                'text' => 'Cliente já cadastrado!'
            ]
        ], 400)
            ->header('Content-Type', 'application/json');
    }
}
