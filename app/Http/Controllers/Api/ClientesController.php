<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Email;
use App\Cidade;
use App\Pessoa;
use App\Cliente;
use App\Endereco;
use App\Telefone;
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
            $itens = Cliente::all();
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
                foreach ($request['adicionais'] as $key => $adic) {
                    $iten[$adic];
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
        // return $request;

        $pessoa = Pessoa::where(
            'cpfcnpj', $request['cpfcnpj']
        )->where(
            'empresa_id', $request['empresa_id']
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
            'tipo'      => $request['tipo'],
            'pessoa_id' => Pessoa::updateOrCreate(
                [
                    'cpfcnpj'     => $request['cpfcnpj'    ],
                ],
                [
                    'empresa_id'  => $request['empresa_id' ],
                    'nome'        => $request['nome'       ],
                    'nascimento'  => $request['nascimento' ],
                    'tipo'        =>          'Cliente'     ,
                    'rgie'        => $request['rgie'       ],
                    'observacoes' => $request['observacoes'],
                    'perfil'      => $request['perfil'     ],
                    'status'      => $request['status'     ],
                ]
            )->id,
            'empresa_id' => $request['empresa_id'],
        ]);

        $pessoa_endereco = PessoaEndereco::firstOrCreate([
            'pessoa_id'   => $cliente->pessoa_id,
            'endereco_id' => Endereco::updateOrCreate(
                [
                    'id' => $request['endereco']['id'],
                ],
                [
                    'cep'       => $request['endereco']['cep'],
                    'cidade_id' => Cidade::firstOrCreate(
                        [
                            'nome' => $request['endereco']['cidade'],
                            'uf'   => $request['endereco']['uf'    ],
                        ]
                    )->id,
                    'rua'         => $request['endereco']['rua'        ],
                    'bairro'      => $request['endereco']['bairro'     ],
                    'numero'      => $request['endereco']['numero'     ],
                    'complemento' => $request['endereco']['complemento'],
                    'tipo'        => $request['endereco']['tipo'       ],
                    'descricao'   => $request['endereco']['descricao'  ],
                ]
            )->id,
        ]);

        $usercpf = User::firstWhere(
            'cpfcnpj', $request['cpfcnpj'],
        );
        $useremail = User::firstWhere(
            'email', $request['acesso']['email'],
        );

        $user = null;

        if ($usercpf) {
            $user = $usercpf;
        } elseif ($useremail) {
            $user = $useremail;
        }

        if (($pessoa == null) || ($pessoa != null && ($user == null))) {
            $user = User::create([
                'empresa_id' => $request['empresa_id'],
                'cpfcnpj'    => $request['cpfcnpj'],
                'email'      => $request['acesso']['email'],
                'password'   => bcrypt($request['acesso']['password']),
                'pessoa_id'  => $cliente->pessoa_id,
            ]);
        }

        foreach ($request['telefones'] as $key => $telefone) {
            $pessoatelefone = PessoaTelefone::firstOrCreate([
                'pessoa_id'   => $cliente->pessoa_id,
                'telefone_id' => Telefone::updateOrCreate(
                    [
                        'id'  => $telefone['id'],
                    ],
                    [
                        'telefone'  => $telefone['telefone' ],
                        'tipo'      => $telefone['tipo'     ],
                        'descricao' => $telefone['descricao'],
                    ]
                )->id,
            ]);
        }

        foreach ($request['contato'] as $key => $email) {
            $pessoaemail = PessoaEmail::firstOrCreate([
                'pessoa_id' => $cliente->pessoa_id,
                'email_id'  => Email::updateOrCreate(
                    [
                        'id'  => $email['id'],
                    ],
                    [
                        'email'     => $email['email' ],
                        'tipo'      => $email['tipo'     ],
                        'descricao' => $email['descricao'],
                    ]
                )->id,
            ]);
        }
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
        dd($request);
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
