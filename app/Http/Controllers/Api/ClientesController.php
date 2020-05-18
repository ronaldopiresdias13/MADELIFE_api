<?php

namespace App\Http\Controllers\Api;

use App\Cliente;
use App\Pessoa;
use App\PessoaEndereco;
use App\PessoaTelefone;
use App\Endereco;
use App\User;
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
        $itens = new Cliente;
        
        if ($request->where) {
            foreach ($request->where as $key => $where) {
                $itens->where(
                    ($where['coluna'   ])? $where['coluna'   ] : 'id',
                    ($where['expressao'])? $where['expressao'] : 'like',
                    ($where['valor'    ])? $where['valor'    ] : '%'
                );
            }
        }

        if ($request->order) {
            foreach ($request->order as $key => $order) {
                $itens->orderBy(
                    ($order['coluna'])? $order['coluna'] : 'id',
                    ($order['tipo'  ])? $order['tipo'  ] : 'asc'
                );
            }
        }
        
        $itens = $itens->get();
        
        if ($request->adicionais) {
            foreach ($itens as $key => $iten) {
                foreach ($request->adicionais as $key => $adic) {
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
        // $cliente = new Cliente;
        // $cliente->pessoa = $request->pessoa;
        // $cliente->save();

        // $pessoa = Pessoa::firstWhere('cpfcnpj', '=', $request['cpfcnpj']);
        // dd($pessoa);

        $pessoa = Pessoa::where(
            'cpfcnpj', $request['cpfcnpj']
        )->where(
            'empresa_id', $request['empresa_id']
        )->first();

        $cliente = false;

        if ($pessoa) {
            $cliente = Cliente::firstWhere(
                'pessoa_id', $pessoa->id,
            );
        }

        if ($cliente) {
            return response()->json('Cliente já existe!', 400)->header('Content-Type', 'text/plain');
        }

        // $usercpf = User::firstWhere(
        //     'cpfcnpj', $request['cpfcnpj'],
        // );
        // $useremail = User::firstWhere(
        //     'email', $request['acesso']['email'],
        // );

        // dd($useremail);
        dd('Parou!!!');

        $cliente = Cliente::Create([
            'tipo'      => $request['tipo'],
            'perfil'    => $request['perfil'],
            'pessoa_id' => ($pessoa) ? $pessoa->id : Pessoa::Create(
                [
                    'empresa_id'  => $request['empresa_id' ],
                    'nome'        => $request['nome'       ],
                    'nascimento'  => $request['nascimento' ],
                    'tipo'        =>          'Cliente'     ,
                    'cpfcnpj'     => $request['cpfcnpj'    ],
                    'rgie'        => $request['rgie'       ],
                    'observacoes' => $request['observacoes'],
                    'status'      => $request['status'     ],
                ]
            )->id,
            'empresa_id' => $request['empresa_id'],
        ]);

        $pessoa_endereco = PessoaEndereco::create([
            'pessoa_id'   => $cliente->pessoa_id,
            'endereco_id' => Endereco::create(
                [
                    'cep'       => $request['endereco']['cep'],
                    'cidade_id' => Endereco::first(
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
                ]
            )->id,
        ]);

        $usercpf = User::firstWhere(
            'cpfcnpj', $request['cpfcnpj'],
        );
        $useremail = User::firstWhere(
            'email', $request['acesso']['email'],
        );

        $user = new User;

        if (($pessoa == null) || (($usercpf == null && $useremail == null) && $pessoa != null)) {
            $user = User::create([
                'cpfcnpj'   => $request['cpfcnpj'],
                'email'     => $request['acesso']['email'],
                'pessoa_id' => $cliente->pessoa_id,
                'password'  => bcrypt($request['acesso']['password']),
            ]);
        }

        // foreach ($request['telefones'] as $key => $telefone) {
        //     PessoaTelefone::create(
        //         ['pessoa' => 'Flight 10'],
        //         ['delayed' => 1]
        //     );
        // }
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
