<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Profissional;
use App\Pessoa;
use App\Formacao;
use App\ProfissionalFormacao;
use App\User;
use App\UserAcesso;
use App\Acesso;
use App\Dadosbancario;
use App\Banco;
use App\Telefone;
use App\PessoaTelefone;
use App\Email;
use App\PessoaEmail;
use App\Endereco;
use App\PessoaEndereco;
use App\Cidade;
use App\Horariotrabalho;
use App\Dadoscontratual;
use App\Beneficio;
use App\ProfissionalBeneficio;
use Illuminate\Http\Request;

class ProfissionaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Profissional::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $profissional = new Profissional;
        $profissional->pessoa = $request->pessoa;
        $profissional->pessoafisica = $request->pessoafisica;
        $profissional->sexo = $request->sexo;
        $profissional->setor = $request->setor;
        $profissional->cargo = $request->cargo;
        $profissional->pis = $request->pis;
        $profissional->numerocarteiratrabalho = $request->numerocarteiratrabalho;
        $profissional->numerocnh = $request->numerocnh;
        $profissional->categoriacnh = $request->categoriacnh;
        $profissional->validadecnh = $request->validadecnh;
        $profissional->numerotituloeleitor = $request->numerotituloeleitor;
        $profissional->zonatituloeleitor = $request->zonatituloeleitor;
        $profissional->secaotituloeleitor = $request->secaotituloeleitor;
        $profissional->dadoscontratuais = $request->dadoscontratuais;
        $profissional->save();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profissional  $profissional
     * @return \Illuminate\Http\Response
     */
    public function show(Profissional $profissional)
    {
        return $profissional;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profissional  $profissional
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profissional $profissional)
    {
        $profissional->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profissional  $profissional
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profissional $profissional)
    {
        $profissional->delete();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function migracao(Request $request)
    {

            // $table->string('secaotituloeleitor')->nullable();
            // $table->unsignedBigInteger('dadoscontratuais_id')->nullable();
            // $table->foreign('dadoscontratuais_id')->references('id')->on('dadoscontratuais')->onDelete('cascade');
        $profissional = Profissional::firstOrCreate([
            'pessoa_id' => Pessoa::firstOrCreate(
                [
                    'cpfcnpj' => $request['profissional']['dadosPf']['cpf']['numero'],
                ],
                [
                    'nome'        => $request['profissional']['dadosPf']['nome'],
                    'nascimento'  => $request['profissional']['dadosPf']['nascimento'],
                    'tipo'        => 'Profissional',
                    'rgie'        => $request['profissional']['dadosPf']['rg']['numero'],
                    'observacoes' => $request['profissional']['observacoes'],
                    'status'      => $request['profissional']['status'],
                ]
            )->id,
            'pessoafisica'           => true,
            'sexo'                   => $request['profissional']['dadosPf']['sexo'],
            'pis'                    => $request['profissional']['dadosProf']['pis'],
            'setor_id'               => null,
            'cargo_id'               => null,
            'numerocarteiratrabalho' => $request['profissional']['dadosProf']['numeroCarteiraTrabalho'],
            'numerocnh'              => $request['profissional']['dadosProf']['numeroCnh'],
            'categoriacnh'           => null,
            'validadecnh'            => $request['profissional']['dadosProf']['validadeCnh'],
            'numerotituloeleitor'    => $request['profissional']['dadosProf']['tituloEleitor'],
            'zonatituloeleitor'      => $request['profissional']['dadosProf']['zonaTitulo'],
            'secaotituloeleitor'     => $request['profissional']['dadosProf']['secaoTitulo'],
            'dadoscontratuais_id'    => Dadoscontratual::firstOrCreate([
                'tiposalario'             => $request['profissional']['dadosContratuais']['tipoSalario'],
                'salario'                 => $request['profissional']['dadosContratuais']['salario'],
                'cargahoraria'            => $request['profissional']['dadosContratuais']['horasMensais'],
                'insalubridade'           => 0,
                'percentualinsalubridade' => null,
                'admissao'                => $request['profissional']['dadosContratuais']['dataAdmissao'],
                'demissao'                => $request['profissional']['dadosContratuais']['dataDemissao']
            ])->id
        ]);
        if($request['profissional']['dadosProf']['formacao'] != null){
            $profissional_formacao = ProfissionalFormacao::firstOrCreate([
                'profissional_id' => $profissional->id,
                'formacao_id'  => Formacao::firstOrCreate(['descricao' => $request['profissional']['dadosProf']['formacao']['descricao']])->id,
            ]);
        }
        
        
        // $usercpf = User::firstWhere(
        //     'cpfcnpj' , $request['profissional']['dadosPf']['cpf']['numero']
        // );
        // $useremail = User::firstWhere(
        //     'email', $request['profissional']['contato']['email']
        // );

        // if ($usercpf || $useremail) {
            // foreach ($request['conta']['grupos'] as $key => $acesso) {
            //     $a = UserAcesso::updateOrCreate(
            //         ['user_id'  => $usercpf->id, 'acesso_id' => Acesso::firstOrCreate(['nome' => $acesso])->id]
            //     );
            // }
        // } else {
            foreach ($request['conta']['grupos'] as $key => $value) {
                $teste = UserAcesso::firstOrCreate([
                    'user_id'  => $user = User::firstOrCreate([
                        'cpfcnpj' => $request['profissional']['dadosPf']['cpf']['numero'],
                        'email'   => $request['profissional']['contato']['email'],
                        'pessoa_id'  => $profissional->pessoa_id,
                        'password' => bcrypt($request['conta']['senha']),
                    ])->id, 
                    'acesso_id' => Acesso::firstOrCreate(['nome' => $value])->id]
                );
            }
        // }
        
        foreach ($request['profissional']['horarioTrabalho'] as $key => $hora) {
            $horario = Horariotrabalho::firstOrCreate([
                'diasemana'       => $hora['diaSemana'],
                'horarioentrada'  => $hora['horarioEntrada'],
                'horariosaida'    => $hora['intervaloSaida'],
                'profissional_id' =>$profissional->id
            ]);
        }
        foreach ($request['profissional']['dadosContratuais']['beneficios'] as $key => $beneficio) {
            $profissional_beneficios = ProfissionalBeneficio::firstOrCreate([
                'profissional_id' =>$profissional->id,
                'beneficio_id'    => Beneficio::firstOrCreate([
                    'descricao'  => $beneficio['beneficios'],
                    // 'valor'      => $beneficio['valor'],
                    'empresa_id' => 1,
                ])->id
            ]);
        }
        if($request['profissional']['dadosBancario']['banco'] != null && $request['profissional']['dadosBancario']['banco']['codigo'] != null){
            $dados_bancario = Dadosbancario::firstOrCreate([
                'banco_id' => Banco::firstOrCreate(
                    [
                        'codigo' => ($request['profissional']['dadosBancario']['banco']['codigo'] == null || $request['profissional']['dadosBancario']['banco']['codigo'] == "") ? '000' : $request['profissional']['dadosBancario']['banco']['codigo'],
                    ],
                    [
                        'descricao' => ($request['profissional']['dadosBancario']['banco']['codigo'] == null || $request['profissional']['dadosBancario']['banco']['codigo'] == "") ? 'Outros' : $request['profissional']['dadosBancario']['banco']['descricao']
                    ]
                )->id,
                'pessoa_id'    => $profissional->pessoa_id,
                'agencia'   => $request['profissional']['dadosBancario']['agencia'  ],
                'conta'     => $request['profissional']['dadosBancario']['conta'    ],
                'digito'    => $request['profissional']['dadosBancario']['digito'   ],
                'tipoconta' => $request['profissional']['dadosBancario']['tipoConta'],
            ]);
        }

        if ($request['profissional']['contato']['telefone'] != null && $request['profissional']['contato']['telefone'] != "") {
            $pessoa_telefones = PessoaTelefone::firstOrCreate([
                'pessoa_id'   => $profissional->pessoa_id,
                'telefone_id' => Telefone::firstOrCreate(
                    [
                        'telefone' => $request['profissional']['contato']['telefone'],
                    ]
                )->id,
            ]);
        }
        if ($request['profissional']['contato']['celular'] != null && $request['profissional']['contato']['celular'] != "") {
            $pessoa_telefones = PessoaTelefone::firstOrCreate([
                'pessoa_id'   => $profissional->pessoa_id,
                'telefone_id' => Telefone::firstOrCreate(
                    [
                        'telefone' => $request['profissional']['contato']['celular'],
                    ]
                )->id,
            ]);
        }
        if($request['profissional']['contato']['email']){
            $pessoa_emails = PessoaEmail::firstOrCreate([
                'pessoa_id' => $profissional->pessoa_id,
                'email_id'  => Email::firstOrCreate(
                    [
                        'email' => $request['profissional']['contato']['email'],
                    ],
                    [
                        'tipo' => 'pessoal',
                    ]
                )->id,
            ]);
        }
        
        
        $cidade = Cidade::where('nome', $request['profissional']['endereco']['cidade'])->where('uf', $request['profissional']['endereco']['uf'])->first();

        $pessoa_endereco = PessoaEndereco::firstOrCreate([
            'pessoa_id'   => $profissional->pessoa_id,
            'endereco_id' => Endereco::firstOrCreate(
                [
                    'cep'         => $request['profissional']['endereco']['cep'],
                    'cidade_id'   => ($cidade) ? $cidade->id : null,
                    'rua'         => $request['profissional']['endereco']['rua'],
                    'bairro'      => $request['profissional']['endereco']['bairro'],
                    'numero'      => $request['profissional']['endereco']['numero'],
                    'complemento' => $request['profissional']['endereco']['complemento'],
                    'tipo'        => 'Residencial',
                ]
            )->id,
        ]);
    }
}
