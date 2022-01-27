<?php

namespace App\Http\Controllers\Api;

use App\Models\Email;
use App\Models\Endereco;
use App\Models\Entrada;
use App\Models\EntradaProduto;
use App\Models\Estoque;
use App\Models\Fornecedor;
use App\Http\Controllers\Controller;
use App\Models\Pessoa;
use App\Models\PessoaEmail;
use App\Models\PessoaEndereco;
use App\Models\PessoaTelefone;
use App\Models\Produto;
use App\Models\Telefone;
use App\Models\Tipopessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntradasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
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
            $itens = Entrada::with($with)->where('ativo', true);
        } else {
            $itens = Entrada::where('ativo', true);
        }

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
        DB::transaction(function () use ($request) {
            $entrada = Entrada::create([
                'empresa_id'      => $request['empresa_id'],
                'data'            => $request['data'],
                'numeronota'      => $request['numeronota'],
                'fornecedor_id'   => $request['fornecedor_id']
            ]);


            foreach ($request['produtos'] as $key => $produto) {
                $prod = Produto::find($produto['produto_id']);
                if ($prod->controlelote) {
                    if ($produto['lote']) {
                        $estoque = Estoque::firstWhere('lote', $produto['lote']);
                        if ($estoque) {
                            $atualiza_quantidade_estoque = Estoque::firstWhere('lote', $produto['lote']);
                            $atualiza_quantidade_estoque->quantidade = $atualiza_quantidade_estoque->quantidade + $produto['quantidade'];
                            $atualiza_quantidade_estoque->update();
                        } else {
                            $nova_estoque = Estoque::create([
                                'produto_id' => $produto['produto_id'],
                                'unidade'    => $prod->unidademedida_id,
                                'quantidade' => $produto['quantidade'],
                                'lote'       => $produto['lote'],
                                'validade'   => $produto['validade'],
                                'ativo'      => 1
                            ]);
                        }
                        // return $estoque;
                    }
                }
                $entrada_produto = EntradaProduto::create([
                    'entrada_id'    => $entrada->id,
                    'produto_id'    => $produto['produto_id'],
                    'quantidade'    => $produto['quantidade'],
                    'lote'          => $produto['lote'],
                    'validade'      => $produto['validade'],
                    'valor'         => $produto['valor'],
                    'ativo'         => 1
                ]);
                $prod->quantidadeestoque = $prod->quantidadeestoque + $produto["quantidade"];
                $prod->update();
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Entrada $entrada)
    {
        $iten = $entrada;

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
     * @param  \App\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Entrada $entrada)
    {
        DB::transaction(function () use ($request, $entrada) {
            $entrada->update($request->all());
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Entrada  $entrada
     * @return \Illuminate\Http\Response
     */
    public function destroy(Entrada $entrada)
    {
        $entrada->ativo = false;
        $entrada->save();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cadastrarFornecedor(Request $request)
    {
        // return $request;
        DB::transaction(function () use ($request) {
            $fornecedor = Fornecedor::create([
                'ativo'       => 1,
                'empresa_id' => $request['empresa_id'],
                'pessoa_id'  => Pessoa::updateOrCreate(
                    [
                        'id' => ($request['pessoa']['id'] != '') ? $request['id'] : null,
                    ],
                    [
                        'nome'        => $request['pessoa']['nome'],
                        'nascimento'  => $request['pessoa']['nascimento'],

                        'cpfcnpj'     => $request['pessoa']['cpfcnpj'],
                        'rgie'        => $request['pessoa']['rgie'],
                        'observacoes' => $request['pessoa']['observacoes'],
                        'perfil'      => $request['pessoa']['perfil'],
                        'status'      => true,
                    ]
                )->id,
            ]);
            Tipopessoa::create([
                'tipo'      => 'Fornecedor',
                'pessoa_id' => $fornecedor->pessoa_id,
                'ativo'     => 1
            ]);

            if ($request['pessoa']['telefone']) {
                // foreach ($request['pessoa']['telefones'] as $key => $telefone) {
                PessoaTelefone::firstOrCreate([
                    'pessoa_id'   => $fornecedor->pessoa_id,
                    'telefone_id' => Telefone::firstOrCreate(
                        [
                            'telefone'  => $request['pessoa']['telefone'],
                        ]
                    )->id,
                ]);
                // }
            }

            if (
                $request['pessoa']['endereco']['cep'] || $request['pessoa']['endereco']['cidade_id']
                || $request['pessoa']['endereco']['rua'] || $request['pessoa']['endereco']['bairro']
            ) {
                // foreach ($request['pessoa']['enderecos'] as $key => $endereco) {
                PessoaEndereco::firstOrCreate([
                    'pessoa_id'   => $fornecedor->pessoa_id,
                    'endereco_id' => Endereco::firstOrCreate(
                        [
                            'cep'         => $request['pessoa']['endereco']['cep'],
                            'cidade_id'   => $request['pessoa']['endereco']['cidade_id'],
                            'rua'         => $request['pessoa']['endereco']['rua'],
                            'bairro'      => $request['pessoa']['endereco']['bairro'],
                            'numero'      => $request['pessoa']['endereco']['numero'],
                            'complemento' => $request['pessoa']['endereco']['complemento'],
                            'tipo'        => $request['pessoa']['endereco']['tipo'],
                            'descricao'   => $request['pessoa']['endereco']['descricao'],
                        ]
                    )->id,
                ]);
                // }
            }

            if ($request['pessoa']['email']) {
                // foreach ($request['pessoa']['emails'] as $key => $email) {
                PessoaEmail::firstOrCreate([
                    'pessoa_id' => $fornecedor->pessoa_id,
                    'email_id'  => Email::firstOrCreate(
                        [
                            'email'     => $request['pessoa']['email'],
                        ]
                    )->id,
                ]);
                // }
            }
        });
        return response()->json([
            'alert' => [
                'title' => 'Salvo!',
                'text' => 'Salvo com sucesso!'
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }
}
