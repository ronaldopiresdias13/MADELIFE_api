<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Prestador;
use App\Pessoa;
use Illuminate\Http\Request;

class PrestadoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Prestador::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $prestador = new Prestador;
        $prestador->pessoa       = $request->pessoa;
        $prestador->fantasia     = $request->fantasia;
        $prestador->sexo         = $request->sexo;
        $prestador->pis          = $request->pis;
        $prestador->formacao     = $request->formacao;
        $prestador->cargo        = $request->cargo;
        $prestador->curriculo    = $request->curriculo;
        $prestador->certificado  = $request->certificado;
        $prestador->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function show(Prestador $prestador)
    {
        return $prestador;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prestador $prestador)
    {
        $prestador->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prestador $prestador)
    {
        $prestador->delete();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function migracao(Request $request)
    {
        // dd('$request');
        // dd($request);
        foreach ($request->all() as $key => $prestador) {
            // dd($prestador['prestador']['dadosPf']['nome']);
            dd($prestador['prestador']);
            // dd($prestador);
            $pessoa = Pessoa::firstOrCreate(
                ['cpfcnpj' => $prestador['prestador']['dadosPf']['cpf']['numero']],
                [
                    'nome'        => $prestador['prestador']['dadosPf'],
                    'nascimento'  => $prestador['prestador']['dadosPf'],
                    'tipo'        => $prestador['prestador']['dadosPf'],
                    'rgie'        => $prestador['prestador']['dadosPf'],
                    'observacoes' => $prestador['prestador']['dadosPf']]
            )->id;

            $teste = UserAcesso::updateOrCreate(
                ['user'  => $user->id, 'acesso' => Acesso::FirstOrCreate(['nome' => $value['nome']])->id]
            );
        }

        dd($request->all());
        $prestador = new Prestador;
        $prestador->pessoa      = $request->pessoa;
        $prestador->fantasia    = $request->fantasia;
        $prestador->sexo        = $request->sexo;
        $prestador->pis         = $request->pis;
        $prestador->formacao    = $request->formacao;
        $prestador->cargo       = $request->cargo;
        $prestador->curriculo   = $request->curriculo;
        $prestador->certificado = $request->certificado;
        $prestador->save();
    }
}
