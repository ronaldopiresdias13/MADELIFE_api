<?php

namespace App\Http\Controllers\Api\Web\RecursosHumanos;

use App\Models\Dadosbancario;
use App\Models\Dadoscontratual;
use App\Models\Email;
use App\Models\Endereco;
use App\Http\Controllers\Controller;
use App\Models\Anexo;
use App\Models\Pessoa;
use App\Models\PessoaEmail;
use App\Models\PessoaEndereco;
use App\Models\PessoaTelefone;
use App\Models\Profissional;
use App\Models\ProfissionalBeneficio;
use App\Models\ProfissionalConvenio;
use App\Models\ProfissionalFormacao;
use App\Models\Telefone;
use App\Models\Tipopessoa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfissionaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        return Profissional::with(['pessoa.user.acessos', 'setor', 'cargo', 'dadoscontratual'])
            ->where('ativo', 1)
            ->where('empresa_id', $empresa_id)
            ->get();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function meuperfil(Request $request)
    {
        $user = $request->user();
        // return $user;
        return User::with(
            [
                'pessoa.telefones',
                'pessoa.emails',
                'pessoa.enderecos.cidade',
                'pessoa.dadosbancario.banco',
                'pessoa.profissional.dadoscontratual',
                'pessoa.profissional.beneficios',
                'pessoa.profissional.convenios.convenio',
                'pessoa.profissional.formacoes',
                'pessoa.profissional.cargo',
                'pessoa.profissional.setor',
                'acessos'
            ]
        )->find($user->id);
        // $empresa_id = $user->pessoa->profissional->empresa_id;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buscaProfissionaisInternosPagamento(Request $request)
    {
        $user = $request->user();
        // return $user;
        return Profissional::with(
            [
                'pessoa.dadosbancario.banco',
                'dadoscontratual',
                // 'pessoa.profissional.beneficios',
                // 'pessoa.profissional.convenios',
                'formacoes',
                // 'pessoa.profissional.cargo',
                'setor',
                // 'acessos'
            ]
        )->where('ativo', 1)
            ->where('empresa_id', $user->pessoa->profissional->empresa_id)->get();
        // $empresa_id = $user->pessoa->profissional->empresa_id;
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

        if ($request['data']['pessoa']) {
            $pessoa = Pessoa::where(
                'cpfcnpj',
                $request['data']['pessoa']['cpfcnpj']
            )->first();
        } elseif ($request['data']['pessoa_id']) {
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

        DB::transaction(function () use ($request, $empresa_id) {
            $profissional = Profissional::create([
                'pessoafisica' => 1,
                'empresa_id'   => $empresa_id,
                'pessoa_id'    => Pessoa::firstOrCreate(
                    [
                        'cpfcnpj'     => $request['data']['pessoa']['cpfcnpj'],
                    ],
                    [
                        'nome'        => $request['data']['pessoa']['nome'],
                        'nascimento'  => $request['data']['pessoa']['nascimento'],
                        'rgie'        => $request['data']['pessoa']['rgie'],
                        // 'observacoes' => $request['pessoa']['observacoes'],
                        'perfil'      => $request['data']['pessoa']['perfil'],
                        'status'      => $request['data']['pessoa']['status'],
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
                'secaotituloeleitor'     => $request['secaotituloeleitor'],
                'meiativa'               => $request['meiativa'],
                'dataverificacaomei'     => $request['dataverificacaomei'],
                'conselhoProfissional'   => $request['conselhoProfissional'],
                'numeroConselhoProfissional' => $request['numeroConselhoProfissional'],
                'cbos'                   => $request['cbos'],
                'uf'                     => $request['uf'],
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
                        'formacao_id'     => $formacao['id'],
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

            if ($request['data']['pessoa']['enderecos']) {
                foreach ($request['data']['pessoa']['enderecos'] as $key => $endereco) {
                    $pessoa_endereco = PessoaEndereco::firstOrCreate([
                        'pessoa_id'   => $profissional->pessoa_id,
                        'endereco_id' => Endereco::firstOrCreate(
                            [
                                'cep'         => $endereco['cep'],
                                'cidade_id'   => $endereco['cidade']['id'],
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

            if ($request['data']['pessoa']['telefones']) {
                foreach ($request['data']['pessoa']['telefones'] as $key => $telefone) {
                    if ($telefone['telefone']) {
                        PessoaTelefone::firstOrCreate([
                            'pessoa_id'   => $profissional->pessoa_id,
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
            }

            if ($request['data']['pessoa']['emails']) {
                foreach ($request['data']['pessoa']['emails'] as $key => $email) {
                    if ($email['email']) {
                        PessoaEmail::firstOrCreate([
                            'pessoa_id' => $profissional->pessoa_id,
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

            // return $request->documentos;

            if ($request['documentos']) {
                foreach ($request['documentos'] as $documento) {
                    $file = $request->file('anexo');
                    $documento = json_decode($file, true);
                    if ($file && $file->isValid()) {
                        $md5 = md5_file($file);
                        $caminho = 'anexos/';
                        $nome = $md5 . '.' . $file->extension();
                        $upload = $file->storeAs($caminho, $nome);
                        $nomeOriginal = $file->getClientOriginalName();

                        if ($upload) {
                             Anexo::create([
                                'anexo_id' => $profissional->id,
                                'anexo_type' => 'profissionais',
                                'caminho' => $caminho . '/' . $nome,
                                'nome'  => $nomeOriginal,
                                'descricao'  => $documento['anexo']['descricao']
                            ]);
                        }
                    }
                }
            }
        });

        // return response()->json('Profissional cadastrado com sucesso!', 200)->header('Content-Type', 'text/plain');

        return response()->json([
            'toast' => [
                'text'  => 'Profissional cadastrado com sucesso!',
                'color' => 'success',
                'duration' => 2000
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
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
        DB::transaction(function () use ($request, $profissional) {
            $profissional = Profissional::updateOrCreate(
                [
                    'id' => $request['id'],
                ],
                [
                    'pessoafisica' => 1,
                    'empresa_id'   => $request['empresa_id'],
                    'pessoa_id'    => Pessoa::updateOrCreate(
                        [
                            // 'id' => ($request['pessoa']['id'] != '') ? $request['pessoa']['id'] : null,
                            'id' => $request['pessoa_id'],
                        ],
                        [
                            // 'empresa_id'  => $request['pessoa']['empresa_id'],
                            'nome'        => $request['pessoa']['nome'],
                            'nascimento'  => $request['pessoa']['nascimento'],
                            'rgie'        => $request['pessoa']['rgie'],
                            // 'observacoes' => $request['pessoa']['observacoes'],
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
                    'secaotituloeleitor'     => $request['secaotituloeleitor'],
                    'meiativa'               => $request['meiativa'],
                    'dataverificacaomei'     => $request['dataverificacaomei'],
                    'conselhoProfissional'   => $request['conselhoProfissional'],
                    'numeroConselhoProfissional' => $request['numeroConselhoProfissional'],
                    'cbos'                   => $request['cbos'],
                    'uf'                     => $request['uf'],
                    'dadoscontratuais_id'    => Dadoscontratual::updateOrCreate([
                        'tiposalario'             => $request['dadoscontratuais']['tiposalario'],
                        'salario'                 => $request['dadoscontratuais']['salario'],
                        'cargahoraria'            => $request['dadoscontratuais']['cargahoraria'],
                        'insalubridade'           => $request['dadoscontratuais']['insalubridade'],
                        'percentualinsalubridade' => $request['dadoscontratuais']['percentualinsalubridade'],
                        'admissao'                => $request['dadoscontratuais']['admissao'],
                        'demissao'                => $request['dadoscontratuais']['demissao'],
                    ])->id,
                ]
            );
            if ($request['formacoes']) {
                foreach ($request['formacoes'] as $key => $formacao) {
                    $profissional_formacao = ProfissionalFormacao::firstOrCreate([
                        'profissional_id' => $profissional->id,
                        'formacao_id'     => $formacao['id'],
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
                        'empresa_id'  => $request['empresa_id'],
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
                                'cidade_id'   => $endereco['cidade']['id'],
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

            $pessoa = Pessoa::find($request['pessoa']['id']);

            foreach ($pessoa->telefones as $key => $telefone) {
                $pessoatelefone = Pessoatelefone::find($telefone->pivot->id);
                $pessoatelefone->delete();
            }

            if ($request['pessoa']['telefones']) {
                foreach ($request['pessoa']['telefones'] as $key => $telefone) {
                    if ($telefone['telefone']) {
                        PessoaTelefone::firstOrCreate(
                            [
                                'pessoa_id'   => $profissional->pessoa_id,
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
            }

            foreach ($pessoa->emails as $key => $email) {
                $pessoaemail = Pessoaemail::find($email->pivot->id);
                $pessoaemail->delete();
            }

            if ($request['pessoa']['emails']) {
                foreach ($request['pessoa']['emails'] as $key => $email) {
                    if ($email['email']) {
                        PessoaEmail::firstOrCreate([
                            'pessoa_id' => $profissional->pessoa_id,
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
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profissional  $profissional
     * @return \Illuminate\Http\Response
     */
    public function show(Profissional $profissional)
    {
        $profissional->pessoa->telefones;
        $profissional->pessoa->emails;
        $profissional->pessoa->dadosbancario;
        $profissional->formacoes;
        $profissional->setor;
        $profissional->cargo;
        $profissional->dadoscontratual;
        if ($profissional->beneficios) {
            foreach ($profissional->beneficios as $key => $beneficio) {
                $beneficio->beneficio;
            }
        }
        $profissional->convenios;
        if ($profissional->pessoa->enderecos) {
            foreach ($profissional->pessoa->enderecos as $key => $endereco) {
                $endereco->cidade;
            }
        }
        return $profissional;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profissional  $profissional
     * @return \Illuminate\Http\Response
     */
    public function atualizarFotoPerfil(Request $request)
    {
        $user = null;
        if (Auth::check()) {
            $user = Auth::user();
        }
        $file = $request['pessoa']['perfil'];
        $nome = md5_file($file);
        $extension = explode('/', mime_content_type($file))[1];
        $caminho = 'perfil/' . $user->pessoa->id . '/' . $nome . '.' . $extension;
        $upload = Storage::disk('local')->put($caminho, $file);
        if ($upload) {
            $user->pessoa->perfil = $caminho;
            $user->pessoa->save();

            return response()->json([
                'success' => [
                    'text' => 'Imagem de perfil salvo com sucesso!',
                    'duration' => 2000
                ]
            ], 200)
                ->header('Content-Type', 'application/json');
        } else {
            return response()->json([
                'error' => [
                    'text' => 'Não foi possivel salvar a imagem de perfil!'
                ]
            ], 400)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profissional  $profissional
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profissional $profissional)
    {
        $profissional->ativo = false;
        $profissional->save();
    }
}
