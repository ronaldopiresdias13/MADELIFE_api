<?php

namespace App\Http\Controllers\Api;

use App\Email;
use App\Pessoa;
use App\Endereco;
use App\Telefone;
use App\Fornecedor;
use App\PessoaEmail;
use App\PessoaEndereco;
use App\PessoaTelefone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tipopessoa;
use Illuminate\Support\Facades\DB;

class FornecedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $with = [];

        if ($request['adicionais']) {
            foreach ($request['adicionais'] as $key => $adicional) {
                if (is_string($adicional)) {
                    array_push($with, $adicional);
                } else {
                    $filho = '';
                    foreach ($adicional as $key => $a) {
                        if ($key == 0) {
                            $filho = $a;
                        } else {
                            $filho = $filho . '.' . $a;
                        }
                    }
                    array_push($with, $filho);
                }
            }
            $itens = Fornecedor::with($with)->where('ativo', true);
        } else {
            $itens = Fornecedor::where('ativo', true);
        }

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                // if ($key == 0) {
                //     $itens = Fornecedor::where(
                //         ($where['coluna']) ? $where['coluna'] : 'id',
                //         ($where['expressao']) ? $where['expressao'] : 'like',
                //         ($where['valor']) ? $where['valor'] : '%'
                //     );
                // } else {
                $itens->where(
                    ($where['coluna']) ? $where['coluna'] : 'id',
                    ($where['expressao']) ? $where['expressao'] : 'like',
                    ($where['valor']) ? $where['valor'] : '%'
                );
                // }
            }
            // } else {
            //     $itens = Fornecedor::where('id', 'like', '%');
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
        $fornecedor = Fornecedor::create([
            'empresa_id' => $request['empresa_id'],
            'pessoa_id'  => Pessoa::create(
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
        $tipopessoa = Tipopessoa::create([
            'tipo'      => 'Fornecedor',
            'pessoa_id' => $fornecedor->pessoa_id,
            'ativo'     => 1
        ]);
        foreach ($request['pessoa']['telefones'] as $key => $telefone) {
            $pessoa_telefone = PessoaTelefone::firstOrCreate([
                'pessoa_id'   => $fornecedor->pessoa_id,
                'telefone_id' => Telefone::firstOrCreate(
                    [
                        'telefone'  => $telefone['telefone'],
                    ]
                )->id,
                'tipo'      => $telefone['pivot']['tipo'],
                'descricao' => $telefone['pivot']['descricao'],
            ]);
        }
        foreach ($request['pessoa']['emails'] as $key => $email) {
            foreach ($request['pessoa']['emails'] as $key => $email) {
                $pessoa_email = PessoaEmail::firstOrCreate([
                    'pessoa_id' => $fornecedor->pessoa_id,
                    'email_id'  => Email::firstOrCreate(
                        [
                            'email' => $email['email'],
                        ]
                    )->id,
                    'tipo'      => $email['pivot']['tipo'],
                    'descricao' => $email['pivot']['descricao'],
                ]);
            }
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
     * @param  \App\Fornecedor  $fornecedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fornecedor $fornecedor)
    {
        DB::transaction(function () use ($request) {
            $pessoa = Pessoa::find($request['pessoa']['id']);
            if ($pessoa) {
                $pessoa->update([
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
        });

        return response()->json('Fornecedor atualizado com sucesso!', 200)->header('Content-Type', 'text/plain');




        // // return $request;
        // $pessoa = Pessoa::find($request['pessoa']['id']);
        // $pessoa->nome = $request['pessoa']['nome'];
        // $pessoa->nascimento = $request['pessoa']['nascimento'];
        // $pessoa->cpfcnpj = $request['pessoa']['cpfcnpj'];
        // $pessoa->rgie = $request['pessoa']['rgie'];
        // $pessoa->observacoes = $request['pessoa']['observacoes'];
        // $pessoa->perfil = $request['pessoa']['perfil'];
        // $pessoa->status = $request['pessoa']['status'];
        // $pessoa->update();

        // foreach ($request['pessoa']['telefones'] as $key => $telefone) {
        //     if (!$telefone['id']) {
        //         $pessoa_telefone = PessoaTelefone::firstOrCreate([
        //             'pessoa_id'   => $pessoa->id,
        //             'telefone_id' => Telefone::firstOrCreate(
        //                 [
        //                     'telefone'  => $telefone['telefone'],
        //                 ]
        //             )->id,
        //             'tipo'      => $telefone['tipo'],
        //             'descricao' => $telefone['descricao'],
        //         ]);
        //     } else {
        //         $pessoa_telefone = PessoaTelefone::where('pessoa_id', $telefone['pivot']['pessoa_id'])
        //             ->where('telefone_id', $telefone['pivot']['telefone_id'])
        //             ->update([
        //                 'tipo' => $telefone['pivot']['tipo'],
        //                 'descricao' => $telefone['pivot']['descricao']
        //             ]);
        //         $phone = Telefone::find($telefone['id']);
        //         $phone->telefone = $telefone['telefone'];
        //         $phone->update();
        //     }
        // }
        // foreach ($request['pessoa']['emails'] as $key => $email) {
        //     if (!$email['id']) {
        //         $pessoa_email = PessoaEmail::firstOrCreate([
        //             'pessoa_id' => $fornecedor->pessoa_id,
        //             'email_id'  => Email::firstOrCreate(
        //                 [
        //                     'email' => $email['email'],
        //                 ]
        //             )->id,
        //             'tipo'      => $email['tipo'],
        //             'descricao' => $email['descricao'],
        //         ]);
        //     } else {
        //         $pessoa_email = PessoaEmail::where('pessoa_id', $email['pivot']['pessoa_id'])
        //             ->where('email_id', $email['pivot']['email_id'])
        //             ->update([
        //                 'tipo' =>      $email['pivot']['tipo'],
        //                 'descricao' => $email['pivot']['descricao']
        //             ]);
        //         $mail = Email::find($email['id']);
        //         $mail->email = $email['email'];
        //         $mail->update();
        //     }
        // }
        // foreach ($request['pessoa']['enderecos'] as $key => $endereco) {
        //     if (!$endereco['id']) {
        //         $pessoa_endereco = PessoaEndereco::firstOrCreate([
        //             'pessoa_id'   => $fornecedor->pessoa_id,
        //             'endereco_id' => Endereco::updateOrCreate(
        //                 [
        //                     'id' => $endereco['id'],
        //                 ],
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
        //     } else {
        //         $address = Endereco::find($endereco['id']);
        //         $address->cep         = $endereco['cep'];
        //         $address->cidade_id   = $endereco['cidade_id'];
        //         $address->rua         = $endereco['rua'];
        //         $address->bairro      = $endereco['bairro'];
        //         $address->numero      = $endereco['numero'];
        //         $address->complemento = $endereco['complemento'];
        //         $address->tipo        = $endereco['tipo'];
        //         $address->descricao   = $endereco['descricao'];
        //         $address->update();
        //     }
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fornecedor  $fornecedor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fornecedor $fornecedor)
    {
        $fornecedor->ativo = false;
        $fornecedor->save();
    }
}
