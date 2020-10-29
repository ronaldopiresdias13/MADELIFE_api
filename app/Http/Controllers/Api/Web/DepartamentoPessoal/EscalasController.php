<?php

namespace App\Http\Controllers\Api\Web\DepartamentoPessoal;

use App\Escala;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EscalasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function show(Escala $escala)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function updateServicoOfEscala(Request $request, Escala $escala)
    {
        $escala->servico_id = $request['servico_id'];
        $escala->save();

        return response()->json([
            'alert' => [
                'title' => 'Parabéns!',
                'text' => 'Salvo com sucesso!'
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
        // return response()->json('Usuário já existe!', 400)->header('Content-Type', 'text/plain');
        // return 'teste';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Escala  $escala
     * @return \Illuminate\Http\Response
     */
    public function destroy(Escala $escala)
    {
        //
    }
}
