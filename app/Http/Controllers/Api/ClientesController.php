<?php

namespace App\Http\Controllers\Api;

use App\Cliente;
use App\Pessoa;
use App\PessoaEndereco;
use App\Endereco;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cliente::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cliente = new Cliente;
        $cliente->pessoa = $request->pessoa;
        $cliente->save();
    }
   
    /**
     * Display the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        return $cliente;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        $cliente->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
    }
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function migracao(Request $request)
    {
        // dd($request);
        $cliente = Cliente::firstOrCreate([
            'pessoa_id' => Pessoa::firstOrCreate(
                [
                    'cpfcnpj' => $request['pessoa']['cpfcnpj'],
                ],
                [
                    'nome'        => $request['pessoa']['nome'],
                    'nascimento'  => $request['pessoa']['nascimento'],
                    'tipo'        => $request['pessoa']['tipo'],
                    'rgie'        => $request['pessoa']['rgie'],
                    'observacoes' => $request['pessoa']['observacoes'],
                    'status'      => $request['pessoa']['status'],
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

        $pessoa_endereco = PessoaEndereco::firstOrCreate([
            'pessoa_id'   => $cliente->pessoa_id,
            'endereco_id' => Endereco::firstOrCreate(
                [
                    'cep'         => $request['endereco']['cep'],
                    'cidade_id'   => $request['endereco']['cidade_id'],
                    'rua'         => $request['endereco']['rua'],
                    'bairro'      => $request['endereco']['bairro'],
                    'numero'      => $request['endereco']['numero'],
                    'complemento' => $request['endereco']['complemento'],
                    'tipo'        => $request['endereco']['tipo'],
                ]
            )->id,
        ]);

        
    }
}
