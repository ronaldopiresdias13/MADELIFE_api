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
        $itens = new Cliente();

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Cliente::where(
                        ($where['coluna']) ? $where['coluna'] : 'id',
                        ($where['expressao']) ? $where['expressao'] : 'like',
                        ($where['valor']) ? $where['valor'] : '%'
                    );
                } else {
                    $itens->where(
                        ($where['coluna']) ? $where['coluna'] : 'id',
                        ($where['expressao']) ? $where['expressao'] : 'like',
                        ($where['valor']) ? $where['valor'] : '%'
                    );
                }
            }
        } else {
            $itens = Cliente::where('id', 'like', '%');
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

        if ($request['pessoa']['users']) {
            foreach ($request['pessoa']['users'] as $key => $user) {
                $usercpf = User::firstWhere(
                    'cpfcnpj',
                    $user['cpfcnpj'],
                );
                $useremail = User::firstWhere(
                    'email',
                    $user['email'],
                );

                $userexist = null;

                if ($usercpf) {
                    $userexist = $usercpf;
                } elseif ($useremail) {
                    $userexist = $useremail;
                }

                if (($pessoa == null) || ($pessoa != null && ($userexist == null))) {
                    $userexist = User::create([
                        'empresa_id' =>        $user['empresa_id'],
                        'cpfcnpj'    =>        $user['cpfcnpj'],
                        'email'      =>        $user['email'],
                        'password'   => bcrypt($user['password']),
                        'pessoa_id'  =>        $cliente->pessoa_id,
                    ]);
                }

                foreach ($user['acessos'] as $key => $acesso) {
                    $user_acesso = UserAcesso::firstOrCreate([
                        'user_id'   => $userexist->id,
                        'acesso_id' => Acesso::firstWhere('id', $acesso)->id,
                    ]);
                }
            }
        }

        return $cliente;
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
        // dd($cliente);
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
                $user = User::updateOrCreate(
                    [
                        'id' => $request['pessoa']['user']['id'],
                    ],
                    [
                        'cpfcnpj'    => $request['pessoa']['user']['cpfcnpj'],
                        'email'      => $request['pessoa']['user']['email'],
                        'password'   => $request['pessoa']['user']['password'],
                        'pessoa_id'  => $pessoa->id,
                        'empresa_id' => $request['pessoa']['user']['empresa_id'],
                    ]
                );
                if ($request['pessoa']['user']['acessos']) {
                    foreach ($request['pessoa']['user']['acessos'] as $key => $acesso) {
                        $user_acesso = UserAcesso::firstOrCreate([
                            'user_id'   => $user->id,
                            'acesso_id' => Acesso::firstWhere('id', $acesso)->id,
                        ]);
                    }
                }
            }
        });

        return response()->json('Cliente atualizado com sucesso!', 200)->header('Content-Type', 'text/plain');

        // dd(null);

        // $pessoa = Pessoa::find($request['pessoa']['id']);
        // $pessoa->nome = $request['pessoa']['nome'];
        // $pessoa->nascimento = $request['pessoa']['nascimento'];
        // $pessoa->cpfcnpj = $request['pessoa']['cpfcnpj'];
        // $pessoa->rgie = $request['pessoa']['rgie'];
        // $pessoa->observacoes = $request['pessoa']['observacoes'];
        // $pessoa->perfil = $request['pessoa']['perfil'];
        // $pessoa->status = $request['pessoa']['status'];
        // $pessoa->update();

        // if ($request['pessoa']['telefones']) {
        //     foreach ($request['pessoa']['telefones'] as $key => $telefone) {
        //         if (!$telefone['id']) {
        //             $pessoa_telefone = PessoaTelefone::firstOrCreate([
        //                 'pessoa_id'   => $pessoa->id,
        //                 'telefone_id' => Telefone::firstOrCreate(
        //                     [
        //                         'telefone'  => $telefone['telefone'],
        //                     ]
        //                 )->id,
        //                 'tipo'      => $telefone['tipo'],
        //                 'descricao' => $telefone['descricao'],
        //             ]);
        //         } else {
        //             $pessoa_telefone = PessoaTelefone::where('pessoa_id', $telefone['pivot']['pessoa_id'])
        //                 ->where('telefone_id', $telefone['pivot']['telefone_id'])
        //                 ->update([
        //                     'tipo' => $telefone['pivot']['tipo'],
        //                     'descricao' => $telefone['pivot']['descricao']
        //                 ]);
        //             $phone = Telefone::find($telefone['id']);
        //             $phone->telefone = $telefone['telefone'];
        //             $phone->update();
        //         }
        //     }
        // }


        // if ($request['pessoa']['emails']) {
        //     foreach ($request['pessoa']['emails'] as $key => $email) {
        //         if (!$email['id']) {
        //             $pessoa_email = PessoaEmail::firstOrCreate([
        //                 'pessoa_id' => $cliente->pessoa_id,
        //                 'email_id'  => Email::firstOrCreate(
        //                     [
        //                         'email' => $email['email'],
        //                     ]
        //                 )->id,
        //                 'tipo'      => $email['tipo'],
        //                 'descricao' => $email['descricao'],
        //             ]);
        //         } else {
        //             $pessoa_email = PessoaEmail::where('pessoa_id', $email['pivot']['pessoa_id'])
        //                 ->where('email_id', $email['pivot']['email_id'])
        //                 ->update([
        //                     'tipo' =>      $email['pivot']['tipo'],
        //                     'descricao' => $email['pivot']['descricao']
        //                 ]);
        //             $mail = Email::find($email['id']);
        //             $mail->email = $email['email'];
        //             $mail->update();
        //         }
        //     }
        // }

        // if ($request['pessoa']['enderecos']) {
        //     foreach ($request['pessoa']['enderecos'] as $key => $endereco) {
        //         if (!$endereco['id']) {
        //             $pessoa_endereco = PessoaEndereco::firstOrCreate([
        //                 'pessoa_id'   => $cliente->pessoa_id,
        //                 'endereco_id' => Endereco::updateOrCreate(
        //                     [
        //                         'id' => $endereco['id'],
        //                     ],
        //                     [
        //                         'cep'         => $endereco['cep'],
        //                         'cidade_id'   => $endereco['cidade_id'],
        //                         'rua'         => $endereco['rua'],
        //                         'bairro'      => $endereco['bairro'],
        //                         'numero'      => $endereco['numero'],
        //                         'complemento' => $endereco['complemento'],
        //                         'tipo'        => $endereco['tipo'],
        //                         'descricao'   => $endereco['descricao'],
        //                     ]
        //                 )->id,
        //             ]);
        //         } else {
        //             $address = Endereco::find($endereco['id']);
        //             $address->cep         = $endereco['cep'];
        //             $address->cidade_id   = $endereco['cidade_id'];
        //             $address->rua         = $endereco['rua'];
        //             $address->bairro      = $endereco['bairro'];
        //             $address->numero      = $endereco['numero'];
        //             $address->complemento = $endereco['complemento'];
        //             $address->tipo        = $endereco['tipo'];
        //             $address->descricao   = $endereco['descricao'];
        //             $address->update();
        //         }
        //     }
        // }
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

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \App\Cliente  $cliente
    //  * @return \Illuminate\Http\Response
    //  */
    // public function meuspassientes(Cliente $cliente)
    // {
    //     $escalas = Escala::where('prestador_id', $prestador->id)
    //         ->join('ordemservicos', 'ordemservicos.id', '=', 'escalas.ordemservico_id')
    //         ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
    //         ->join('homecares', 'homecares.orcamento_id', '=', 'orcamentos.id')
    //         ->select('homecares.nome')
    //         ->groupBy('homecares.nome')
    //         ->orderBy('homecares.nome')
    //         ->limit(100)
    //         ->get();
    //     return $escalas;
    // }
}
