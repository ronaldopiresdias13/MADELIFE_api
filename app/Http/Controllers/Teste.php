<?php

namespace App\Http\Controllers;

use App\Agendamento;
use App\Pessoa;
use Illuminate\Http\Request;

class Teste extends Controller
{
    public function teste(Request $request)
    {
        // Agendamento::find(1)->delete();

        // Agendamento::withTrashed()->find(1)->restore();

        return Agendamento::all();

        return Pessoa::with('cliente')->get();

        // if (auth()->check()) {
        //     // return 'Teste';
        //     return auth()->user(); //->pessoa; //->profissional->empresa_id;
        //     // static::creating(function ($model) {
        //     // });
        // }
    }
}
