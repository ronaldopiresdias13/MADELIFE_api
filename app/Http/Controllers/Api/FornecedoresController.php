<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Email;
use App\Acesso;
use App\Pessoa;
use App\Endereco;
use App\Telefone;
use App\Fornecedor;
use App\UserAcesso;
use App\PessoaEmail;
use App\PessoaEndereco;
use App\PessoaTelefone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FornecedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = new Fornecedor();

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Fornecedor::where(
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
            $itens = Fornecedor::where('id', 'like', '%');
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

        $fornecedor = null;

        if ($pessoa) {
            $fornecedor = Fornecedor::firstWhere(
                'pessoa_id',
                $pessoa->id,
            );
        }

        if ($fornecedor) {
            return response()->json('Fornecedor já existe!', 400)->header('Content-Type', 'text/plain');
        }

        $fornecedor = Fornecedor::create([
            'tipo'       => $request['tipo'],
            'empresa_id' => $request['empresa_id'],
            'pessoa_id'  => Pessoa::updateOrCreate(
                [
                    'id' => $request['pessoa']['id'],
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

        foreach ($request['pessoa']['telefones'] as $key => $telefone) {
            $pessoa_telefone = PessoaTelefone::firstOrCreate([
                'pessoa_id'   => $fornecedor->pessoa_id,
                'telefone_id' => Telefone::updateOrCreate(
                    [
                        'id' => $telefone['id'],
                    ],
                    // $telefone,
                    [
                        'telefone'  => $telefone['telefone'],
                        'tipo'      => $telefone['tipo'],
                        'descricao' => $telefone['descricao'],
                    ]
                )->id,
            ]);
        }

        foreach ($request['pessoa']['enderecos'] as $key => $endereco) {
            $pessoa_endereco = PessoaEndereco::firstOrCreate([
                'pessoa_id'   => $fornecedor->pessoa_id,
                'endereco_id' => Endereco::updateOrCreate(
                    [
                        'id' => $endereco['id'],
                    ],
                    // $endereco,
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

        foreach ($request['pessoa']['emails'] as $key => $email) {
            $pessoa_email = PessoaEmail::firstOrCreate([
                'pessoa_id' => $fornecedor->pessoa_id,
                'email_id'  => Email::updateOrCreate(
                    [
                        'id'  => $email['id'],
                    ],
                    // $email,
                    [
                        'email'     => $email['email'],
                        'tipo'      => $email['tipo'],
                        'descricao' => $email['descricao'],
                    ]
                )->id,
            ]);
        }

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
                    'pessoa_id'  =>        $fornecedor->pessoa_id,
                ]);
            }

            foreach ($user['acessos'] as $key => $acesso) {
                $user_acesso = UserAcesso::firstOrCreate([
                    'user_id'   => $userexist->id,
                    'acesso_id' => Acesso::firstWhere('id', $acesso)->id,
                ]);
            }
        }

        return $fornecedor;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Fornecedor  $fornecedor
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Fornecedor $fornecedor)
    {
        $iten = $fornecedor;

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
