<?php

namespace App\Http\Controllers\Api\App\v3_0_20;

use App\Http\Controllers\Controller;
use App\Models\Pessoa;
use Illuminate\Http\Request;

class PessoasController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPessoaPerfil(Request $request)
    {
        $user = $request->user();
        $pessoa = Pessoa::with([
            'conselhos',
            'telefones',
            'emails',
            'enderecos.cidade',
            'dadosbancario.banco',
            'prestador.formacoes'
        ])
            ->find($user->pessoa_id);
        return $pessoa;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pessoa  $pessoa
     * @return \Illuminate\Http\Response
     */
    public function atualizaDadosPessoais(Request $request, Pessoa $pessoa)
    {
        $pessoa->nome = $request['nome'];
        $pessoa->nascimento = $request['nascimento'];
        $pessoa->cpfcnpj = $request['cpfcnpj'];
        $pessoa->rgie = $request['rgie'];
        $pessoa->observacoes = $request['observacoes'];
        $pessoa->perfil = $request['perfil'];
        $pessoa->status = $request['status'];
        $pessoa->save();

        $prestador = $pessoa->prestador;
        $prestador->sexo = $request['prestador']['sexo'];
        $prestador->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pessoa  $pessoa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pessoa $pessoa)
    {
        //
    }
}
