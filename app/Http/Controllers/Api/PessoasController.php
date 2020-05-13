<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PessoasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $pessoas = new Pessoa;
        // $pessoas = DB::table('pessoas')->where('status', true)->orderBy('nome')->get();
        $pessoas = Pessoa::all()->where('status', true);
        foreach ($pessoas as $key => $p) {
            $p->enderecos;
        }
        return $pessoas;
        // return DB::table('pessoas')->where('status', true)->orderBy('nome')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pessoa = new Pessoa;
        $pessoa->nome = $request->nome;
        $pessoa->nascimento = $request->nascimento;
        $pessoa->tipo = $request->tipo;
        $pessoa->cpfcnpj = $request->cpfcnpj;
        $pessoa->rgie = $request->rgie;
        $pessoa->observacoes = $request->observacoes;
        $pessoa->status = $request->status;
        $pessoa->save();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pessoa  $pessoa
     * @return \Illuminate\Http\Response
     */
    public function show(Pessoa $pessoa)
    {
        return $pessoa;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pessoa  $pessoa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pessoa $pessoa)
    {
        $pessoa->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pessoa  $pessoa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pessoa $pessoa)
    {
        $pessoa->delete();
    }
}
