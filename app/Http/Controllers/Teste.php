<?php

namespace App\Http\Controllers;

use App\Pessoa;
use Illuminate\Http\Request;

class Teste extends Controller
{
    public function teste(Request $request)
    {
        return Pessoa::with('cliente')->get();

        // if (auth()->check()) {
        //     // return 'Teste';
        //     return auth()->user(); //->pessoa; //->profissional->empresa_id;
        //     // static::creating(function ($model) {
        //     // });
        // }
    }
}
