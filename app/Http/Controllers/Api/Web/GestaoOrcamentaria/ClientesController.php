<?php

namespace App\Http\Controllers\Api\Web\GestaoOrcamentaria;

use App\Models\Cliente;
use App\Models\Email;
use App\Models\Empresa;
use App\Models\Endereco;
use App\Http\Controllers\Controller;
use App\Models\Pessoa;
use App\Models\PessoaEmail;
use App\Models\PessoaEndereco;
use App\Models\PessoaTelefone;
use App\Models\Telefone;
use App\Models\Tipopessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Empresa $empresa)
    {
        return Cliente::with(['pessoa.emails', 'pessoa.telefones', 'pessoa.enderecos.cidade'])
            ->where('empresa_id', $empresa->id)
            ->where('ativo', true)
            ->get();
        // ->orderBy('pessoas.nome', 'DESC');
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
            $cliente = Cliente::create([
                'tipo'       => $request['tipo'],
                'empresa_id' => $request['empresa_id'],
                'pessoa_id'  => Pessoa::updateOrCreate(
                    [
                        'id' => ($request['pessoa']['id'] != '') ? $request['id'] : null,
                    ],
                    [
                        'nome'        => $request['pessoa']['nome'],
                        'nascimento'  => $request['pessoa']['nascimento'],

                        'cpfcnpj'     => $request['pessoa']['cpfcnpj'],
                        'rgie'        => $request['pessoa']['rgie'],
                        'observacoes' => $request['pessoa']['observacoes'],
                        'perfil'      => $request['pessoa']['perfil'],
                        'status'      => $request['pessoa']['status'],
                    ]
                )->id,
            ]);
            Tipopessoa::create([
                'tipo'      => 'Cliente',
                'pessoa_id' => $cliente->pessoa_id,
                'ativo'     => 1
            ]);

            if ($request['pessoa']['telefones']) {
                foreach ($request['pessoa']['telefones'] as $key => $telefone) {
                    PessoaTelefone::firstOrCreate([
                        'pessoa_id'   => $cliente->pessoa_id,
                        'telefone_id' => Telefone::firstOrCreate(
                            [
                                'telefone'  => $telefone['telefone'],
                            ]
                        )->id,
                        'tipo'       => $telefone['pivot']['tipo'],
                        'descricao'  => $telefone['pivot']['descricao']
                    ]);
                }
            }

            if ($request['pessoa']['enderecos']) {
                foreach ($request['pessoa']['enderecos'] as $key => $endereco) {
                    PessoaEndereco::firstOrCreate([
                        'pessoa_id'   => $cliente->pessoa_id,
                        'endereco_id' => Endereco::firstOrCreate(
                            [
                                'cep'         => $endereco['cep'],
                                'cidade_id'   => $endereco['cidade_id'],
                                'rua'         => $endereco['rua'],
                                'bairro'      => $endereco['bairro'],
                                'numero'      => $endereco['numero'],
                                'complemento' => $endereco['complemento'],
                                'tipo'        => $endereco['tipo'],
                                'descricao'   => $endereco['descricao'],
                            ]
                        )->id,
                    ]);
                }
            }

            if ($request['pessoa']['emails']) {
                foreach ($request['pessoa']['emails'] as $key => $email) {
                    PessoaEmail::firstOrCreate([
                        'pessoa_id' => $cliente->pessoa_id,
                        'email_id'  => Email::firstOrCreate(
                            [
                                'email'     => $email['email'],
                            ]
                        )->id,
                        'tipo'       => $telefone['pivot']['tipo'],
                        'descricao'  => $telefone['pivot']['descricao']
                    ]);
                }
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        $cliente->pessoa;
        $cliente->pessoa->telefones;
        $cliente->pessoa->telefones;
        $cliente->pessoa->emails;
        if ($cliente->pessoa->enderecos) {
            foreach ($cliente->pessoa->enderecos as $key => $endereco) {
                $endereco->cidade;
            }
        }
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
        DB::transaction(function () use ($request, $cliente) {
            $cliente->update([
                'tipo' => $request['tipo'],
                'empresa_id' => $request['empresa_id'],
            ]);
            $pessoa = Pessoa::find($request['pessoa']['id']);
            if ($pessoa) {
                $pessoa->update([
                    'nome'        => $request['pessoa']['nome'],
                    'nascimento'  => $request['pessoa']['nascimento'],
                    // 'tipo'        => $request['pessoa']['tipo'],
                    'cpfcnpj'     => $request['pessoa']['cpfcnpj'],
                    'rgie'        => $request['pessoa']['rgie'],
                    'observacoes' => $request['pessoa']['observacoes'],
                    'perfil'      => $request['pessoa']['perfil'],
                    'status'      => $request['pessoa']['status'],
                ]);
            }
            if ($request['pessoa']['telefones']) {
                foreach ($request['pessoa']['telefones'] as $key => $telefone) {
                    PessoaTelefone::firstOrCreate(
                        [
                            'pessoa_id'   => $pessoa->id,
                            'telefone_id' => Telefone::firstOrCreate(
                                [
                                    'telefone'  => $telefone['telefone'],
                                ]
                            )->id,
                        ],
                        [
                            'tipo'      => $telefone['pivot']['tipo'],
                            'descricao' => $telefone['pivot']['descricao'],
                        ]
                    );
                }
            }
            if ($request['pessoa']['enderecos']) {
                foreach ($request['pessoa']['enderecos'] as $key => $endereco) {
                    PessoaEndereco::updateOrCreate(
                        [
                            'pessoa_id'   => $pessoa->id,
                            'endereco_id' => Endereco::firstOrCreate(
                                [
                                    'cep'         => $endereco['cep'],
                                    'cidade_id'   => $endereco['cidade_id'],
                                    'rua'         => $endereco['rua'],
                                    'bairro'      => $endereco['bairro'],
                                    'numero'      => $endereco['numero'],
                                    'complemento' => $endereco['complemento'],
                                    'tipo'        => $endereco['tipo'],
                                    'descricao'   => $endereco['descricao'],
                                ]
                            )->id,
                        ]
                    );
                }
            }
            if ($request['pessoa']['emails']) {
                foreach ($request['pessoa']['emails'] as $key => $email) {
                    PessoaEmail::updateOrCreate(
                        [
                            'pessoa_id' => $pessoa->id,
                            'email_id'  => Email::firstOrCreate(
                                [
                                    'email' => $email['email'],
                                ]
                            )->id,
                        ],
                        [
                            'tipo'      => $email['pivot']['tipo'],
                            'descricao' => $email['pivot']['descricao'],
                        ]
                    );
                }
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->ativo = false;
        $cliente->save();
    }
}
