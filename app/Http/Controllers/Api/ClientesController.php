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

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = null;

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }
        
        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Cliente::where(
                        ($where['coluna'   ])? $where['coluna'   ] : 'id'  ,
                        ($where['expressao'])? $where['expressao'] : 'like',
                        ($where['valor'    ])? $where['valor'    ] : '%'
                    );
                } else {
                    $itens->where(
                        ($where['coluna'   ])? $where['coluna'   ] : 'id',
                        ($where['expressao'])? $where['expressao'] : 'like',
                        ($where['valor'    ])? $where['valor'    ] : '%'
                    );
                }
            }
        } else {
            $itens = Cliente::where('id', 'like', '%');
        }

        if ($request['order']) {
            foreach ($request['order'] as $key => $order) {
                $itens->orderBy(
                    ($order['coluna'])? $order['coluna'] : 'id',
                    ($order['tipo'  ])? $order['tipo'  ] : 'asc'
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
                                }
                                else {
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
            'cpfcnpj', $request['pessoa']['cpfcnpj']
        )->where(
            'empresa_id', $request['pessoa']['empresa_id']
        )->first();

        $cliente = null;

        if ($pessoa) {
            $cliente = Cliente::firstWhere(
                'pessoa_id', $pessoa->id,
            );
        }

        if ($cliente) {
            return response()->json('Cliente já existe!', 400)->header('Content-Type', 'text/plain');
        }

        $cliente = Cliente::create([
            'tipo'       => $request['tipo'      ],
            'empresa_id' => $request['empresa_id'],
            'pessoa_id'  => Pessoa::updateOrCreate(
                [
                    'id' => ($request['pessoa']['id'] != '')? $request['id'] : null,
                ],
                [
                    'empresa_id'  => $request['pessoa']['empresa_id' ],
                    'nome'        => $request['pessoa']['nome'       ],
                    'nascimento'  => $request['pessoa']['nascimento' ],
                    'tipo'        =>                    'Cliente'     ,
                    'cpfcnpj'     => $request['pessoa']['cpfcnpj'    ],
                    'rgie'        => $request['pessoa']['rgie'       ],
                    'observacoes' => $request['pessoa']['observacoes'],
                    'perfil'      => $request['pessoa']['perfil'     ],
                    'status'      => $request['pessoa']['status'     ],
                ]
            )->id,
        ]);

        if ($request['pessoa']['telefones']) {
            foreach ($request['pessoa']['telefones'] as $key => $telefone) {
                $pessoa_telefone = PessoaTelefone::firstOrCreate([
                    'pessoa_id'   => $cliente->pessoa_id,
                    'telefone_id' => Telefone::firstOrCreate(
                        [
                            'telefone'  => $telefone['telefone' ],
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
                            'cep'         => $endereco['cep'        ],
                            'cidade_id'   => $endereco['cidade_id'  ],
                            'rua'         => $endereco['rua'        ],
                            'bairro'      => $endereco['bairro'     ],
                            'numero'      => $endereco['numero'     ],
                            'complemento' => $endereco['complemento'],
                            'tipo'        => $endereco['tipo'       ],
                            'descricao'   => $endereco['descricao'  ],
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
                            'email'     => $email['email'    ],
                        ]
                    )->id,
                ]);
            }
        }

        if ($request['pessoa']['users']) {
            foreach ($request['pessoa']['users'] as $key => $user) {
                $usercpf = User::firstWhere(
                    'cpfcnpj', $user['cpfcnpj'],
                );
                $useremail = User::firstWhere(
                    'email', $user['email'],
                );
        
                $userexist = null;
        
                if ($usercpf) {
                    $userexist = $usercpf;
                } elseif ($useremail) {
                    $userexist = $useremail;
                }
        
                if (($pessoa == null) || ($pessoa != null && ($userexist == null))) {
                    $userexist = User::create([
                        'empresa_id' =>        $user['empresa_id'] ,
                        'cpfcnpj'    =>        $user['cpfcnpj'   ] ,
                        'email'      =>        $user['email'     ] ,
                        'password'   => bcrypt($user['password'  ]),
                        'pessoa_id'  =>        $cliente->pessoa_id ,
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
                            }
                            else {
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
        $cliente = Cliente::firstOrCreate([
            'pessoa_id' => Pessoa::firstOrCreate(
                [
                    'cpfcnpj' => $request['pessoa']['cpfcnpj'],
                // ],
                // [
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
