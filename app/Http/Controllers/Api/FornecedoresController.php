<?php

namespace App\Http\Controllers\Api;

use App\Fornecedor;
use App\Pessoa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FornecedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Fornecedor::all();
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
     * @param  \App\Fornecedor  $fornecedor
     * @return \Illuminate\Http\Response
     */
    public function show(Fornecedor $fornecedor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Fornecedor  $fornecedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fornecedor $fornecedor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fornecedor  $fornecedor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fornecedor $fornecedor)
    {
        //
    }

    public function migracao(Request $request)
    {
        // dd($request);
        $fornecedor = Fornecedor::firstOrCreate([
            'pessoa_id' => Pessoa::firstOrCreate(
                [
                    'cpfcnpj' => 0,
                // ],
                // [
                    'nome'        => $request['dadosPf']['nome'],
                    'nascimento'  => $request['dadosPf']['nascimento'],
                    'tipo'        => 'Fornecedor',
                    'rgie'        => 0,
                    'observacoes' => $request['observacoes'],
                    'status'      => $request['status'],
                ]
            )->id,
            'empresa_id' => 1
        ]);
       
        
        // if ($request['prestador']['contato']['telefone'] != null && $request['prestador']['contato']['telefone'] != "") {
        //     $pessoa_telefones = PessoaTelefone::firstOrCreate([
        //         'pessoa'   => $prestador->pessoa,
        //         'telefone' => Telefone::firstOrCreate(
        //             [
        //                 'telefone' => $request['prestador']['contato']['telefone'],
        //             ]
        //         )->id,
        //     ]);
        // }
        // if ($request['prestador']['contato']['celular'] != null && $request['prestador']['contato']['celular'] != "") {
        //     $pessoa_telefones = PessoaTelefone::firstOrCreate([
        //         'pessoa'   => $prestador->pessoa,
        //         'telefone' => Telefone::firstOrCreate(
        //             [
        //                 'telefone' => $request['prestador']['contato']['celular'],
        //             ]
        //         )->id,
        //     ]);
        // }

        // $pessoa_emails = PessoaEmail::firstOrCreate([
        //     'pessoa' => $prestador->pessoa,
        //     'email'  => Email::firstOrCreate(
        //         [
        //             'email' => $request['prestador']['contato']['email'],
        //         ],
        //         [
        //             'tipo' => 'pessoal',
        //         ]
        //     )->id,
        // ]);

        // $cidade = Cidade::where('nome', $request['prestador']['endereco']['cidade'])->where('uf', $request['prestador']['endereco']['uf'])->first();

        // $pessoa_endereco = PessoaEndereco::firstOrCreate([
        //     'pessoa_id'   => $cliente->pessoa_id,
        //     'endereco_id' => Endereco::firstOrCreate(
        //         [
        //             'cep'         => $request['endereco']['cep'],
        //             'cidade_id'   => $request['endereco']['cidade_id'],
        //             'rua'         => $request['endereco']['rua'],
        //             'bairro'      => $request['endereco']['bairro'],
        //             'numero'      => $request['endereco']['numero'],
        //             'complemento' => $request['endereco']['complemento'],
        //             'tipo'        => $request['endereco']['tipo'],
        //         ]
        //     )->id,
        // ]);
    }
}
