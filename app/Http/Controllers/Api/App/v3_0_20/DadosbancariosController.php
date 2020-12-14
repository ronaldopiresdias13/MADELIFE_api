<?php

namespace App\Http\Controllers\Api\App\v3_0_20;

use App\Dadosbancario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DadosbancariosController extends Controller
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
        DB::transaction(function () use ($request) {
            $dadosbancario = new Dadosbancario();
            $dadosbancario->empresa_id = 1;
            $dadosbancario->banco_id = $request->banco_id;
            $dadosbancario->pessoa_id = $request->pessoa_id;
            $dadosbancario->agencia = $request->agencia;
            $dadosbancario->conta = $request->conta;
            $dadosbancario->digito = $request->digito;
            $dadosbancario->tipoconta = $request->tipoconta;
            $dadosbancario->ativo = 1;
            $dadosbancario->save();
        });

        return response()->json([
            'toast' => [
                'text' => 'Salvo com sucesso!',
                'color' => 'success',
                'duration' => 2000
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dadosbancario  $dadosbancario
     * @return \Illuminate\Http\Response
     */
    public function show(Dadosbancario $dadosbancario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dadosbancario  $dadosbancario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dadosbancario $dadosbancario)
    {
        DB::transaction(function () use ($request, $dadosbancario) {
            $dadosbancario->banco_id = $request->banco_id;
            $dadosbancario->pessoa_id = $request->pessoa_id;
            $dadosbancario->agencia = $request->agencia;
            $dadosbancario->conta = $request->conta;
            $dadosbancario->digito = $request->digito;
            $dadosbancario->tipoconta = $request->tipoconta;
            $dadosbancario->save();
        });

        return response()->json([
            'toast' => [
                'text' => 'Atualizado com sucesso!',
                'color' => 'success',
                'duration' => 2000
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dadosbancario  $dadosbancario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dadosbancario $dadosbancario)
    {
        DB::transaction(function () use ($dadosbancario) {
            $dadosbancario->ativo = false;
            $dadosbancario->save();
        });

        return response()->json([
            'toast' => [
                'text' => 'Excluido com sucesso!',
                'color' => 'success',
                'duration' => 2000
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }
}
