<?php

namespace App\Http\Controllers\Api;

use App\Email;
use App\Pessoa;
use App\Remocao;
use App\Evento;
use App\Servico;
use App\Telefone;
use App\Homecare;
use App\Orcamento;
use App\RemocaoEmail;
use App\HomecareEmail;
use App\Orcamentocusto;
use App\RemocaoTelefone;
use App\OrcamentoServico;
use App\OrcamentoProduto;
use App\HomecareTelefone;
use App\Historicoorcamento;
use Illuminate\Http\Request;
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
        $itens = null;

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }
        
        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Orcamento::where(
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
            $itens = Orcamento::where('id', 'like', '%');
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
                foreach ($request['adicionais'] as $key => $adicional) { // Percorrer os adicionais
                    if (is_string($adicional)) { // Se String, chama o adicional
                        $iten[$adicional];
                    } else { // Se Array Percorrer o array
                        $iten2 = $iten;
                        foreach ($adicional as $key => $a) {
                            if ($key == 0) { // Se primeiro item
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
        // $teste = (String)$request->all();
        // return gettype(json_encode($request->all()));

        $orcamento = Orcamento::updateOrCreate(
        [
            'empresa_id'        => $request['empresa_id'       ],
            'cliente_id'        => $request['cliente_id'       ],
            'numero'            => $request['numero'           ],
            'processo'          => $request['processo'         ],
            'cidade_id'         => $request['cidade_id'        ],
            'tipo'              => $request['tipo'             ],
            'data'              => $request['data'             ],
            'unidade'           => $request['unidade'          ],
            'quantidade'        => $request['quantidade'       ],
            'situacao'          => $request['situacao'         ],
            'descricao'         => $request['descricao'        ],
            'valortotalservico' => $request['valortotalservico'],
            'valortotalcusto'   => $request['valortotalcusto'  ],
            'valortotalproduto' => $request['valortotalproduto'],
            'observacao'        => $request['observacao'       ],
            'status'            => 1
        ]);

        if ($request['servicos']) {
            foreach ($request['servicos'] as $key => $servico) {
                $orcamento_servico = OrcamentoServico::updateOrCreate(
                [
                    'orcamento_id' => $orcamento->id        ,
                    'servico_id'   => $servico['servico_id'],
                ],
                [
                    'quantidade'	       => $servico['quantidade'          ],
                    'frequencia'	       => $servico['frequencia'          ],
                    'basecobranca'	       => $servico['basecobranca'        ],
                    'valorunitario'	       => $servico['valorunitario'       ],
                    'custo'                => $servico['custo'               ],
                    'subtotal'	           => $servico['subtotal'            ],
                    'subtotalcusto'    	   => $servico['subtotalcusto'       ],
                    'icms'	               => $servico['icms'                ],
                    'inss'                 => $servico['inss'                ],
                    'iss'                  => $servico['iss'                 ],
                    'valorcustomensal'     => $servico['valorcustomensal'    ],
                    'valorresultadomensal' => $servico['valorresultadomensal'],
                ]);
            }
        }

        if ($request['produtos']) {
            foreach ($request['produtos'] as $key => $produto) {
                $orcamento_produto = OrcamentoProduto::updateOrCreate(
                [
                    'orcamento_id' => $orcamento->id        ,
                    'produto_id'   => $produto['produto_id'],
                ],
                [
                    'quantidade'	       => $produto['quantidade'          ],
                    'valorunitario'	       => $produto['valorunitario'       ],
                    'custo'                => $produto['custo'               ],
                    'subtotal'	           => $produto['subtotal'            ],
                    'subtotalcusto'    	   => $produto['subtotalcusto'       ],
                    'valorresultadomensal' => $produto['valorresultadomensal'],
                    'valorcustomensal'     => $produto['valorcustomensal'    ]
                ]);
            }
        }

        if ($request['custos']) {
            foreach ($request['custos'] as $key => $custo) {
                $orcamentocusto = Orcamentocusto::updateOrCreate(
                [
                    'orcamento_id' => $orcamento->id,
                    'descricao'    => $custo['descricao'    ],
                ],
                [
                    'quantidade'    => $custo['quantidade'   ],
                    'unidade'       => $custo['unidade'      ],
                    'valorunitario'	=> $custo['valorunitario'],
                    'valortotal'    => $custo['valortotal'   ],
                ]);
            }
        }

        if ($request['homecare']) {
            $homecare = Homecare::updateOrCreate(
            [
                'orcamento_id' => $orcamento->id,
            ],
            [
                'nome'         => $request['homecare']['nome'      ],
                'sexo'         => $request['homecare']['sexo'      ],
                'nascimento'   => $request['homecare']['nascimento'],
                'cpfcnpj'	   => $request['homecare']['cpf'       ],
                'rgie'         => $request['homecare']['rg'        ],
                'endereco'     => $request['homecare']['endereco'  ],
                'cidade_id'    => $request['homecare']['cidade'    ],
                'observacao'   => $request['homecare']['observacao'],
            ]);

            if ($request['homecare']['telefones']) {
                foreach ($request['homecare']['telefones'] as $key => $telefone) {
                    $homecare_telefone = HomecareTelefone::firstOrCreate([
                        'homecare_id' => $homecare->id,
                        'telefone_id' => Telefone::firstOrCreate(
                            [
                                'telefone'  => $telefone['telefone' ],
                            ])->id,
                        'tipo'      => $telefone['tipo'     ],
                        'descricao' => $telefone['descricao'],
                    ]);
                }
            }
            
            if ($request['homecare']['emails']) {
                foreach ($request['homecare']['emails'] as $key => $email) {
                    $homecare_email = HomecareEmail::firstOrCreate([
                        'homecare_id' => $homecare->id,
                        'email_id'    => Email::firstOrCreate(
                            [
                                'email'     => $email['email'    ],
                            ])->id,
                        'tipo'      => $email['tipo'     ],
                        'descricao' => $email['descricao'],
                    ]);
                }
            }
        }

        if ($request['remocao']) {
            $remocao = Remocao::updateOrCreate(
            [
                'orcamento_id'    => $orcamento->id,
            ],
            [
                'nome'            => $request['remocao']['nome'           ],
                'sexo'            => $request['remocao']['sexo'           ],
                'nascimento'      => $request['remocao']['nascimento'     ],
                'cpfcnpj'	      => $request['remocao']['cpf'            ],
                'rgie'            => $request['remocao']['rg'             ],
                'enderecoorigem'  => $request['remocao']['enderecoorigem' ],
                'cidadeorigem'    => $request['remocao']['cidadeorigem'   ],
                'enderecodestino' => $request['remocao']['enderecodestino'],
                'cidadedestino'   => $request['remocao']['cidadedestino'  ],
                'observacao'      => $request['remocao']['observacao'     ],
            ]);

            if ($request['remocao']['telefones']) {
                foreach ($request['remocao']['telefones'] as $key => $telefone) {
                    $remocao_telefone = RemocaoTelefone::firstOrCreate([
                        'remocao_id'  => $remocao->id,
                        'telefone_id' => Telefone::firstOrCreate(
                            [
                                'telefone'  => $telefone['telefone' ],
                            ])->id,
                        'tipo'      => $telefone['tipo'     ],
                        'descricao' => $telefone['descricao'],
                    ]);
                }
            }
            if ($request['remocao']['emails']) {
                foreach ($request['remocao']['emails'] as $key => $email) {
                    $remocao_email = RemocaoEmail::firstOrCreate([
                        'remocao_id' => $remocao->id,
                        'email_id'   => Email::firstOrCreate(
                            [
                                'email'     => $email['email'    ],
                            ])->id,
                        'tipo'      => $email['tipo'     ],
                        'descricao' => $email['descricao'],
                    ]);
                }
            }
        }

        if ($request['evento']) {
            $evento = Evento::updateOrCreate(
            [
                'orcamento_id' => $orcamento->id,
            ],
            [
                'nome'         => $request['evento']['nome'    ],
                'endereco'     => $request['evento']['endereco'],
                'cep'          => $request['evento']['cep'     ],
                'cidade'       => $request['evento']['cidade'  ],
            ]);

            if ($request['evento']['telefones']) {
                foreach ($request['evento']['telefones'] as $key => $telefone) {
                    $evento_telefone = EventoTelefone::firstOrCreate([
                        'evento_id'   => $evento->id,
                        'telefone_id' => Telefone::firstOrCreate(
                            [
                                'telefone'  => $telefone['telefone' ],
                            ])->id,
                        'tipo'      => $telefone['tipo'     ],
                        'descricao' => $telefone['descricao'],
                    ]);
                }
            }
            if ($request['evento']['emails']) {
                foreach ($request['evento']['emails'] as $key => $email) {
                    $evento_email = EventoEmail::firstOrCreate([
                        'evento_id' => $evento->id,
                        'email_id'  => Email::firstOrCreate(
                            [
                                'email'     => $email['email'    ],
                            ])->id,
                        'tipo'      => $email['tipo'     ],
                        'descricao' => $email['descricao'],
                    ]);
                }
            }
        }

        if ($request['aph']) {
            $aph = Aph::updateOrCreate(
            [
                'orcamento_id' => $orcamento->id,
            ],
            [
                'nome'         => $request['aph']['nome'    ],
                'endereco'     => $request['aph']['endereco'],
                'cep'          => $request['aph']['cep'     ],
                'cidade'       => $request['aph']['cidade'  ],
            ]);

            if ($request['aph']['telefones']) {
                foreach ($request['aph']['telefones'] as $key => $telefone) {
                    $aph_telefone = AphTelefone::firstOrCreate([
                        'aph_id'      => $aph->id,
                        'telefone_id' => Telefone::firstOrCreate(
                            [
                                'telefone'  => $telefone['telefone' ],
                            ])->id,
                        'tipo'      => $telefone['tipo'     ],
                        'descricao' => $telefone['descricao'],
                    ]);
                }
            }
            if ($request['aph']['emails']) {
                foreach ($request['aph']['emails'] as $key => $email) {
                    $aph_email = AphEmail::firstOrCreate([
                        'aph_id'   => $aph->id,
                        'email_id' => Email::firstOrCreate(
                            [
                                'email'     => $email['email'    ],
                            ])->id,
                        'tipo'      => $email['tipo'     ],
                        'descricao' => $email['descricao'],
                    ]);
                }
            }
        }

        $historicoorcamento = Historicoorcamento::updateOrCreate([
            'orcamento_id' => $orcamento->id,
            'historico'    => json_encode($request->all()),
        ]);
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
     * @param  \App\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orcamento $orcamento)
    {
        $orcamento = Orcamento::updateOrCreate(
            [
                'id' => ($request['id'] != '')? $request['id'] : null,
            ],
            [
                'empresa_id'        => $request['empresa_id'       ],
                'cliente_id'        => $request['cliente_id'       ],
                'numero'            => $request['numero'           ],
                'processo'          => $request['processo'         ],
                'cidade_id'         => $request['cidade_id'        ],
                'tipo'              => $request['tipo'             ],
                'data'              => $request['data'             ],
                'unidade'           => $request['unidade'          ],
                'quantidade'        => $request['quantidade'       ],
                'situacao'          => $request['situacao'         ],
                'descricao'         => $request['descricao'        ],
                'valortotalservico' => $request['valortotalservico'],
                'valortotalcusto'   => $request['valortotalcusto'  ],
                'valortotalproduto' => $request['valortotalproduto'],
                'observacao'        => $request['observacao'       ],
                'status'            => 1
            ]);
    
            if ($request['servicos']) {
                foreach ($request['servicos'] as $key => $servico) {
                    $orcamento_servico = OrcamentoServico::updateOrCreate(
                    [
                        'orcamento_id' => $orcamento->id        ,
                        'servico_id'   => $servico['servico_id'],
                    ],
                    [
                        'quantidade'	       => $servico['quantidade'          ],
                        'frequencia'	       => $servico['frequencia'          ],
                        'basecobranca'	       => $servico['basecobranca'        ],
                        'valorunitario'	       => $servico['valorunitario'       ],
                        'custo'                => $servico['custo'               ],
                        'subtotal'	           => $servico['subtotal'            ],
                        'subtotalcusto'    	   => $servico['subtotalcusto'       ],
                        'icms'	               => $servico['icms'                ],
                        'inss'                 => $servico['inss'                ],
                        'iss'                  => $servico['iss'                 ],
                        'valorcustomensal'     => $servico['valorcustomensal'    ],
                        'valorresultadomensal' => $servico['valorresultadomensal'],
                    ]);
                }
            }
    
            if ($request['produtos']) {
                foreach ($request['produtos'] as $key => $produto) {
                    $orcamento_produto = OrcamentoProduto::updateOrCreate(
                    [
                        'orcamento_id' => $orcamento->id        ,
                        'produto_id'   => $produto['produto_id'],
                    ],
                    [
                        'quantidade'	       => $produto['quantidade'          ],
                        'valorunitario'	       => $produto['valorunitario'       ],
                        'custo'                => $produto['custo'               ],
                        'subtotal'	           => $produto['subtotal'            ],
                        'subtotalcusto'    	   => $produto['subtotalcusto'       ],
                        'valorresultadomensal' => $produto['valorresultadomensal'],
                        'valorcustomensal'     => $produto['valorcustomensal'    ]
                    ]);
                }
            }
    
            if ($request['custos']) {
                foreach ($request['custos'] as $key => $custo) {
                    $orcamentocusto = Orcamentocusto::updateOrCreate(
                    [
                        'orcamento_id' => $orcamento->id,
                        'descricao'    => $custo['descricao'    ],
                    ],
                    [
                        'quantidade'    => $custo['quantidade'   ],
                        'unidade'       => $custo['unidade'      ],
                        'valorunitario'	=> $custo['valorunitario'],
                        'valortotal'    => $custo['valortotal'   ],
                    ]);
                }
            }
    
            if ($request['homecare']) {
                $homecare = Homecare::updateOrCreate(
                [
                    'orcamento_id' => $orcamento->id,
                ],
                [
                    'nome'         => $request['homecare']['nome'      ],
                    'sexo'         => $request['homecare']['sexo'      ],
                    'nascimento'   => $request['homecare']['nascimento'],
                    'cpfcnpj'	   => $request['homecare']['cpf'       ],
                    'rgie'         => $request['homecare']['rg'        ],
                    'endereco'     => $request['homecare']['endereco'  ],
                    'cidade_id'    => $request['homecare']['cidade'    ],
                    'observacao'   => $request['homecare']['observacao'],
                ]);
    
                if ($request['homecare']['telefones']) {
                    foreach ($request['homecare']['telefones'] as $key => $telefone) {
                        $homecare_telefone = HomecareTelefone::updateOrCreate([
                            'homecare_id' => $homecare->id,
                            'telefone_id' => Telefone::updateOrCreate(
                                [
                                    'id' => $telefone['id'],
                                ],
                                [
                                    'telefone'  => $telefone['telefone' ],
                                    'tipo'      => $telefone['tipo'     ],
                                    'descricao' => $telefone['descricao'],
                                ])->id,
                        ]);
                    }
                }
                
                if ($request['homecare']['emails']) {
                    foreach ($request['homecare']['emails'] as $key => $email) {
                        $homecare_email = HomecareEmail::updateOrCreate([
                            'homecare_id' => $homecare->id,
                            'email_id'    => Email::updateOrCreate(
                                [
                                    'id' => $email['id'],
                                ],
                                [
                                    'email'     => $email['email'    ],
                                    'tipo'      => $email['tipo'     ],
                                    'descricao' => $email['descricao'],
                                ])->id,
                        ]);
                    }
                }
            }
    
            if ($request['remocao']) {
                $remocao = Remocao::updateOrCreate(
                [
                    'orcamento_id'    => $orcamento->id,
                ],
                [
                    'nome'            => $request['remocao']['nome'           ],
                    'sexo'            => $request['remocao']['sexo'           ],
                    'nascimento'      => $request['remocao']['nascimento'     ],
                    'cpfcnpj'	      => $request['remocao']['cpf'            ],
                    'rgie'            => $request['remocao']['rg'             ],
                    'enderecoorigem'  => $request['remocao']['enderecoorigem' ],
                    'cidadeorigem'    => $request['remocao']['cidadeorigem'   ],
                    'enderecodestino' => $request['remocao']['enderecodestino'],
                    'cidadedestino'   => $request['remocao']['cidadedestino'  ],
                    'observacao'      => $request['remocao']['observacao'     ],
                ]);
    
                if ($request['remocao']['telefones']) {
                    foreach ($request['remocao']['telefones'] as $key => $telefone) {
                        $remocao_telefone = RemocaoTelefone::updateOrCreate([
                            'remocao_id'  => $remocao->id,
                            'telefone_id' => Telefone::updateOrCreate(
                                [
                                    'id' => $telefone['id'],
                                ],
                                [
                                    'telefone'  => $telefone['telefone' ],
                                    'tipo'      => $telefone['tipo'     ],
                                    'descricao' => $telefone['descricao'],
                                ])->id,
                        ]);
                    }
                }
                if ($request['remocao']['emails']) {
                    foreach ($request['remocao']['emails'] as $key => $email) {
                        $remocao_email = RemocaoEmail::updateOrCreate([
                            'remocao_id' => $remocao->id,
                            'email_id'   => Email::updateOrCreate(
                                [
                                    'id' => $email['id'],
                                ],
                                [
                                    'email'     => $email['email'    ],
                                    'tipo'      => $email['tipo'     ],
                                    'descricao' => $email['descricao'],
                                ])->id,
                        ]);
                    }
                }
            }
    
            if ($request['evento']) {
                $evento = Evento::updateOrCreate(
                [
                    'orcamento_id' => $orcamento->id,
                ],
                [
                    'nome'         => $request['evento']['nome'    ],
                    'endereco'     => $request['evento']['endereco'],
                    'cep'          => $request['evento']['cep'     ],
                    'cidade'       => $request['evento']['cidade'  ],
                ]);
    
                if ($request['evento']['telefones']) {
                    foreach ($request['evento']['telefones'] as $key => $telefone) {
                        $evento_telefone = EventoTelefone::updateOrCreate([
                            'evento_id'   => $evento->id,
                            'telefone_id' => Telefone::updateOrCreate(
                                [
                                    'id' => $telefone['id'],
                                ],
                                [
                                    'telefone'  => $telefone['telefone' ],
                                    'tipo'      => $telefone['tipo'     ],
                                    'descricao' => $telefone['descricao'],
                                ])->id,
                        ]);
                    }
                }
                if ($request['evento']['emails']) {
                    foreach ($request['evento']['emails'] as $key => $email) {
                        $evento_email = EventoEmail::updateOrCreate([
                            'evento_id' => $evento->id,
                            'email_id'  => Email::updateOrCreate(
                                [
                                    'id' => $email['id'],
                                ],
                                [
                                    'email'     => $email['email'    ],
                                    'tipo'      => $email['tipo'     ],
                                    'descricao' => $email['descricao'],
                                ])->id,
                        ]);
                    }
                }
            }
    
            if ($request['aph']) {
                $aph = Aph::updateOrCreate(
                [
                    'orcamento_id' => $orcamento->id,
                ],
                [
                    'nome'         => $request['aph']['nome'    ],
                    'endereco'     => $request['aph']['endereco'],
                    'cep'          => $request['aph']['cep'     ],
                    'cidade'       => $request['aph']['cidade'  ],
                ]);
    
                if ($request['aph']['telefones']) {
                    foreach ($request['aph']['telefones'] as $key => $telefone) {
                        $aph_telefone = AphTelefone::updateOrCreate([
                            'aph_id'      => $aph->id,
                            'telefone_id' => Telefone::updateOrCreate(
                                [
                                    'id' => $telefone['id'],
                                ],
                                [
                                    'telefone'  => $telefone['telefone' ],
                                    'tipo'      => $telefone['tipo'     ],
                                    'descricao' => $telefone['descricao'],
                                ])->id,
                        ]);
                    }
                }
                if ($request['aph']['emails']) {
                    foreach ($request['aph']['emails'] as $key => $email) {
                        $aph_email = AphEmail::updateOrCreate([
                            'aph_id'   => $aph->id,
                            'email_id' => Email::updateOrCreate(
                                [
                                    'id' => $email['id'],
                                ],
                                [
                                    'email'     => $email['email'    ],
                                    'tipo'      => $email['tipo'     ],
                                    'descricao' => $email['descricao'],
                                ])->id,
                        ]);
                    }
                }
            }
    
            $historicoorcamento = Historicoorcamento::updateOrCreate([
                'orcamento_id' => $orcamento->id,
                'historico'    => json_encode($request->all()),
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orcamento $orcamento)
    {
        $orcamento->delete();
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
       
    }
}
