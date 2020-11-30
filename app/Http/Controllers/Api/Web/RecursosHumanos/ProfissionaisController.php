<?php

namespace App\Http\Controllers\Api\Web\RecursosHumanos;

use App\Dadosbancario;
use App\Dadoscontratual;
use App\Email;
use App\Endereco;
use App\Http\Controllers\Controller;
use App\Pessoa;
use App\PessoaEmail;
use App\PessoaEndereco;
use App\PessoaTelefone;
use App\Profissional;
use App\ProfissionalBeneficio;
use App\ProfissionalConvenio;
use App\ProfissionalFormacao;
use App\Telefone;
use App\Tipopessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfissionaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novoProfissional(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'alert' => [
                    'title' => 'Ops!',
                    'text'  => 'Sua sessão expirou você será direcionado para a tela de login!'
                ]
            ], 401)
                ->header('Content-Type', 'application/json');
        }

        $empresa_id = Auth::user()->pessoa->profissional->empresa_id;

        if ($request['pessoa']) {
            $pessoa = Pessoa::where(
                'cpfcnpj',
                $request['pessoa']['cpfcnpj']
            )->first();
        } elseif ($request['pessoa_id']) {
            $pessoa = Pessoa::find($request['pessoa_id']);
        }

        $profissional = null;

        if ($pessoa) {
            $profissional = Profissional::firstWhere(
                'pessoa_id',
                $pessoa->id,
            );
        }

        if ($profissional) {
            return response()->json('Profissional já existe!', 400)->header('Content-Type', 'text/plain');
        }

        DB::transaction(function () use ($request, $profissional, $empresa_id) {
            $profissional = Profissional::create([
                'pessoafisica' => 1,
                'empresa_id'   => $empresa_id,
                'pessoa_id'    => Pessoa::firstOrCreate(
                    [
                        'cpfcnpj'     => $request['pessoa']['cpfcnpj'],
                    ],
                    [
                        'nome'        => $request['pessoa']['nome'],
                        'nascimento'  => $request['pessoa']['nascimento'],
                        'rgie'        => $request['pessoa']['rgie'],
                        'observacoes' => $request['pessoa']['observacoes'],
                        'perfil'      => $request['pessoa']['perfil'],
                        'status'      => $request['pessoa']['status'],
                    ]
                )->id,
                'sexo'                   => $request['sexo'],
                'setor_id'               => $request['setor_id'],
                'cargo_id'               => $request['cargo_id'],
                'pis'                    => $request['pis'],
                'numerocarteiratrabalho' => $request['numerocarteiratrabalho'],
                'numerocnh'              => $request['numerocnh'],
                'categoriacnh'           => $request['categoriacnh'],
                'validadecnh'            => $request['validadecnh'],
                'numerotituloeleitor'    => $request['numerotituloeleitor'],
                'zonatituloeleitor'      => $request['zonatituloeleitor'],
                'meiativa'               => $request['meiativa'],
                'dataverificacaomei'     => $request['dataverificacaomei'],
                'dadoscontratuais_id'    => Dadoscontratual::create([
                    'tiposalario'             => $request['dadoscontratuais']['tiposalario'],
                    'salario'                 => $request['dadoscontratuais']['salario'],
                    'cargahoraria'            => $request['dadoscontratuais']['cargahoraria'],
                    'insalubridade'           => $request['dadoscontratuais']['insalubridade'],
                    'percentualinsalubridade' => $request['dadoscontratuais']['percentualinsalubridade'],
                    'admissao'                => $request['dadoscontratuais']['admissao'],
                    'demissao'                => $request['dadoscontratuais']['demissao'],
                ])->id,
            ]);
            $tipopessoa = Tipopessoa::create([
                'tipo'      => 'Profissional',
                'pessoa_id' => $profissional->pessoa_id,
                'ativo'     => 1
            ]);
            if ($request['formacoes']) {
                foreach ($request['formacoes'] as $key => $formacao) {
                    $profissional_formacao = ProfissionalFormacao::firstOrCreate([
                        'profissional_id' => $profissional->id,
                        'formacao_id'     => $formacao['formacao_id'],
                    ]);
                }
            }
            if ($request['beneficios']) {
                foreach ($request['beneficios'] as $key => $beneficio) {
                    $profissional_beneficio = ProfissionalBeneficio::firstOrCreate([
                        'profissional_id' => $profissional->id,
                        'beneficio_id'    => $beneficio['beneficio_id']
                    ]);
                }
            }
            if ($request['convenios']) {
                foreach ($request['convenios'] as $key => $convenio) {
                    $profissional_convenio = ProfissionalConvenio::firstOrCreate([
                        'profissional_id' => $profissional->id,
                        'convenio_id'    => $convenio['convenio_id']
                    ]);
                }
            }
            if ($request['dadosBancario']) {
                foreach ($request['dadosBancario'] as $key => $dadosbancario) {
                    $dados_bancario = Dadosbancario::firstOrCreate([
                        'empresa_id'  => $empresa_id,
                        'banco_id'    => $dadosbancario['banco_id'],
                        'agencia'     => $dadosbancario['agencia'],
                        'conta'       => $dadosbancario['conta'],
                        'digito'      => $dadosbancario['digito'],
                        'tipoconta'   => $dadosbancario['tipoconta'],
                        'pessoa_id'   => $profissional->pessoa_id,
                    ]);
                }
            }

            if ($request['pessoa']['enderecos']) {
                foreach ($request['pessoa']['enderecos'] as $key => $endereco) {
                    $pessoa_endereco = PessoaEndereco::firstOrCreate([
                        'pessoa_id'   => $profissional->pessoa_id,
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
                    ]);
                }
            }

            if ($request['pessoa']['telefones']) {
                foreach ($request['pessoa']['telefones'] as $key => $telefone) {
                    $pessoa_telefone = PessoaTelefone::firstOrCreate([
                        'pessoa_id'   => $profissional->pessoa_id,
                        'telefone_id' => Telefone::firstOrCreate(
                            [
                                'telefone'  => $telefone['telefone'],
                            ]
                        )->id,
                        'tipo'      => $telefone['tipo'],
                        'descricao' => $telefone['descricao'],
                    ]);
                }
            }

            if ($request['pessoa']['emails']) {
                foreach ($request['pessoa']['emails'] as $key => $email) {
                    $pessoa_email = PessoaEmail::firstOrCreate([
                        'pessoa_id' => $profissional->pessoa_id,
                        'email_id'  => Email::firstOrCreate(
                            [
                                'email' => $email['email'],
                            ]
                        )->id,
                        'tipo'      => $email['tipo'],
                        'descricao' => $email['descricao'],
                    ]);
                }
            }
        });

        // return response()->json('Profissional cadastrado com sucesso!', 200)->header('Content-Type', 'text/plain');

        return response()->json([
            'toast' => [
                'text'  => 'Profissional cadastrado com sucesso!'
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profissional  $profissional
     * @return \Illuminate\Http\Response
     */
    public function show(Profissional $profissional)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profissional  $profissional
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profissional $profissional)
    {
        //
    }
}