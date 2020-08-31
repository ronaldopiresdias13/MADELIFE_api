<?php

namespace App\Http\Controllers\Api;

use App\Aph;
use App\Email;
use App\Evento;
use App\Remocao;
use App\AphEmail;
use App\Telefone;
use App\Homecare;
use App\Orcamento;
use App\AphTelefone;
use App\EventoEmail;
use App\RemocaoEmail;
use App\EventoTelefone;
use App\Orcamentocusto;
use App\RemocaoTelefone;
use App\OrcamentoServico;
use App\OrcamentoProduto;
use App\Historicoorcamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrcamentosController extends Controller
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
            $itens = Orcamento::with($with)->where('ativo', true);
        } else {
            $itens = Orcamento::where('ativo', true);
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
                foreach ($request['adicionais'] as $key => $adicional) { // Percorrer os adicionais
                    if (is_string($adicional)) { // Se String, chama o adicional
                        $iten[$adicional];
                    } else { // Se Array Percorrer o array
                        $iten2 = $iten;
                        foreach ($adicional as $key => $a) {
                            if ($key == 0) { // Se primeiro item
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
        // $teste = (String)$request->all();
        // return gettype(json_encode($request->all()));
        DB::transaction(function () use ($request) {
            $orcamento = Orcamento::create(
                [
                    'empresa_id'        => $request['empresa_id'],
                    'cliente_id'        => $request['cliente_id'],
                    'numero'            => $request['numero'],
                    'processo'          => $request['processo'],
                    'cidade_id'         => $request['cidade']['id'],
                    'tipo'              => $request['tipo'],
                    'data'              => $request['data'],
                    'unidade'           => $request['unidade'],
                    'quantidade'        => $request['quantidade'],
                    'situacao'          => $request['situacao'],
                    'descricao'         => $request['descricao'],
                    'valortotalservico' => $request['valortotalservico'],
                    'valortotalcusto'   => $request['valortotalcusto'],
                    'valortotalproduto' => $request['valortotalproduto'],
                    'observacao'        => $request['observacao'],
                    'status'            => 1
                ]
            );

            if ($request['servicos']) {
                foreach ($request['servicos'] as $key => $servico) {
                    $orcamento_servico = OrcamentoServico::create(
                        [
                            'orcamento_id' => $orcamento->id,
                            'servico_id'   => $servico['servico_id']['id'],
                            'quantidade'           => $servico['quantidade'],
                            'frequencia'           => $servico['frequencia'],
                            'basecobranca'         => $servico['basecobranca'],
                            'valorunitario'        => $servico['valorunitario'],
                            'custo'                => $servico['custo'],
                            'subtotal'             => $servico['subtotal'],
                            'subtotalcusto'        => $servico['subtotalcusto'],
                            'adicionalnoturno'     => $servico['adicionalnoturno'],
                            'horascuidado'         => $servico['horascuidado'],
                            'icms'                 => $servico['icms'],
                            'inss'                 => $servico['inss'],
                            'iss'                  => $servico['iss'],
                            'descricao'            => $servico['descricao'],
                            'valorcustomensal'     => $servico['valorcustomensal'],
                            'valorresultadomensal' => $servico['valorresultadomensal'],
                        ]
                    );
                }
            }

            if ($request['produtos']) {
                foreach ($request['produtos'] as $key => $produto) {
                    $orcamento_produto = OrcamentoProduto::create(
                        [
                            'orcamento_id' => $orcamento->id,
                            'produto_id'   => $produto['produto_id'],
                            'quantidade'           => $produto['quantidade'],
                            'valorunitario'        => $produto['valorunitario'],
                            'custo'                => $produto['custo'],
                            'subtotal'             => $produto['subtotal'],
                            'subtotalcusto'        => $produto['subtotalcusto'],
                            'descricao'            => $servico['descricao'],
                            'valorresultadomensal' => $produto['valorresultadomensal'],
                            'valorcustomensal'     => $produto['valorcustomensal']
                        ]
                    );
                }
            }

            if ($request['custos']) {
                foreach ($request['custos'] as $key => $custo) {
                    $orcamentocusto = Orcamentocusto::create(
                        [
                            'orcamento_id' => $orcamento->id,
                            'descricao'    => $custo['descricao'],
                            'quantidade'    => $custo['quantidade'],
                            'unidade'       => $custo['unidade'],
                            'valorunitario' => $custo['valorunitario'],
                            'valortotal'    => $custo['valortotal'],
                        ]
                    );
                }
            }

            if ($request['homecare']) {
                $homecare = Homecare::create(
                    [
                        'orcamento_id' => $orcamento->id,
                        'paciente_id' => $request['homecare']
                    ]
                );

                // $homecare = Homecare::find($request['homecare']);
                // $homecare->orcamento_id = $orcamento->id;
                // $homecare->update();
                // $homecare = Homecare::updateOrCreate(
                //     [
                //         'orcamento_id' => $orcamento->id,
                //     ],
                //     [
                //         'nome'         => $request['homecare']['nome'],
                //         'sexo'         => $request['homecare']['sexo'],
                //         'nascimento'   => $request['homecare']['nascimento'],
                //         'cpfcnpj'      => $request['homecare']['cpfcnpj'],
                //         'rgie'         => $request['homecare']['rgie'],
                //         'endereco'     => $request['homecare']['endereco'],
                //         'cidade_id'    => $request['homecare']['cidade_id'],
                //         'observacao'   => $request['homecare']['observacao'],
                //     ]
                // );

                // if ($request['homecare']['telefones']) {
                //     foreach ($request['homecare']['telefones'] as $key => $telefone) {
                //         $homecare_telefone = HomecareTelefone::firstOrCreate([
                //             'homecare_id' => $homecare->id,
                //             'telefone_id' => Telefone::firstOrCreate(
                //                 [
                //                     'telefone'  => $telefone['telefone'],
                //                 ]
                //             )->id,
                //             'tipo'      => $telefone['pivot']['tipo'],
                //             'descricao' => $telefone['pivot']['descricao'],
                //         ]);
                //     }
                // }

                // if ($request['homecare']['emails']) {
                //     foreach ($request['homecare']['emails'] as $key => $email) {
                //         $homecare_email = HomecareEmail::firstOrCreate([
                //             'homecare_id' => $homecare->id,
                //             'email_id'    => Email::firstOrCreate(
                //                 [
                //                     'email'     => $email['email'],
                //                 ]
                //             )->id,
                //             'tipo'      => $email['pivot']['tipo'],
                //             'descricao' => $email['pivot']['descricao'],
                //         ]);
                //     }
                // }
            }

            if ($request['remocao']) {
                $remocao = Remocao::create(
                    [
                        'orcamento_id'    => $orcamento->id,
                    ],
                    [
                        'nome'            => $request['remocao']['nome'],
                        'sexo'            => $request['remocao']['sexo'],
                        'nascimento'      => $request['remocao']['nascimento'],
                        'cpfcnpj'         => $request['remocao']['cpfcnpj'],
                        'rgie'            => $request['remocao']['rgie'],
                        'enderecoorigem'  => $request['remocao']['enderecoorigem'],
                        'cidadeorigem'    => $request['remocao']['cidadeorigem'],
                        'enderecodestino' => $request['remocao']['enderecodestino'],
                        'cidadedestino'   => $request['remocao']['cidadedestino'],
                        'observacao'      => $request['remocao']['observacao'],
                    ]
                );

                if ($request['remocao']['telefones']) {
                    foreach ($request['remocao']['telefones'] as $key => $telefone) {
                        $remocao_telefone = RemocaoTelefone::create([
                            'remocao_id'  => $remocao->id,
                            'telefone_id' => Telefone::firstOrCreate(
                                [
                                    'telefone'  => $telefone['telefone'],
                                ]
                            )->id,
                            'tipo'      => $telefone['pivot']['tipo'],
                            'descricao' => $telefone['pivot']['descricao'],
                        ]);
                    }
                }

                if ($request['remocao']['emails']) {
                    foreach ($request['remocao']['emails'] as $key => $email) {
                        $remocao_email = RemocaoEmail::create([
                            'remocao_id' => $remocao->id,
                            'email_id'   => Email::firstOrCreate(
                                [
                                    'email'     => $email['email'],
                                ]
                            )->id,
                            'tipo'      => $email['pivot']['tipo'],
                            'descricao' => $email['pivot']['descricao'],
                        ]);
                    }
                }
            }

            if ($request['evento']) {
                $evento = Evento::create(
                    [
                        'orcamento_id' => $orcamento->id,
                        'nome'     => $request['evento']['nome'],
                        'endereco' => $request['evento']['endereco'],
                        'cep'      => $request['evento']['cep'],
                        'cidade_id'   => $request['evento']['cidade_id'],
                    ]
                );

                if ($request['evento']['telefones']) {
                    foreach ($request['evento']['telefones'] as $key => $telefone) {
                        $evento_telefone = EventoTelefone::create([
                            'evento_id'   => $evento->id,
                            'telefone_id' => Telefone::firstOrCreate(
                                [
                                    'telefone' => $telefone['telefone'],
                                ]
                            )->id,
                            'tipo'      => $telefone['pivot']['tipo'],
                            'descricao' => $telefone['pivot']['descricao'],
                        ]);
                    }
                }

                if ($request['evento']['emails']) {
                    foreach ($request['evento']['emails'] as $key => $email) {
                        $evento_email = EventoEmail::reate([
                            'evento_id' => $evento->id,
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
            }

            if ($request['aph']) {
                $aph = Aph::create(
                    [
                        'orcamento_id' => $orcamento->id,
                        'nome'     => $request['aph']['nome'],
                        'endereco' => $request['aph']['endereco'],
                        'cep'      => $request['aph']['cep'],
                        'cidade'   => $request['aph']['cidade'],
                    ]
                );

                if ($request['aph']['telefones']) {
                    foreach ($request['aph']['telefones'] as $key => $telefone) {
                        $aph_telefone = AphTelefone::create([
                            'aph_id'      => $aph->id,
                            'telefone_id' => Telefone::firstOrCreate(
                                [
                                    'telefone' => $telefone['telefone'],
                                ]
                            )->id,
                            'tipo'      => $telefone['pivot']['tipo'],
                            'descricao' => $telefone['pivot']['descricao'],
                        ]);
                    }
                }

                if ($request['aph']['emails']) {
                    foreach ($request['aph']['emails'] as $key => $email) {
                        $aph_email = AphEmail::create([
                            'aph_id'   => $aph->id,
                            'email_id' => Email::firstOrCreate(
                                [
                                    'email' => $email['email'],
                                ]
                            )->id,
                            'tipo'      => $email['pivot']['tipo'],
                            'descricao' => $email['pivot']['descricao'],
                        ]);
                    }
                }
            }

            $historicoorcamento = Historicoorcamento::create([
                'orcamento_id' => $orcamento->id,
                'historico'    => json_encode($request->all()),
            ]);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Orcamento $orcamento)
    {
        $iten = $orcamento;

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
     * @param  \App\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orcamento $orcamento)
    {
        DB::transaction(function () use ($request, $orcamento) {
            $orcamento = Orcamento::updateOrCreate(
                [
                    'id' => ($request['id'] != '') ? $request['id'] : null,
                ],
                [
                    'empresa_id'        => $request['empresa_id'],
                    'cliente_id'        => $request['cliente_id'],
                    'numero'            => $request['numero'],
                    'processo'          => $request['processo'],
                    'cidade_id'         => $request['cidade']['id'],
                    'tipo'              => $request['tipo'],
                    'data'              => $request['data'],
                    'unidade'           => $request['unidade'],
                    'quantidade'        => $request['quantidade'],
                    'situacao'          => $request['situacao'],
                    'descricao'         => $request['descricao'],
                    'valortotalservico' => $request['valortotalservico'],
                    'valortotalcusto'   => $request['valortotalcusto'],
                    'valortotalproduto' => $request['valortotalproduto'],
                    'observacao'        => $request['observacao'],
                    'status'            => 1
                ]
            );

            foreach ($orcamento->servicos as $key => $servico) {
                $servico->delete();
            }

            if ($request['servicos']) {
                foreach ($request['servicos'] as $key => $servico) {
                    $orcamento_servico = OrcamentoServico::create(
                        [
                            'orcamento_id'         => $orcamento->id,
                            'servico_id'           => $servico['servico_id'],
                            'quantidade'           => $servico['quantidade'],
                            'frequencia'           => $servico['frequencia'],
                            'basecobranca'         => $servico['basecobranca'],
                            'valorunitario'        => $servico['valorunitario'],
                            'custo'                => $servico['custo'],
                            'subtotal'             => $servico['subtotal'],
                            'subtotalcusto'        => $servico['subtotalcusto'],
                            'adicionalnoturno'     => $servico['adicionalnoturno'],
                            'horascuidado'         => $servico['horascuidado'],
                            'icms'                 => $servico['icms'],
                            'inss'                 => $servico['inss'],
                            'iss'                  => $servico['iss'],
                            'valorcustomensal'     => $servico['valorcustomensal'],
                            'valorresultadomensal' => $servico['valorresultadomensal'],
                        ]
                    );
                }
            }

            foreach ($orcamento->produtos as $key => $produto) {
                $produto->delete();
            }

            if ($request['produtos']) {
                foreach ($request['produtos'] as $key => $produto) {
                    $orcamento_produto = OrcamentoProduto::create(
                        [
                            'orcamento_id'         => $orcamento->id,
                            'produto_id'           => $produto['produto_id'],
                            'quantidade'           => $produto['quantidade'],
                            'valorunitario'        => $produto['valorunitario'],
                            'custo'                => $produto['custo'],
                            'subtotal'             => $produto['subtotal'],
                            'subtotalcusto'        => $produto['subtotalcusto'],
                            'valorresultadomensal' => $produto['valorresultadomensal'],
                            'valorcustomensal'     => $produto['valorcustomensal']
                        ]
                    );
                }
            }

            foreach ($orcamento->custos as $key => $custo) {
                $custo->delete();
            }

            if ($request['custos']) {
                foreach ($request['custos'] as $key => $custo) {
                    $orcamentocusto = Orcamentocusto::create(
                        [
                            'orcamento_id'  => $orcamento->id,
                            'descricao'     => $custo['descricao'],
                            'quantidade'    => $custo['quantidade'],
                            'unidade'       => $custo['unidade'],
                            'valorunitario' => $custo['valorunitario'],
                            'valortotal'    => $custo['valortotal'],
                        ]
                    );
                }
            }

            // $orcamento->homecare->delete();

            if ($request['homecare']) {
                $homecare = Homecare::updateOrCreate(
                    [
                        'orcamento_id' => $orcamento->id,
                    ],
                    [
                        'paciente_id'       => $request['homecare'],
                    ]
                );

                // $homecare = Homecare::updateOrCreate(
                //     [
                //         'orcamento_id' => $orcamento->id,
                //     ],
                //     [
                //         'nome'       => $request['homecare']['nome'],
                //         'sexo'       => $request['homecare']['sexo'],
                //         'nascimento' => $request['homecare']['nascimento'],
                //         'cpfcnpj'    => $request['homecare']['cpfcnpj'],
                //         'rgie'       => $request['homecare']['rgie'],
                //         'endereco'   => $request['homecare']['endereco'],
                //         'cidade_id'  => $request['homecare']['cidade_id'],
                //         'observacao' => $request['homecare']['observacao'],
                //     ]
                // );

                // if ($request['homecare']['telefones']) {
                //     foreach ($request['homecare']['telefones'] as $key => $telefone) {
                //         $homecare_telefone = HomecareTelefone::updateOrCreate(
                //             [
                //                 'homecare_id' => $homecare->id,
                //                 'telefone_id' => Telefone::firstOrCreate(
                //                     [
                //                         'telefone'  => $telefone['telefone'],
                //                     ]
                //                 )->id,
                //             ],
                //             [
                //                 'tipo'      => $telefone['pivot']['tipo'],
                //                 'descricao' => $telefone['pivot']['descricao'],
                //             ]
                //         );
                //     }
                // }

                // if ($request['homecare']['emails']) {
                //     foreach ($request['homecare']['emails'] as $key => $email) {
                //         $homecare_email = HomecareEmail::updateOrCreate(
                //             [
                //                 'homecare_id' => $homecare->id,
                //                 'email_id'    => Email::firstOrCreate(
                //                     [
                //                         'email'     => $email['email'],
                //                     ]
                //                 )->id,
                //             ],
                //             [
                //                 'tipo'      => $email['pivot']['tipo'],
                //                 'descricao' => $email['pivot']['descricao'],
                //             ]
                //         );
                //     }
                // }
            }

            if ($request['remocao']) {
                $remocao = Remocao::updateOrCreate(
                    [
                        'orcamento_id'    => $orcamento->id,
                    ],
                    [
                        'nome'            => $request['remocao']['nome'],
                        'sexo'            => $request['remocao']['sexo'],
                        'nascimento'      => $request['remocao']['nascimento'],
                        'cpfcnpj'         => $request['remocao']['cpfcnpj'],
                        'rgie'            => $request['remocao']['rgie'],
                        'enderecoorigem'  => $request['remocao']['enderecoorigem'],
                        'cidadeorigem'    => $request['remocao']['cidadeorigem'],
                        'enderecodestino' => $request['remocao']['enderecodestino'],
                        'cidadedestino'   => $request['remocao']['cidadedestino'],
                        'observacao'      => $request['remocao']['observacao'],
                    ]
                );

                if ($request['remocao']['telefones']) {
                    foreach ($request['remocao']['telefones'] as $key => $telefone) {
                        $remocao_telefone = RemocaoTelefone::updateOrCreate(
                            [
                                'remocao_id'  => $remocao->id,
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

                if ($request['remocao']['emails']) {
                    foreach ($request['remocao']['emails'] as $key => $email) {
                        $remocao_email = RemocaoEmail::updateOrCreate(
                            [
                                'remocao_id' => $remocao->id,
                                'email_id'   => Email::firstOrCreate(
                                    [
                                        'email'     => $email['email'],
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
            }

            if ($request['evento']) {
                $evento = Evento::updateOrCreate(
                    [
                        'orcamento_id' => $orcamento->id,
                    ],
                    [
                        'nome'     => $request['evento']['nome'],
                        'endereco' => $request['evento']['endereco'],
                        'cep'      => $request['evento']['cep'],
                        'cidade'   => $request['evento']['cidade'],
                    ]
                );

                if ($request['evento']['telefones']) {
                    foreach ($request['evento']['telefones'] as $key => $telefone) {
                        $evento_telefone = EventoTelefone::updateOrCreate(
                            [
                                'evento_id'   => $evento->id,
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

                if ($request['evento']['emails']) {
                    foreach ($request['evento']['emails'] as $key => $email) {
                        $evento_email = EventoEmail::updateOrCreate(
                            [
                                'evento_id' => $evento->id,
                                'email_id'  => Email::firstOrCreate(
                                    [
                                        'email'     => $email['email'],
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
            }

            if ($request['aph']) {
                $aph = Aph::updateOrCreate(
                    [
                        'orcamento_id' => $orcamento->id,
                    ],
                    [
                        'nome'     => $request['aph']['nome'],
                        'endereco' => $request['aph']['endereco'],
                        'cep'      => $request['aph']['cep'],
                        'cidade'   => $request['aph']['cidade'],
                    ]
                );

                if ($request['aph']['telefones']) {
                    foreach ($request['aph']['telefones'] as $key => $telefone) {
                        $aph_telefone = AphTelefone::updateOrCreate(
                            [
                                'aph_id'      => $aph->id,
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

                if ($request['aph']['emails']) {
                    foreach ($request['aph']['emails'] as $key => $email) {
                        $aph_email = AphEmail::updateOrCreate(
                            [
                                'aph_id'   => $aph->id,
                                'email_id' => Email::firstOrCreate(
                                    [
                                        'email'     => $email['email'],
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
            }

            $historicoorcamento = Historicoorcamento::create([
                'orcamento_id' => $orcamento->id,
                'historico'    => json_encode($request->all()),
            ]);
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function alterarSituacao(Request $request, Orcamento $orcamento)
    {
        // $orcamento->empresa_id        = $request['empresa_id'];
        // $orcamento->cliente_id        = $request['cliente_id'];
        // $orcamento->numero            = $request['numero'];
        // $orcamento->processo          = $request['processo'];
        // $orcamento->cidade_id         = $request['cidade_id'];
        // $orcamento->tipo              = $request['tipo'];
        // $orcamento->data              = $request['data'];
        // $orcamento->unidade           = $request['unidade'];
        // $orcamento->quantidade        = $request['quantidade'];
        $orcamento->situacao          = $request['situacao'];
        // $orcamento->descricao         = $request['descricao'];
        // $orcamento->valortotalservico = $request['valortotalservico'];
        // $orcamento->valortotalcusto   = $request['valortotalcusto'];
        // $orcamento->valortotalproduto = $request['valortotalproduto'];
        // $orcamento->observacao        = $request['observacao'];
        // $orcamento->status            = $request['status'];
        $orcamento->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orcamento $orcamento)
    {
        $orcamento->ativo = false;
        $orcamento->save();
    }
}
