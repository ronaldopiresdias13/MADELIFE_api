<?php

namespace App\Http\Controllers\Api;

use App\Banco;
use App\Pessoa;
use App\Dadosbancario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DadosbancariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Dadosbancario::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dadosbancario = new Dadosbancario;
        // $dadosbancario->pessoa = Pessoa::firstWhere('cpfcnpj', $request->pessoa)->id;
        // $dadosbancario->banco = Banco::firstWhere('codigo', $request->banco)->id;
        $dadosbancario->banco = $request->banco;
        $dadosbancario->pessoa = $request->pessoa;
        $dadosbancario->agencia = $request->agencia;
        $dadosbancario->conta = $request->conta;
        $dadosbancario->digito = $request->digito;
        $dadosbancario->tipoconta = $request->tipoconta;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dadosbancario  $dadosbancario
     * @return \Illuminate\Http\Response
     */
    public function show(Dadosbancario $dadosbancario)
    {
        return $dadosbancario;
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
        $dadosbancario->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dadosbancario  $dadosbancario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dadosbancario $dadosbancario)
    {
        $dadosbancario->delete();
    }
}
