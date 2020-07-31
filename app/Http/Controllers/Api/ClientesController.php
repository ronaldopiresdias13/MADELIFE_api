<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Email;
use App\Acesso;
use App\Cidade;
use App\Pessoa;
use App\Cliente;
use App\Endereco;
use App\Telefone;
use App\UserAcesso;
use App\PessoaEmail;
use App\PessoaTelefone;
use App\PessoaEndereco;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = Cliente::where('ativo', true);

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                $itens->where(
                    ($where['coluna']) ? $where['coluna'] : 'id',
                    ($where['expressao']) ? $where['expressao'] : 'like',
                    ($where['valor']) ? $where['valor'] : '%'
                );
            }
        }

        if ($request['order']) {
            foreach ($request['order'] as $key => $order) {
                $itens->orderBy(
                    ($order['coluna']) ? $order['coluna'] : 'id',
                    ($order['tipo']) ? $order['tipo'] : 'asc'
                );
            }
        }

        $itens = $itens->get();

        if ($request['adicionais']) {
            foreach ($itens as $key => $iten) {
                foreach ($request['adicionais'] as $key => $adicional) {
                    if (is_string($adicional)) {
                        $iten[$adicional];
                    } else {
                        $iten2 = $iten;
                        foreach ($adicional as $key => $a) {
                            if ($key == 0) {
                                if ($iten[0] == null) {
                                    $iten2 = $iten[$a];
                                } else {
                                    foreach ($iten as $key => $i) {
                                        $i[$a];
                                    }
                                }
                            } else {
                                if ($iten2 != null) {
                                    if ($iten2->count() > 0) {
                                        if ($iten2[0] == null) {
                                            $iten2 = $iten2[$a];
                                        } else {
                                            foreach ($iten2 as $key => $i) {
                                                $i[$a];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $itens;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pessoa = Pessoa::where(
            'cpfcnpj',
            $request['pessoa']['cpfcnpj']
        )->where(
            'empresa_id',
            $request['pessoa']['empresa_id']
        )->first();

        $cliente = null;

        if ($pessoa) {
            $cliente = Cliente::firstWhere(
                'pessoa_id',
                $pessoa->id,
            );
        }

        if ($cliente) {
            return response()->json('Cliente já existe!', 400)->header('Content-Type', 'text/plain');
        }

        DB::transaction(function () use ($request, $pessoa) {
            $cliente = Cliente::create([
                'tipo'       => $request['tipo'],
                'empresa_id' => $request['empresa_id'],
                'pessoa_id'  => Pessoa::updateOrCreate(
                    [
                        'id' => ($request['pessoa']['id'] != '') ? $request['id'] : null,
                    ],
                    [
                        'empresa_id'  => $request['pessoa']['empresa_id'],
                        'nome'        => $request['pessoa']['nome'],
                        'nascimento'  => $request['pessoa']['nascimento'],
                        'tipo'        =>                    'Cliente',
                        'cpfcnpj'     => $request['pessoa']['cpfcnpj'],
                        'rgie'        => $request['pessoa']['rgie'],
                        'observacoes' => $request['pessoa']['observacoes'],
                        'perfil'      => $request['pessoa']['perfil'],
                        'status'      => $request['pessoa']['status'],
                    ]
                )->id,
            ]);

            if ($request['pessoa']['telefones']) {
                foreach ($request['pessoa']['telefones'] as $key => $telefone) {
                    $pessoa_telefone = PessoaTelefone::firstOrCreate([
                        'pessoa_id'   => $cliente->pessoa_id,
                        'telefone_id' => Telefone::firstOrCreate(
                            [
                                'telefone'  => $telefone['telefone'],
                            ]
                        )->id,
                    ]);
                }
            }

            if ($request['pessoa']['enderecos']) {
                foreach ($request['pessoa']['enderecos'] as $key => $endereco) {
                    $pessoa_endereco = PessoaEndereco::firstOrCreate([
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
                    $pessoa_email = PessoaEmail::firstOrCreate([
                        'pessoa_id' => $cliente->pessoa_id,
                        'email_id'  => Email::firstOrCreate(
                            [
                                'email'     => $email['email'],
                            ]
                        )->id,
                    ]);
                }
            }

            // if ($request['pessoa']['user']) {
            //     if ($request['pessoa']['user']['email'] !== '' && $request['pessoa']['user']['email'] !== null) {
            //         $user = new User();
            //         if ($request['pessoa']['user']['password'] !== '' && $request['pessoa']['user']['password'] !== null) {
            //             $user = User::updateOrCreate(
            //                 [
            //                     'email'      =>        $request['pessoa']['user']['email'],
            //                 ],
            //                 [
            //                     // 'empresa_id' =>        $request['empresa_id'],
            //                     'cpfcnpj'    =>        $request['pessoa']['user']['cpfcnpj'],
            //                     'password'   => bcrypt($request['pessoa']['user']['password']),
            //                     'pessoa_id'  =>        $cliente->pessoa_id,
            //                 ]
            //             );
            //         } else {
            //             $user = User::firstOrCreate(
            //                 [
            //                     'email'      =>        $request['pessoa']['user']['email'],
            //                 ],
            //                 [
            //                     // 'empresa_id' =>        $request['empresa_id'],
            //                     'cpfcnpj'    =>        $request['pessoa']['user']['cpfcnpj'],
            //                     'password'   => bcrypt($request['pessoa']['user']['password']),
            //                     'pessoa_id'  =>        $cliente->pessoa_id,
            //                 ]
            //             );
            //         }
            //         if ($request['pessoa']['user']['acessos']) {
            //             foreach ($request['pessoa']['user']['acessos'] as $key => $acesso) {
            //                 $user_acesso = UserAcesso::firstOrCreate([
            //                     'user_id'   => $user->id,
            //                     'acesso_id' => $acesso,
            //                 ]);
            //             }
            //         }
            //     }
            // }
        });

        return response()->json('Cliente atualizado com sucesso!', 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Cliente $cliente)
    {
        $iten = $cliente;

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['adicionais']) {
            foreach ($request['adicionais'] as $key => $adicional) {
                if (is_string($adicional)) {
                    $iten[$adicional];
                } else {
                    $iten2 = $iten;
                    foreach ($adicional as $key => $a) {
                        if ($key == 0) {
                            if ($iten[0] == null) {
                                $iten2 = $iten[$a];
                            } else {
                                foreach ($iten as $key => $i) {
                                    $i[$a];
                                }
                            }
                        } else {
                            if ($iten2 != null) {
                                if ($iten2->count() > 0) {
                                    if ($iten2[0] == null) {
                                        $iten2 = $iten2[$a];
                                    } else {
                                        foreach ($iten2 as $key => $i) {
                                            $i[$a];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $iten;
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
                    'empresa_id'  => $request['pessoa']['empresa_id'],
                    'nome'        => $request['pessoa']['nome'],
                    'nascimento'  => $request['pessoa']['nascimento'],
                    'tipo'        => $request['pessoa']['tipo'],
                    'cpfcnpj'     => $request['pessoa']['cpfcnpj'],
                    'rgie'        => $request['pessoa']['rgie'],
                    'observacoes' => $request['pessoa']['observacoes'],
                    'perfil'      => $request['pessoa']['perfil'],
                    'status'      => $request['pessoa']['status'],
                ]);
            }
            if ($request['pessoa']['telefones']) {
                foreach ($request['pessoa']['telefones'] as $key => $telefone) {
                    $pessoa_telefone = PessoaTelefone::firstOrCreate(
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
                    $pessoa_endereco = PessoaEndereco::updateOrCreate(
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
                    $pessoa_email = PessoaEmail::updateOrCreate(
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
            if ($request['pessoa']['user']) {
                if (
                    $request['pessoa']['user']['email'] !== '' &&
                    $request['pessoa']['user']['email'] !== null
                ) {
                    $user = new User();
                    if (
                        $request['pessoa']['user']['password'] !== '' &&
                        $request['pessoa']['user']['password'] !== null
                    ) {
                        $user = User::updateOrCreate(
                            [
                                'email'      => $request['pessoa']['user']['email'],
                            ],
                            [
                                'cpfcnpj'    => $request['pessoa']['user']['cpfcnpj'],
                                'email'      => $request['pessoa']['user']['email'],
                                'password'   => bcrypt($request['pessoa']['user']['password']),
                                'pessoa_id'  => $pessoa->id,
                                // 'empresa_id' => 1,
                            ]
                        );
                    } else {
                        $user = User::firstOrCreate(
                            [
                                'email'      =>        $request['pessoa']['user']['email'],
                            ],
                            [
                                // 'empresa_id' =>        1,
                                'cpfcnpj'    =>        $request['pessoa']['user']['cpfcnpj'],
                                'password'   => bcrypt($request['pessoa']['user']['password']),
                                'pessoa_id'  =>        $pessoa->id,
                            ]
                        );
                    }
                    if ($request['pessoa']['user']['acessos']) {
                        foreach ($request['pessoa']['user']['acessos'] as $key => $acesso) {
                            $user_acesso = UserAcesso::firstOrCreate([
                                'user_id'   => $user->id,
                                'acesso_id' => Acesso::firstWhere('id', $acesso)->id,
                            ]);
                        }
                    }
                }
            }
        });

        return response()->json('Cliente atualizado com sucesso!', 200)->header('Content-Type', 'text/plain');
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
