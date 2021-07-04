<?php

namespace App\Http\Controllers\Api\Web;

use App\Models\Email;
use App\Models\Empresa;
use App\Models\Endereco;
use App\Http\Controllers\Controller;
use App\Models\Ordemservico;
use App\Models\Paciente;
use App\Models\Pessoa;
use App\Models\PessoaEmail;
use App\Models\PessoaEndereco;
use App\Models\PessoaTelefone;
use App\Models\Telefone;
use App\Models\Tipopessoa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

class PacientesController extends Controller
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

        $pacientes = Paciente::with([
            'pessoa.emails', 'pessoa.telefones', 'pessoa.enderecos.cidade', 'pessoa.pacientes.internacoes',
            'responsavel.pessoa:id,nome', 'pessoa.user.acessos'
        ]);

        if ($request->data_final) {
            $pacientes = $pacientes->whereHas('internacoes', function (Builder $query) use ($request) {
                $query->where('data_final', null, $request->data_final);
            });
        };
        $pacientes->where('empresa_id', $empresa_id);
        $pacientes->where('ativo', true);
        $pacientes = $pacientes->get();
        return $pacientes;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listNomePacientes(Request $request)
    {
        $user = $request->user();
        $profissional = $user->pessoa->profissional;

        $pacientes = DB::table('ordemservicos')
            ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
            ->join('homecares', 'homecares.orcamento_id', '=', 'orcamentos.id')
            ->join('pacientes', 'pacientes.id', '=', 'homecares.orcamento_id')
            ->join('pessoas', 'pessoas.id', '=', 'pacientes.pessoa_id')
            ->where('ordemservicos.ativo', true)
            ->where('ordemservicos.empresa_id', $profissional->empresa_id)
            ->select('ordemservicos.id as value', 'pessoas.nome as label')
            ->orderBy('pessoas.nome')
            ->get();
        return $pacientes;

        // $escalas = Ordemservico::with([
        //     'orcamento' => function ($query) {
        //         $query->select('id', 'cliente_id');
        //         $query->with(['homecare' => function ($query) {
        //             $query->select('id', 'orcamento_id', 'paciente_id');
        //             $query->with(['paciente' => function ($query) {
        //                 $query->select('id', 'pessoa_id');
        //                 $query->with(['pessoa' => function ($query) {
        //                     $query->select('id', 'nome');
        //                 }]);
        //             }]);
        //         }]);
        //         $query->with(['cliente' => function ($query) {
        //             $query->select('id', 'pessoa_id');
        //             $query->with(['pessoa' => function ($query) {
        //                 $query->select('id', 'nome');
        //             }]);
        //         }]);
        //     }
        // ])
        //     ->where('empresa_id', $profissional->empresa_id)
        //     ->where('ativo', true)
        //     ->get(['id', 'orcamento_id']);

        // return $escalas;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $empresa = $request->user()->pessoa->profissional->empresa;
        $qtdPac = Paciente::where('empresa_id', $empresa->id)->where('ativo', true)->count();
        if ($qtdPac < $empresa->quantidadepaciente) {
            DB::transaction(function () use ($request) {
                $paciente = Paciente::create([
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
                            'status'      => $request['pessoa']['status'],
                        ]
                    )->id,
                    'responsavel_id' => $request['responsavel_id'],
                    'complexidade'   => $request['complexidade'],
                    'numeroCarteira' => $request['numeroCarteira'],
                    'sexo'           => $request['sexo'],
                    'ativo'          => $request['ativo'],
                    'atendimentoRN'  => $request['atendimentoRN']
                ]);
                Tipopessoa::create([
                    'tipo'      => 'Paciente',
                    'pessoa_id' => $paciente->pessoa_id,
                    'ativo'     => 1
                ]);
                if ($request['pessoa']['telefones']) {
                    foreach ($request['pessoa']['telefones'] as $key => $telefone) {
                        if ($telefone['telefone']) {
                            PessoaTelefone::firstOrCreate([
                                'pessoa_id'   => $paciente->pessoa_id,
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
                if ($request['pessoa']['enderecos']) {
                    foreach ($request['pessoa']['enderecos'] as $key => $endereco) {
                        PessoaEndereco::firstOrCreate([
                            'pessoa_id'   => $paciente->pessoa_id,
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
                if ($request['pessoa']['emails']) {
                    foreach ($request['pessoa']['emails'] as $key => $email) {
                        PessoaEmail::firstOrCreate([
                            'pessoa_id' => $paciente->pessoa_id,
                            'email_id'  => Email::firstOrCreate(
                                [
                                    'email'     => $email['email'],
                                ]
                            )->id,
                            'tipo'       => $email['pivot']['tipo'],
                            'descricao'  => $email['pivot']['descricao']
                        ]);
                    }
                }
            });
        } else {
            return response()->json([
                'alert' => [
                    'title' => 'Ops, não foi possível salvar',
                    'text' => 'Quantidade máxima de pacientes atingida!'
                ]
            ], 400)
                ->header('Content-Type', 'application/json');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function show(Paciente $paciente)
    {
        $paciente->pessoa;
        $paciente->pessoa->telefones;
        $paciente->pessoa->telefones;
        $paciente->pessoa->emails;
        if ($paciente->pessoa->enderecos) {
            foreach ($paciente->pessoa->enderecos as $key => $endereco) {
                $endereco->cidade;
            }
        }

        return $paciente;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paciente $paciente)
    {
        DB::transaction(function () use ($request, $paciente) {
            $paciente->update([
                'sexo'           => $request['sexo'],
                'empresa_id'     => $request['empresa_id'],
                'responsavel_id' => $request['responsavel_id'],
                'numeroCarteira' => $request['numeroCarteira'],
                'complexidade'   => $request['complexidade'],
                'ativo'          => $request['ativo'],
                'atendimentoRN'  => $request['atendimentoRN']
            ]);
            $pessoa = Pessoa::find($request['pessoa']['id']);
            if ($pessoa) {
                $pessoa->update([
                    'nome'        => $request['pessoa']['nome'],
                    'nascimento'  => $request['pessoa']['nascimento'],
                    'cpfcnpj'     => $request['pessoa']['cpfcnpj'],
                    'rgie'        => $request['pessoa']['rgie'],
                    'observacoes' => $request['pessoa']['observacoes'],
                    'perfil'      => $request['pessoa']['perfil'],
                    'status'      => $request['pessoa']['status'],
                ]);
            }
            if ($request['pessoa']['telefones']) {
                foreach ($request['pessoa']['telefones'] as $key => $telefone) {
                    $pessoa_telefone = PessoaTelefone::firstOrCreate(
                        [
                            'pessoa_id'   => $pessoa->id,
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
            if ($request['pessoa']['enderecos']) {
                foreach ($request['pessoa']['enderecos'] as $key => $endereco) {
                    $pessoa_endereco = PessoaEndereco::updateOrCreate(
                        [
                            'pessoa_id'   => $pessoa->id,
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
                        ]
                    );
                }
            }
            if ($request['pessoa']['emails']) {
                foreach ($request['pessoa']['emails'] as $key => $email) {
                    $pessoa_email = PessoaEmail::updateOrCreate(
                        [
                            'pessoa_id' => $pessoa->id,
                            'email_id'  => Email::firstOrCreate(
                                [
                                    'email' => $email['email'],
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
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paciente $paciente)
    {
        $paciente->ativo = false;
        $paciente->save();
    }
}
