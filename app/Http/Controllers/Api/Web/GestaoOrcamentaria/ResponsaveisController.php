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
    public function index(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        return Responsavel::with(['pessoa.emails', 'pessoa.telefones', 'pessoa.enderecos.cidade', 'pacientes.pessoa', 'pessoa.user.acessos'])
            ->where('empresa_id', $empresa_id)
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
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
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
            $responsavel = Responsavel::where(
                'empresa_id',
                $empresa_id,
            )
                ->where(
                    'pessoa_id',
                    $pessoa->id,
                )
                ->first();
        }

        if ($responsavel) {
            return response()->json('Responsável já existe!', 400)->header('Content-Type', 'text/plain');
        }

        DB::transaction(function () use ($request, $pessoa, $empresa_id) {
            $responsavel = Responsavel::create([
                'empresa_id' => $empresa_id,
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
                    if ($telefone['telefone']) {
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
            }

            if ($request['pessoa']['enderecos']) {
                foreach ($request['pessoa']['enderecos'] as $key => $endereco) {
                    PessoaEndereco::firstOrCreate([
                        'pessoa_id'   => $responsavel->pessoa_id,
                        'endereco_id' => Endereco::firstOrCreate(
                            [
                                'cep'         => $endereco['cep'],
                                'cidade_id'   => $endereco['cidade']['id'],
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
                    if ($email['email']) {
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
            }
        });

        return response()->json([
            'alert' => [
                'title' => 'Ok!',
                'text' => 'Responsável cadastrado com sucesso!'
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
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
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;

        DB::transaction(function () use ($request, $responsavel, $empresa_id) {
            $responsavel->update([
                'parentesco' => $request['parentesco'],
                'empresa_id' => $empresa_id,
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

            foreach ($pessoa->telefones as $key => $telefone) {
                $pessoatelefone = Pessoatelefone::find($telefone->pivot->id);
                $pessoatelefone->delete();
            }

            if ($request['pessoa']['telefones']) {
                foreach ($request['pessoa']['telefones'] as $key => $telefone) {
                    if ($telefone['telefone']) {
                        PessoaTelefone::updateOrCreate(
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
            }
            if ($request['pessoa']['enderecos']) {
                foreach ($request['pessoa']['enderecos'] as $key => $endereco) {
                    PessoaEndereco::updateOrCreate(
                        [
                            'pessoa_id'   => $pessoa->id,
                            'endereco_id' => Endereco::firstOrCreate(
                                [
                                    'cep'         => $endereco['cep'],
                                    'cidade_id'   => $endereco['cidade']['id'],
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

            foreach ($pessoa->emails as $key => $email) {
                $pessoaemail = Pessoaemail::find($email->pivot->id);
                $pessoaemail->delete();
            }

            if ($request['pessoa']['emails']) {
                foreach ($request['pessoa']['emails'] as $key => $email) {
                    if ($email['email']) {
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
    public function responsaveisPage(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $result = Responsavel::with(['pessoa.emails', 'pessoa.telefones', 'pessoa.enderecos.cidade', 'pacientes.pessoa', 'pessoa.user.acessos'])
            ->where('empresa_id', $empresa_id)
            ->where('ativo', true)
            // ->get();
          
        ->paginate($request['per_page'] ? $request['per_page'] : 15);

        if (env("APP_ENV", 'production') == 'production') {
            return $result->withPath(str_replace('http:', 'https:', $result->path()));
        } else {
            return $result;
        }
    }
}
