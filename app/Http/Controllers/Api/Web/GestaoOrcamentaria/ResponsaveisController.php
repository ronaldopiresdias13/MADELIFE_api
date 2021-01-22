<?php

namespace App\Http\Controllers\Api\Web\GestaoOrcamentaria;

use App\Models\Email;
use App\Models\Empresa;
use App\Models\Endereco;
use App\Http\Controllers\Controller;
use App\Models\Pessoa;
use App\Models\PessoaEmail;
use App\Models\PessoaEndereco;
use App\Models\PessoaTelefone;
use App\Models\Responsavel;
use App\Models\Telefone;
use App\Models\Tipopessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResponsaveisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Empresa $empresa)
    {
        return Responsavel::with(['pessoa.emails', 'pessoa.telefones', 'pessoa.enderecos.cidade'])
            ->where('empresa_id', $empresa->id)
            ->where('ativo', true)
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $pessoa = null;

        if ($request['pessoa']) {
            $pessoa = Pessoa::where(
                'cpfcnpj',
                $request['pessoa']['cpfcnpj']
            )->first();
        } elseif ($request['pessoa_id']) {
            $pessoa = Pessoa::find($request['pessoa_id']);
        }

        $responsavel = null;

        if ($pessoa) {
            $responsavel = Responsavel::firstWhere(
                'pessoa_id',
                $pessoa->id,
            );
        }

        if ($responsavel) {
            return response()->json('Responsável já existe!', 400)->header('Content-Type', 'text/plain');
        }

        DB::transaction(function () use ($request, $pessoa) {
            $responsavel = Responsavel::create([
                'empresa_id' => $request['empresa_id'],
                'parentesco' => $request['parentesco'],
                'pessoa_id'  => $pessoa ? $pessoa->id : Pessoa::create([
                    'nome'        => $request['pessoa']['nome'],
                    'nascimento'  => $request['pessoa']['nascimento'],
                    'cpfcnpj'     => $request['pessoa']['cpfcnpj'],
                    'rgie'        => $request['pessoa']['rgie'],
                    'observacoes' => $request['pessoa']['observacoes'],
                    'perfil'      => $request['pessoa']['perfil'],
                    'status'      => $request['pessoa']['status'],
                ])->id
            ]);

            Tipopessoa::create([
                'tipo'      => 'Responsável',
                'pessoa_id' => $responsavel->pessoa_id,
                'ativo'     => 1
            ]);

            if ($request['pessoa']['telefones']) {
                foreach ($request['pessoa']['telefones'] as $key => $telefone) {
                    PessoaTelefone::firstOrCreate([
                        'pessoa_id'   => $responsavel->pessoa_id,
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
                        'pessoa_id'   => $responsavel->pessoa_id,
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
                        'pessoa_id' => $responsavel->pessoa_id,
                        'email_id'  => Email::firstOrCreate(
                            [
                                'email'     => $email['email'],
                            ]
                        )->id,
                        'tipo'       => $email['pivot']['tipo'],
                        'descricao'  => $email['pivot']['descricao']
                    ]);
                }
            }
        });

        return response()->json([
            'alert' => [
                'title' => 'Ok!',
                'text' => 'Responsável cadastrado com sucesso!'
            ]
        ], 200)
            ->header('Content-Type', 'application/json');

        // return response()->json('Responsável cadastrado com sucesso!', 200)->header('Content-Type', 'text/plain');













        // $responsavel = Responsavel::create([
        //     'empresa_id' => $request['empresa_id'],
        //     'parentesco' => $request['parentesco'],
        //     'pessoa_id'  => Pessoa::create([
        //         'nome'        => $request['pessoa']['nome'],
        //         'nascimento'  => $request['pessoa']['nascimento'],
        //         'cpfcnpj'     => $request['pessoa']['cpfcnpj'],
        //         'rgie'        => $request['pessoa']['rgie'],
        //         'observacoes' => $request['pessoa']['observacoes'],
        //         'perfil'      => $request['pessoa']['perfil'],
        //         'status'      => $request['pessoa']['status'],
        //     ])->id
        // ]);
        // Tipopessoa::create([
        //     'tipo'      => 'Responsável',
        //     'pessoa_id' => $responsavel->pessoa_id,
        //     'ativo'     => 1
        // ]);
        // if ($request['pessoa']['telefones']) {
        //     foreach ($request['pessoa']['telefones'] as $key => $telefone) {
        //         PessoaTelefone::firstOrCreate([
        //             'pessoa_id'   => $responsavel->pessoa_id,
        //             'telefone_id' => Telefone::firstOrCreate(
        //                 [
        //                     'telefone'  => $telefone['telefone'],
        //                 ]
        //             )->id,
        //         ]);
        //     }
        // }
        // if ($request['pessoa']['enderecos']) {
        //     foreach ($request['pessoa']['enderecos'] as $key => $endereco) {
        //         PessoaEndereco::firstOrCreate([
        //             'pessoa_id'   => $responsavel->pessoa_id,
        //             'endereco_id' => Endereco::firstOrCreate(
        //                 [
        //                     'cep'         => $endereco['cep'],
        //                     'cidade_id'   => $endereco['cidade_id'],
        //                     'rua'         => $endereco['rua'],
        //                     'bairro'      => $endereco['bairro'],
        //                     'numero'      => $endereco['numero'],
        //                     'complemento' => $endereco['complemento'],
        //                     'tipo'        => $endereco['tipo'],
        //                     'descricao'   => $endereco['descricao'],
        //                 ]
        //             )->id,
        //         ]);
        //     }
        // }
        // if ($request['pessoa']['emails']) {
        //     foreach ($request['pessoa']['emails'] as $key => $email) {
        //         PessoaEmail::firstOrCreate([
        //             'pessoa_id' => $responsavel->pessoa_id,
        //             'email_id'  => Email::firstOrCreate(
        //                 [
        //                     'email'     => $email['email'],
        //                 ]
        //             )->id,
        //         ]);
        //     }
        // }
        // });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Responsavel  $responsavel
     * @return \Illuminate\Http\Response
     */
    public function show(Responsavel $responsavel)
    {
        $responsavel->pessoa;
        $responsavel->pessoa->telefones;
        $responsavel->pessoa->telefones;
        $responsavel->pessoa->emails;
        if ($responsavel->pessoa->enderecos) {
            foreach ($responsavel->pessoa->enderecos as $key => $endereco) {
                $endereco->cidade;
            }
        }
        return $responsavel;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Responsavel  $responsavel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Responsavel $responsavel)
    {
        DB::transaction(function () use ($request, $responsavel) {
            $responsavel->update([
                'parentesco' => $request['parentesco'],
                'empresa_id' => $request['empresa_id'],
            ]);
            $pessoa = Pessoa::find($request['pessoa']['id']);
            if ($pessoa) {
                $pessoa->update([
                    'nome'        => $request['pessoa']['nome'],
                    'nascimento'  => $request['pessoa']['nascimento'],
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
     * @param  \App\Responsavel  $responsavel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Responsavel $responsavel)
    {
        $responsavel->ativo = false;
        $responsavel->save();
    }
}
