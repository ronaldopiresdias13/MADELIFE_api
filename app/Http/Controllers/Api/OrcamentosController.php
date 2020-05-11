<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Orcamento;
use App\ItensOrcamentoServico;
use App\ItensOrcamentoProduto;
use App\ItensOrcamentoCusto;
use App\Historicoorcamento;
use App\HistoricoorcamentoOrcamentocusto;
use App\HistoricoorcamentoOrcamentoproduto;
use App\HistoricoorcamentoOrcamentoservico;
use Illuminate\Http\Request;

class OrcamentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Orcamento::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function show(Orcamento $orcamento)
    {
        //
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
        $table->id();
            $table->foreignId('orcamento')->constrained()->onDelete('cascade');
            $table->string('data');
            $table->float('valortotalservico');
            $table->string('valortotalproduto');
            $table->string('valortotalcusto');
            $table->timestamps();

        
        $orcamento = Orcamento::firstOrCreate([
            'numero' => $request['numeroOrcamento'],
            'tipo' => $request['tipoOrcamento'],
            'cliente_id' => Cliente::firstWhere(
                'pessoa_id', $request['pessoa_id']
            )->id,
            'empresa_id' => 1,
            'data' => $request['data'],
            'quantidade' => $request['cicloMeses'],
            'unidade' => 'Meses',
            'cidade' => $request['cidade'],
            'processo' => $request['numeroProcesso'],
            'situacao' => $request['situacao'],
            'descricao' => "",
            'observacao' => $request['observacao'],
        ]);

        foreach ($request->historicoOrcamento as $key => $value) {
            $teste = UserAcesso::updateOrCreate(
                ['user'  => $user->id, 'acesso' => Acesso::FirstOrCreate(['nome' => $value['nome']])->id]
            );
        }
        $historico = Historicoorcamento::firstOrCreate([
            'orcamento' => $orcamento->id
        ]);
        $prestador = Prestador::firstOrCreate([
            'pessoa' => Pessoa::firstOrCreate(
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
            'cargo'       => null,
            'curriculo'   => $request['prestador']['dadosPf']['curriculo'],
            'certificado' => $request['prestador']['dadosPf']['certificado'],
        ]);
        
        $prestador_formacao = PrestadorFormacao::firstOrCreate([
            'prestador' => $prestador->id,
            'formacao'  => Formacao::firstOrCreate(['descricao' => $request['prestador']['dadosProf']['formacao']['descricao']])->id,
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
                'pessoa'  => $prestador->pessoa,
                'password' => bcrypt($request['senha']),
            ]);
        }

        if($request['prestador']['dadosBancario']['banco'] != null && $request['prestador']['dadosBancario']['banco']['codigo'] != null){
            $dados_bancario = Dadosbancario::firstOrCreate([
                'banco' => Banco::firstOrCreate(
                    [
                        'codigo' => ($request['prestador']['dadosBancario']['banco']['codigo'] == null || $request['prestador']['dadosBancario']['banco']['codigo'] == "") ? '000' : $request['prestador']['dadosBancario']['banco']['codigo'],
                    ],
                    [
                        'descricao' => ($request['prestador']['dadosBancario']['banco']['codigo'] == null || $request['prestador']['dadosBancario']['banco']['codigo'] == "") ? 'Outros' : $request['prestador']['dadosBancario']['banco']['descricao']
                    ]
                )->id,
                'pessoa'    => $prestador->pessoa,
                'agencia'   => $request['prestador']['dadosBancario']['agencia'  ],
                'conta'     => $request['prestador']['dadosBancario']['conta'    ],
                'digito'    => $request['prestador']['dadosBancario']['digito'   ],
                'tipoconta' => $request['prestador']['dadosBancario']['tipoConta'],
            ]);
        }

        if ($request['prestador']['contato']['telefone'] != null && $request['prestador']['contato']['telefone'] != "") {
            $pessoa_telefones = PessoaTelefone::firstOrCreate([
                'pessoa'   => $prestador->pessoa,
                'telefone' => Telefone::firstOrCreate(
                    [
                        'telefone' => $request['prestador']['contato']['telefone'],
                    ]
                )->id,
            ]);
        }
        if ($request['prestador']['contato']['celular'] != null && $request['prestador']['contato']['celular'] != "") {
            $pessoa_telefones = PessoaTelefone::firstOrCreate([
                'pessoa'   => $prestador->pessoa,
                'telefone' => Telefone::firstOrCreate(
                    [
                        'telefone' => $request['prestador']['contato']['celular'],
                    ]
                )->id,
            ]);
        }

        $pessoa_emails = PessoaEmail::firstOrCreate([
            'pessoa' => $prestador->pessoa,
            'email'  => Email::firstOrCreate(
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
            'pessoa'   => $prestador->pessoa,
            'endereco' => Endereco::firstOrCreate(
                [
                    'cep'         => $request['prestador']['endereco']['cep'],
                    'cidade'      => ($cidade) ? $cidade->id : null,
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
            'pessoa'   => $prestador->pessoa,
        ]);
    }
}
