<?php

namespace App\Http\Controllers\Api;

use App\Models\Cliente;
use App\Models\Email;
use App\Models\Endereco;
use App\Models\Estoque;
use App\Http\Controllers\Controller;
use App\Models\Orcamento;
use App\Models\OrcamentoProduto;
use App\Models\Pessoa;
use App\Models\PessoaEmail;
use App\Models\PessoaEndereco;
use App\Models\PessoaTelefone;
use App\Models\Produto;
use App\Models\Saida;
use App\Models\SaidaProduto;
use App\Models\Telefone;
use App\Models\Tipopessoa;
use App\Models\Venda;
use App\Models\VendaSaida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendasController extends Controller
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
            $itens = Venda::with($with)->where('id', 'like', '%');
        } else {
            $itens = Venda::where('id', 'like', '%');
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
        // return $request;
        DB::transaction(function () use ($request) {
            $orcamento = Orcamento::create([
                'empresa_id'        => $request['orcamento']['empresa_id'],
                'cliente_id'        => $request['orcamento']['cliente_id'],
                'numero'            => $request['orcamento']['numero'],
                'processo'          => $request['orcamento']['processo'],
                'cidade_id'         => $request['orcamento']['cidade']['id'],
                'tipo'              => $request['orcamento']['tipo'],
                'data'              => $request['orcamento']['data'],
                'unidade'           => $request['orcamento']['unidade'],
                'quantidade'        => $request['orcamento']['quantidade'],
                'situacao'          => $request['orcamento']['situacao'],
                'descricao'         => $request['orcamento']['descricao'],
                'valordesconto'     => $request['orcamento']['valordesconto'],
                'valortotalservico' => $request['orcamento']['valortotalservico'] ? $request['orcamento']['valortotalservico'] : 0,
                'valortotalcusto'   => $request['orcamento']['valortotalcusto'] ? $request['orcamento']['valortotalcusto'] : 0,
                'valortotalproduto' => $request['orcamento']['valortotalproduto'] ? $request['orcamento']['valortotalproduto'] : 0,
                'observacao'        => $request['orcamento']['observacao'],
                'status'            => 1
            ]);
            if ($request['orcamento']['produtos']) {
                foreach ($request['orcamento']['produtos'] as $key => $produto) {
                    $orcamento_produto = OrcamentoProduto::create(
                        [
                            'orcamento_id'         => $orcamento->id,
                            'produto_id'           => $produto['produto']['id'],
                            'quantidade'           => $produto['quantidade'],
                            'valorunitario'        => $produto['valor'],
                            'custo'                => $produto['custo'],
                            'subtotal'             => $produto['subtotal'],
                            'subtotalcusto'        => $produto['subtotalcusto'],
                            'valorresultadomensal' => $produto['valorresultadomensal'],
                            'valorcustomensal'     => $produto['valorcustomensal']
                        ]
                    );
                }
            }
            $saida = Saida::create([
                'empresa_id'      => $request['saida']['empresa_id'],
                'data'            => $request['saida']['data'],
                'descricao'       => $request['saida']['descricao'],
                'profissional_id' => $request['saida']['profissional_id']
            ]);
            if ($request['orcamento']['produtos']) {
                foreach ($request['orcamento']['produtos'] as $key => $produto) {
                    $prod = Produto::find($produto["produto"]["id"]);
                    if ($prod->controlelote) {
                        if ($produto['lote']) {
                            $estoque = Estoque::firstWhere('lote', $produto['lote']);
                            if ($estoque) {
                                $atualiza_quantidade_estoque = Estoque::firstWhere('lote', $produto['lote']);
                                $atualiza_quantidade_estoque->quantidade = $atualiza_quantidade_estoque->quantidade - $produto['quantidade'];
                                $atualiza_quantidade_estoque->update();
                            } else {
                                $nova_estoque = Estoque::create([
                                    'produto_id' => $produto['produto']['id'],
                                    'unidade'    => $prod->unidademedida_id,
                                    'quantidade' => $produto['quantidade'],
                                    'lote'       => $produto['lote'],
                                    'validade'   => $produto['validade']
                                    // 'ativo'      => 1
                                ]);
                            }
                            // return $estoque;
                        }
                    }
                    $saida_produto = SaidaProduto::create([
                        'saida_id'      => $saida->id,
                        'produto_id'    => $produto['produto']['id'],
                        'quantidade'    => $produto['quantidade'],
                        'lote'          => $produto['lote'],
                        'valor'         => $produto['valor']
                        // 'ativo'         => 1
                    ]);
                    $prod->quantidadeestoque = $prod->quantidadeestoque - $produto["quantidade"];
                    $prod->update();
                }
            }
            $venda_saida = VendaSaida::create([
                'venda_id' => Venda::create([
                    'orcamento_id' => $orcamento->id,
                    'realizada' => 1,
                    'data' => $request['saida']['data'],
                    'empresa_id' => $request['empresa_id']
                    // 'ativo' => 1
                ])->id,
                'saida_id' => $saida->id
                // 'ativo' => 1
            ]);
        });
        return response()->json([
            'alert' => [
                'title' => 'Salvo!',
                'text' => 'Salvo com sucesso!'
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cadastrarCliente(Request $request)
    {
        // return $request;
        DB::transaction(function () use ($request) {
            $cliente = Cliente::create([
                'tipo'       => $request['tipo'],
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
                'tipo'      => 'Cliente',
                'pessoa_id' => $cliente->pessoa_id
                // 'ativo'     => 1
            ]);

            if ($request['pessoa']['telefone']) {
                // foreach ($request['pessoa']['telefones'] as $key => $telefone) {
                PessoaTelefone::firstOrCreate([
                    'pessoa_id'   => $cliente->pessoa_id,
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
                    'pessoa_id'   => $cliente->pessoa_id,
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
                    'pessoa_id' => $cliente->pessoa_id,
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
    /**
     * Display the specified resource.
     *
     * @param  \App\Venda  $venda
     * @return \Illuminate\Http\Response
     */
    public function show(Venda $venda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Venda  $venda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venda $venda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Venda  $venda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venda $venda)
    {
        //
    }
}
