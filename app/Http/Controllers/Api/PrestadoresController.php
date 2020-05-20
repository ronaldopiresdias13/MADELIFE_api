<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Prestador;
use App\Pessoa;
use App\Formacao;
use App\User;
use App\Dadosbancario;
use App\Banco;
use App\Cargo;
use App\PrestadorFormacao;
use App\PessoaTelefone;
use App\Telefone;
use App\PessoaEmail;
use App\Email;
use App\PessoaEndereco;
use App\Endereco;
use App\Cidade;
use App\Conselho;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;

class PrestadoresController extends Controller
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
                    $itens = Prestador::where(
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
            $itens = Prestador::where('id', 'like', '%');
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
        $prestador = new Prestador;
        $prestador->pessoa      = $request->pessoa     ;
        $prestador->fantasia    = $request->fantasia   ;
        $prestador->sexo        = $request->sexo       ;
        $prestador->pis         = $request->pis        ;
        $prestador->formacao    = $request->formacao   ;
        $prestador->cargo       = $request->cargo      ;
        $prestador->curriculo   = $request->curriculo  ;
        $prestador->certificado = $request->certificado;
        $prestador->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function show(Prestador $prestador)
    {
        return $prestador;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prestador $prestador)
    {
        $prestador->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prestador $prestador)
    {
        $prestador->delete();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function migracao(Request $request)
    {
        $prestador = Prestador::firstOrCreate([
            'pessoa_id' => Pessoa::firstOrCreate(
                [
                    'cpfcnpj' => $request['prestador']['dadosPf']['cpf']['numero'],
                ],
                [
                    'nome'        => $request['prestador']['dadosPf']['nome'],
                    'nascimento'  => $request['prestador']['dadosPf']['nascimento'],
                    'tipo'        => 'Prestador',
                    'rgie'        => $request['prestador']['dadosPf']['rg']['numero'],
                    'observacoes' => $request['prestador']['observacoes'],
                    'status'      => $request['prestador']['status'],
                ]
            )->id,
            'fantasia'    => $request['prestador']['nomeFantasia'],
            'sexo'        => $request['prestador']['dadosPf']['sexo'],
            'pis'         => $request['prestador']['dadosProf']['pis'],
            'cargo_id'       => null,
            'curriculo'   => null,
            'certificado' => $request['prestador']['id'],
        ]);
        
        $prestador_formacao = PrestadorFormacao::firstOrCreate([
            'prestador_id' => $prestador->id,
            'formacao_id'  => Formacao::firstOrCreate(['descricao' => $request['prestador']['dadosProf']['formacao']['descricao']])->id,
        ]);
        
        $usercpf = User::firstWhere(
            'cpfcnpj' , $request['prestador']['dadosPf']['cpf']['numero']
        );
        $useremail = User::firstWhere(
            'email', $request['prestador']['contato']['email']
        );

        if ($usercpf || $useremail) {
            
        } else {
            $user = User::create([
                'cpfcnpj' => $request['prestador']['dadosPf']['cpf']['numero'],
                'email'   => $request['prestador']['contato']['email'],
                'pessoa_id'  => $prestador->pessoa_id,
                'password' => bcrypt($request['senha']),
            ]);
        }

        if($request['prestador']['dadosBancario']['banco'] != null && $request['prestador']['dadosBancario']['banco']['codigo'] != null){
            $dados_bancario = Dadosbancario::firstOrCreate([
                'banco_id' => Banco::firstOrCreate(
                    [
                        'codigo' => ($request['prestador']['dadosBancario']['banco']['codigo'] == null || $request['prestador']['dadosBancario']['banco']['codigo'] == "") ? '000' : $request['prestador']['dadosBancario']['banco']['codigo'],
                    ],
                    [
                        'descricao' => ($request['prestador']['dadosBancario']['banco']['codigo'] == null || $request['prestador']['dadosBancario']['banco']['codigo'] == "") ? 'Outros' : $request['prestador']['dadosBancario']['banco']['descricao']
                    ]
                )->id,
                'pessoa_id'    => $prestador->pessoa_id,
                'agencia'   => $request['prestador']['dadosBancario']['agencia'  ],
                'conta'     => $request['prestador']['dadosBancario']['conta'    ],
                'digito'    => $request['prestador']['dadosBancario']['digito'   ],
                'tipoconta' => $request['prestador']['dadosBancario']['tipoConta'],
            ]);
        }

        if ($request['prestador']['contato']['telefone'] != null && $request['prestador']['contato']['telefone'] != "") {
            $pessoa_telefones = PessoaTelefone::firstOrCreate([
                'pessoa_id'   => $prestador->pessoa_id,
                'telefone_id' => Telefone::firstOrCreate(
                    [
                        'telefone' => $request['prestador']['contato']['telefone'],
                    ]
                )->id,
            ]);
        }
        if ($request['prestador']['contato']['celular'] != null && $request['prestador']['contato']['celular'] != "") {
            $pessoa_telefones = PessoaTelefone::firstOrCreate([
                'pessoa_id'   => $prestador->pessoa_id,
                'telefone_id' => Telefone::firstOrCreate(
                    [
                        'telefone' => $request['prestador']['contato']['celular'],
                    ]
                )->id,
            ]);
        }

        $pessoa_emails = PessoaEmail::firstOrCreate([
            'pessoa_id' => $prestador->pessoa_id,
            'email_id'  => Email::firstOrCreate(
                [
                    'email' => $request['prestador']['contato']['email'],
                ],
                [
                    'tipo' => 'pessoal',
                ]
            )->id,
        ]);

        $cidade = Cidade::where('nome', $request['prestador']['endereco']['cidade'])->where('uf', $request['prestador']['endereco']['uf'])->first();

        $pessoa_endereco = PessoaEndereco::firstOrCreate([
            'pessoa_id'   => $prestador->pessoa_id,
            'endereco_id' => Endereco::firstOrCreate(
                [
                    'cep'         => $request['prestador']['endereco']['cep'],
                    'cidade_id'      => ($cidade) ? $cidade->id : null,
                    'rua'         => $request['prestador']['endereco']['rua'],
                    'bairro'      => $request['prestador']['endereco']['bairro'],
                    'numero'      => $request['prestador']['endereco']['numero'],
                    'complemento' => $request['prestador']['endereco']['complemento'],
                    'tipo'        => 'Residencial',
                ]
            )->id,
        ]);

        $conselho = Conselho::firstOrCreate([
            'instituicao' => $request['prestador']['conselho']['instituicao'],
            'uf' => 'SP',
            'numero' => $request['prestador']['conselho']['numero'],
            'pessoa_id'   => $prestador->pessoa_id,
        ]);
    }
}
