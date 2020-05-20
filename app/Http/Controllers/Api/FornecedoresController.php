<?php

namespace App\Http\Controllers\Api;

use App\Fornecedor;
use App\Pessoa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FornecedoresController extends Controller
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
                    $itens = Fornecedor::where(
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
            $itens = Fornecedor::where('id', 'like', '%');
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
                    if (is_string($adic)) {
                        $iten[$adic];
                    } else {
                        switch (count($adic)) {
                            case 1:
                                $iten[$adic[0]];
                                break;
                            
                            case 2:
                                $iten[$adic[0]][$adic[1]];
                                break;
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
        //
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
            foreach ($request['adicionais'] as $key => $adic) {
                if (is_string($adic)) {
                    $iten[$adic];
                } else {
                    switch (count($adic)) {
                        case 1:
                            $iten[$adic[0]];
                            break;
                        
                        case 2:
                            $iten[$adic[0]][$adic[1]];
                            break;
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
