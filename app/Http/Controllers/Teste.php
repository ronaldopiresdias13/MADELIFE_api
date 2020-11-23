<?php

namespace App\Http\Controllers;

use App\Agendamento;
use App\Categoriadocumento;
use App\Pessoa;
use Illuminate\Http\Request;

class Teste extends Controller
{
    public function teste(Request $request)
    {
        // Categoriadocumento::find(1)->delete();

        // Categoriadocumento::withTrashed()->find(1)->restore();

        $categoriadocumentos = Categoriadocumento::all();

        foreach ($categoriadocumentos as $key => $categoriadocumento) {
            $categoriadocumento->documentos;
        }

        return $categoriadocumentos[0]['documentos'][0];

        return Pessoa::with('cliente')->get();

        // if (auth()->check()) {
        //     // return 'Teste';
        //     return auth()->user(); //->pessoa; //->profissional->empresa_id;
        //     // static::creating(function ($model) {
        //     // });
        // }
    }
}
