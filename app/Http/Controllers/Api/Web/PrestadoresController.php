<?php

namespace App\Http\Controllers\Api\Web;

use App\Models\Escala;
use App\Http\Controllers\Controller;
use App\Models\Conselho;
use App\Models\Dadosbancario;
use App\Models\EmpresaPrestador;
use App\Models\Endereco;
use App\Models\Pessoa;
use App\Models\PessoaEndereco;
use App\Models\PessoaTelefone;
use App\Models\Prestador;
use App\Models\Telefone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestadoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listNomePrestadores(Request $request)
    {
        $prestadores = DB::table('prestadores')
            ->join('pessoas', 'pessoas.id', '=', 'prestadores.pessoa_id')
            ->select('prestadores.id as value', 'pessoas.nome as label')
            ->where('prestadores.ativo', true)
            ->orderBy('pessoas.nome')
            ->get();
        return $prestadores;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listPrestadoresComFormacoes(Request $request)
    {
        $prestadores = Prestador::where('ativo', true)
            ->join('pessoas', 'pessoas.id', '=', 'prestadores.pessoa_id')
            ->select('prestadores.id', 'pessoas.nome')
            ->orderBy('pessoas.nome');
        // ->get();

        return $prestadores;
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
        //
    }


    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function buscaprestadoresporfiltro(Request $request)
    {
        // $user = $request->user();
        // $empresa_id = $user->pessoa->profissional->empresa->id;
        return Prestador::with(['formacoes', 'pessoa.conselhos', 'pessoa.enderecos' => function ($query) {
            $query->with('cidade');
        }])
            // ->whereHas('empresas', function (Builder $query) use ($empresa_id){
            //     $query->where('status', '=', 'Aprovado');
            //     $query->where('ativo', '=', 1);
            //     $query->where('empresa_id', $empresa_id);
            // })
            // ->join('formacoes', 'pessoas.id', '=', 'prestadores.pessoa_id')

            ->join('pessoas', 'pessoas.id', '=', 'prestadores.pessoa_id')
            ->leftJoin('conselhos', 'pessoas.id', '=', 'conselhos.pessoa_id')
            ->where('pessoas.nome', 'like', $request->nome ? '%' . $request->nome . '%' : '')
            ->where('prestadores.ativo', 1)
            ->orWhere('pessoas.cpfcnpj', 'like', $request->cpf ? $request->cpf : '')
            ->orWhere('conselhos.numero', 'like', $request->conselho ? $request->conselho : '')
            ->select('prestadores.*')
            ->get();
    }
    public function buscaprestadoresporfiltro2(Request $request)
    {
        $prestador = Prestador::with([
            'formacoes', 'pessoa', 'pessoa.conselhos', 'pessoa.enderecos' => function ($query) {
                $query->with('cidade');
            }
        ]);

        if ($request->nome) {
            $prestador->whereHas('pessoa', function (Builder $query) use ($request) {
                $query->where('nome', 'like', $request->nome ? '%' . $request->nome . '%' : '');
            });
        }
        if ($request->cpf) {
            $prestador->whereHas('pessoa', function (Builder $query) use ($request) {
                $query->where('cpfcnpj', 'like', $request->cpf ? $request->cpf : '');
            });
        }
        if ($request->conselho) {
            $prestador->whereHas('pessoa.conselhos', function (Builder $query) use ($request) {
                $query->where('numero', 'like', $request->conselho ? $request->conselho : '');
            });
        }
        $prestador->where('ativo', 1);
        $prestador = $prestador->get();
        return $prestador;
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function buscaprestadoresrecrutadosporfiltro(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa->id;
        $prestador = Prestador::with(['formacoes', 'pessoa.conselhos', 'pessoa.enderecos' => function ($query) {
            $query->with('cidade');
        }])
            ->whereHas('empresas', function (Builder $query) use ($empresa_id) {
                $query->where('status', '=', 'Aprovado');
                $query->where('ativo', '=', 1);
                $query->where('empresa_id', $empresa_id);
            });

        if ($request->nome) {
            $prestador->whereHas('pessoa', function (Builder $query) use ($request) {
                $query->where('nome', 'like', $request->nome ? '%' . $request->nome . '%' : '');
            });
        }
        if ($request->cpf) {
            $prestador->whereHas('pessoa', function (Builder $query) use ($request) {
                $query->where('cpfcnpj', 'like', $request->cpf ? $request->cpf : '');
            });
        }
        if ($request->conselho) {
            $prestador->whereHas('pessoa.conselhos', function (Builder $query) use ($request) {
                $query->where('numero', 'like', $request->conselho ? $request->conselho : '');
            });
        }
        $prestador->where('ativo', 1);
        $prestador = $prestador->get();
        return $prestador;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function historicopacientesprestador(Prestador $prestador, Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;

        $hoje = getdate();
        $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . $hoje['mday'];

        $escalas = Escala::where('prestador_id', $prestador->id)
            ->where('escalas.empresa_id', $empresa_id)
            ->where('escalas.ativo', true)
            ->whereBetween('dataentrada', [$request->data_ini ? $request->data_ini : $data, $request->data_fim ? $request->data_fim : $data])
            ->join('ordemservicos', 'ordemservicos.id', '=', 'escalas.ordemservico_id')
            ->join('orcamentos', 'orcamentos.id', '=', 'ordemservicos.orcamento_id')
            ->join('homecares', 'homecares.orcamento_id', '=', 'orcamentos.id')
            ->join('pacientes', 'homecares.paciente_id', '=', 'pacientes.id')
            ->join('pessoas', 'pacientes.pessoa_id', '=', 'pessoas.id')
            ->select('pessoas.nome')
            // ->where('homecares.ativo', true)
            ->groupBy('pessoas.nome')
            ->orderBy('pessoas.nome')
            ->get();
        return $escalas;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function buscalistadeconselhospodidpessoa(Pessoa $pessoa)
    {
        return Conselho::where('ativo', true)->where('pessoa_id', $pessoa->id)->get();
    }

    public function buscalistadetelefonespodidpessoa(Pessoa $pessoa)
    {
        return $pessoa->telefones;
    }

    public function buscalistadeenderecospodidpessoa(Pessoa $pessoa)
    {
        foreach ($pessoa->enderecos as $key => $endereco) {
            $endereco->cidade;
        }
        return $pessoa->enderecos;
    }

    public function buscalistadebancospodidpessoa(Pessoa $pessoa)
    {
        return Dadosbancario::with('banco')->where('ativo', true)->where('pessoa_id', $pessoa->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function salvarconselho(Request $request)
    {
        Conselho::create(
            [
                'instituicao' => $request['instituicao'],
                'numero'      => $request['numero'],
                'pessoa_id'   => $request['pessoa_id'],
                'ativo'   => $request['ativo'],
            ]
        );
    }
    // public function salvartelefone(Request $request)
    // {
    //     $user = $request->user();
    //     PessoaTelefone::firstOrCreate([
    //         'pessoa_id'   => $user->pessoa_id,
    //         'telefone_id' => Telefone::firstOrCreate(
    //             [
    //                 'telefone'  => $request['telefone'],
    //             ]
    //         )->id,
    //         'tipo'      => $request['pivot']['tipo'],
    //         'descricao' => $request['pivot']['descricao'],
    //     ]);
    // }

    public function salvarendereco(Request $request)
    {
        PessoaEndereco::firstOrCreate([
            'pessoa_id'   => $request['pessoa_id'],
            'endereco_id' => Endereco::create(
                [
                    'tipo'        => $request['tipo'],
                    'cep'         => $request['cep'],
                    'cidade_id'   => $request['cidade_id'],
                    'bairro'      => $request['bairro'],
                    'rua'         => $request['rua'],
                    'numero'      => $request['numero'],
                    'complemento' => $request['complemento'],
                    'descricao'   => $request['descricao'],

                ]
            )->id,
        ]);
    }

    public function salvarbanco(Request $request)
    {
        Dadosbancario::create(
            [
                'empresa_id' => $request['empresa_id'],
                'banco_id'   => $request['banco']['id'],
                'pessoa_id'  => $request['pessoa_id'],
                'agencia'    => $request['agencia'],
                'conta'      => $request['conta'],
                'digito'     => $request['digito'],
                'tipoconta'  => $request['tipoconta'],
                'cpfcnpj'    => $request['cpfcnpj'],
                'ativo'      => 1
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function deletarconselho(Conselho $conselho)
    {
        $conselho->ativo = false;
        $conselho->save();
    }

    public function deletarbanco(Dadosbancario $dadosbancario)
    {
        $dadosbancario->ativo = false;
        $dadosbancario->save();
    }

    public function deletartelefone(PessoaTelefone $pessoaTelefone)
    {
        $pessoaTelefone->ativo = false;
        $pessoaTelefone->save();
    }

    public function deletarendereco(PessoaEndereco $pessoaEndereco)
    {
        $pessoaEndereco->ativo = false;
        $pessoaEndereco->save();
    }
}
