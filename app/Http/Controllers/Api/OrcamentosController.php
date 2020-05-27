<?php

namespace App\Http\Controllers\Api;

use App\Orcamento;
use App\Pessoa;
use App\Servico;
use App\Homecare;
use App\OrcamentoEmail;
use App\Orcamentocusto;
use App\OrcamentoServico;
use App\OrcamentoProduto;
use App\OrcamentoTelefone;
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
        // dd('teste');
        // $orcamentos = Orcamento::all();
        // foreach ($orcamentos as $key => $orcamento) {
        //     // $orcamento->historicos;
        //     foreach ($orcamento->historicos as $key => $historico) {
        //         $historico->orcamentoservicos;
        //         $historico->orcamentoprodutos;
        //     }
        // }
        // return $orcamentos;
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
        $teste = (String)$request;
        return gettype($teste);

        $orcamento = Orcamento::updateOrCreate(
        [
            'id' => ($request['id'] != '')? $request['id'] : null,
        ],
        [
            'empresa_id' => $request['empresa_id'],
            'cliente_id' => $request['cliente_id'],
            'numero'     => $request['numero'    ],
            'processo'   => $request['processo'  ],
            'cidade_id'  => $request['cidade_id' ],
            'tipo'       => $request['tipo'      ],
            'data'       => $request['data'      ],
            'unidade'    => $request['unidade'   ],
            'quantidade' => $request['quantidade'],
            'situacao'   => $request['situacao'  ],
            'descricao'  => $request['descricao' ],
            'observacao' => $request['observacao'],
            'status'     => 1
        ]);

        if ($request['servicos']) {
            foreach ($request['servicos'] as $key => $servico) {
                $orcamento_servico = OrcamentoServico::create([
                    'orcamento_id'         => $orcamento->id                  ,
                    'servico_id'           => $servico['servico_id'          ],
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
                $orcamento_produto = OrcamentoProduto::create([
                    'orcamento_id'         => $orcamento->id                  ,
                    'produto_id'           => $produto['produto_id'          ],
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
                $orcamentocusto = Orcamentocusto::create([
                    'orcamento_id'  => $orcamento->id,
                    'descricao'     => $custo['descricao'    ],
                    'quantidade'    => $custo['quantidade'   ],
                    'unidade'       => $custo['unidade'      ],
                    'valorunitario'	=> $custo['valorunitario'],
                    'valortotal'    => $custo['valortotal'   ],
                ]);
            }
        }

        if ($request['homecare']) {
            $orcamentocusto = Homecare::create([
                'orcamento_id' => $orcamento->id,
                'nome'         => $homecare['nome'      ],
                'sexo'         => $homecare['sexo'      ],
                'nascimento'   => $homecare['nascimento'],
                'cpfcnpj'	   => $homecare['cpf'       ],
                'rgie'         => $homecare['rg'        ],
                'endereco'     => $homecare['endereco'  ],
                'cidade_id'    => $homecare['cidade'    ],
                'observacao'   => $homecare['observacao'],
            ]);

            $orcamento_telefone = OrcamentoTelefone::create([
                'orcamento_id' => $orcamento->id,
                'telefone_id'  => $homecare['telefone'],
            ]);
            $orcamento_telefone = OrcamentoTelefone::create([
                'orcamento_id' => $orcamento->id,
                'telefone_id'  => $homecare['celular'],
            ]);
            $orcamento_email = OrcamentoEmail::create([
                'orcamento_id' => $orcamento->id,
                'email_id'     => $homecare['email'],
            ]);
        }

        $historicoorcamento = Historicoorcamento::create([
            'orcamento_id'  => $orcamento->id,
            'descricao'     => (String)$request,
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orcamento $orcamento)
    {
        //
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
