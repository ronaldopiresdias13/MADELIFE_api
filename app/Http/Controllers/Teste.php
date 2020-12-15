<?php

namespace App\Http\Controllers;

use App\Agendamento;
use App\Categoriadocumento;
use App\Pessoa;
use App\User;
use Illuminate\Http\Request;

class Teste extends Controller
{
    public function teste(Request $request)
    {
        $user = User::firstWhere('email', $request['email']);

        if (!$user || !$user->pessoa->prestador) {
            return response()->json([
                'alert' => [
                    'title' => 'Ops!',
                    'text' => 'Email não cadastrado!'
                ]
            ], 400)
                ->header('Content-Type', 'application/json');
        }

        // if (!$user->pessoa->prestador) {
        //     return response()->json([
        //         'alert' => [
        //             'title' => 'Ops!',
        //             'text' => 'Email não cadastrado!'
        //         ]
        //     ], 400)
        //         ->header('Content-Type', 'application/json');
        // } else {
        //     return response()->json([
        //         'toast' => [
        //             'text' => 'Não tem!'
        //         ]
        //     ], 200)
        //         ->header('Content-Type', 'application/json');
        // }



        return 'teste';

        // Categoriadocumento::find(1)->delete();

        // Categoriadocumento::withTrashed()->find(1)->restore();

        $categoriadocumentos = Categoriadocumento::all();

        foreach ($categoriadocumentos as $key => $categoriadocumento) {
            $categoriadocumento->documentos;
        }

        return $categoriadocumentos[0]->documentos[0];

        return Pessoa::with('cliente')->get();

        // if (auth()->check()) {
        //     // return 'Teste';
        //     return auth()->user(); //->pessoa; //->profissional->empresa_id;
        //     // static::creating(function ($model) {
        //     // });
        // }
    }
}
