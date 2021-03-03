<?php

namespace App\Http\Controllers\Web\Prestadores;

use App\Http\Controllers\Controller;
use App\Models\Prestador;
use App\Models\Tipopessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestadoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listRecrutamento(Request $request)
    {
        // if ($request['nome']) {
        //     return 'true';
        // }
        // return 'false';
        // return $request;
        $result = DB::table('prestadores')
            ->leftJoin('prestador_formacao', function ($join) {
                $join->on('prestadores.id', '=', 'prestador_formacao.prestador_id')
                    ->where('prestador_formacao.ativo', true);
            })
            ->leftJoin('formacoes', function ($join) {
                $join->on('formacoes.id', '=', 'prestador_formacao.formacao_id')
                    ->where('formacoes.deleted_at', null);
            })
            ->join('pessoas', function ($join) use ($request) {
                $join->on('prestadores.pessoa_id', '=', 'pessoas.id')
                    ->where('pessoas.ativo', true)
                    ->where('pessoas.nome', 'like', $request['nome'] ? '%' . $request['nome'] . '%' : '%');
            })
            ->leftJoin('pessoa_endereco', function ($join) {
                $join->on('pessoa_endereco.pessoa_id', '=', 'pessoas.id')
                    ->where('pessoa_endereco.ativo', true);
            })
            ->leftJoin('enderecos', function ($join) {
                $join->on('enderecos.id', '=', 'pessoa_endereco.endereco_id')
                    ->where('enderecos.ativo', true);
            })
            ->leftJoin('cidades', function ($join) {
                $join->on('cidades.id', '=', 'enderecos.cidade_id')
                    ->where('cidades.ativo', true);
            })
            ->leftJoin('conselhos', function ($join) {
                $join->on('conselhos.id', '=', 'conselhos.pessoa_id')
                    ->where('conselhos.ativo', true);
            })
            ->where('prestadores.ativo', true)
            ->select(
                'prestadores.id as id',
                'pessoas.nome as nome',
                // 'pessoas.nascimento as nascimento',
                // 'pessoas.cpfcnpj as cpfcnpj',
                // 'pessoas.rgie as rgie',
                // 'pessoas.observacoes as observacoes',
                // 'pessoas.perfil as perfil',
                // 'prestadores.sexo as sexo',
                'cidades.nome as cidade',
                'cidades.uf as uf',
                // 'enderecos.cep as cep',
                // 'enderecos.bairro as bairro',
                // 'enderecos.rua as logradouro',
                // 'enderecos.numero as numero',
                // 'enderecos.complemento as complemento',
                // 'enderecos.tipo as tipo',
                'formacoes.descricao as formacao',
                'conselhos.instituicao as conselho',
                'conselhos.uf as conselho_uf',
                'conselhos.numero as conselho_numero',


                // // Select pessoas
                // 'pessoas.id as pessoas_id',
                // 'pessoas.nome as pessoas_nome',
                // 'pessoas.nascimento as pessoas_nascimento',
                // 'pessoas.cpfcnpj as pessoas_cpfcnpj',
                // 'pessoas.rgie as pessoas_rgie',
                // 'pessoas.observacoes as pessoas_observacoes',
                // 'pessoas.perfil as pessoas_perfil',
                // 'pessoas.status as pessoas_status',
                // 'pessoas.ativo as pessoas_ativo',
                // 'pessoas.created_at as pessoas_created_at',
                // 'pessoas.updated_at as pessoas_updated_at',
                // // Select prestadores
                // 'prestadores.id as prestadores_id',
                // 'prestadores.fantasia as prestadores_fantasia',
                // 'prestadores.sexo as prestadores_sexo',
                // 'prestadores.pis as prestadores_pis',
                // 'prestadores.cargo_id as prestadores_cargo_id',
                // 'prestadores.curriculo as prestadores_curriculo',
                // 'prestadores.certificado as prestadores_certificado',
                // 'prestadores.meiativa as prestadores_meiativa',
                // 'prestadores.dataverificacaomei as prestadores_dataverificacaomei',
                // 'prestadores.ativo as prestadores_ativo',
                // 'prestadores.created_at as prestadores_created_at',
                // 'prestadores.updated_at as prestadores_updated_at',
                // // Select formacoes
                // 'formacoes.id as formacoes_id',
                // 'formacoes.descricao as formacoes_descricao',
                // 'formacoes.created_at as formacoes_created_at',
                // 'formacoes.updated_at as formacoes_updated_at',
                // 'formacoes.deleted_at as formacoes_deleted_at',
                // // Select enderecos
                // 'enderecos.id as enderecos_id',
                // 'enderecos.descricao as enderecos_descricao',
                // 'enderecos.cep as enderecos_cep',
                // 'enderecos.cidade_id as enderecos_cidade_id',
                // 'enderecos.rua as enderecos_rua',
                // 'enderecos.bairro as enderecos_bairro',
                // 'enderecos.numero as enderecos_numero',
                // 'enderecos.complemento as enderecos_complemento',
                // 'enderecos.tipo as enderecos_tipo',
                // 'enderecos.ativo as enderecos_ativo',
                // 'enderecos.created_at as enderecos_created_at',
                // 'enderecos.updated_at as enderecos_updated_at',
                // // Select cidades
                // 'cidades.id as cidades_id',
                // 'cidades.nome as cidades_nome',
                // 'cidades.uf as cidades_uf',
                // 'cidades.ativo as cidades_ativo',
                // 'cidades.created_at as cidades_created_at',
                // 'cidades.updated_at as cidades_updated_at',
                // // Select conselhos
                // 'conselhos.id as conselhos_id',
                // 'conselhos.instituicao as conselhos_instituicao',
                // 'conselhos.uf as conselhos_uf',
                // 'conselhos.numero as conselhos_numero',
                // 'conselhos.pessoa_id as conselhos_pessoa_id',
                // 'conselhos.ativo as conselhos_ativo',
                // 'conselhos.created_at as conselhos_created_at',
                // 'conselhos.updated_at as conselhos_updated_at',
            )
            ->groupBy(
                'prestadores.id',
                'pessoas.nome',
                // 'pessoas.nascimento',
                // 'pessoas.cpfcnpj',
                // 'pessoas.rgie',
                // 'pessoas.observacoes',
                'pessoas.perfil',
                // 'prestadores.sexo',
                'cidades.nome',
                'cidades.uf',
                // 'enderecos.cep',
                // 'enderecos.bairro',
                // 'enderecos.rua',
                // 'enderecos.numero',
                // 'enderecos.complemento',
                // 'enderecos.tipo',
                'formacoes.descricao',
                'conselhos.instituicao',
                'conselhos.uf',
                'conselhos.numero',
            )
            ->orderBy('nome');

        if ($request['formacao_id']) {
            $result->where('formacoes.id', $request['formacao_id']);
        }
        if ($request['cidade_id']) {
            $result->where('cidades.id', $request['cidade_id']);
        }
        if ($request['uf']) {
            $result->where('cidades.uf', $request['uf']);
        }

        // return 'teste';

        $result = $result->paginate(10);
        // $result = $result->get();

        if (env("APP_ENV", 'production') == 'production') {
            return $result->withPath(str_replace('http:', 'https:', $result->path()));
        } else {
            return $result;
        }




        // $result = DB::table('pessoas')
        //     ->crossJoin('tipopessoas', 'pessoas.id', '=', 'tipopessoas.pessoa_id')
        //     ->crossJoin('prestadores', 'pessoas.id', '=', 'prestadores.pessoa_id')
        //     ->crossJoin('prestador_formacao', 'prestadores.id', '=', 'prestador_formacao.prestador_id')
        //     ->crossJoin('formacoes', 'formacoes.id', '=', 'prestador_formacao.formacao_id')
        //     ->crossJoin('pessoa_endereco', 'pessoas.id', '=', 'pessoa_endereco.pessoa_id')
        //     ->crossJoin('enderecos', 'enderecos.id', '=', 'pessoa_endereco.endereco_id')
        //     ->crossJoin('cidades', 'cidades.id', '=', 'enderecos.cidade_id')
        //     ->crossJoin('conselhos', 'pessoas.id', '=', 'conselhos.pessoa_id')
        //     ->where([
        //         // Wheres ativos
        //         ['tipopessoas.ativo', true],
        //         ['pessoas.ativo', true],
        //         ['prestadores.ativo', true],
        //         ['prestador_formacao.ativo', true],
        //         ['formacoes.deleted_at', null],
        //         ['pessoa_endereco.ativo', true],
        //         ['enderecos.ativo', true],
        //         ['cidades.ativo', true],
        //         ['conselhos.ativo', true],
        //         // Wheres condicionais
        //         ['tipopessoas.tipo', 'prestador'],
        //         // ['formacoes.id', ''],
        //         // ['cidades.id', ''],
        //         ['pessoas.nome', 'like', 'BIANCA%']
        //     ])
        //     ->select(
        //         'pessoas.id as id',
        //         'pessoas.nome as nome',
        //         'pessoas.nascimento as nascimento',
        //         'pessoas.cpfcnpj as cpfcnpj',
        //         'pessoas.rgie as rgie',
        //         'pessoas.observacoes as observacoes',
        //         'pessoas.perfil as perfil',
        //         // 'pessoas.status as status',
        //         'prestadores.sexo as sexo',
        //         'cidades.nome as cidade',
        //         'cidades.uf as uf',
        //         'enderecos.cep as cep',
        //         'enderecos.bairro as bairro',
        //         'enderecos.rua as logradouro',
        //         'enderecos.numero as numero',
        //         'enderecos.complemento as complemento',
        //         'enderecos.tipo as tipo',
        //         'formacoes.descricao as formacao',
        //         'conselhos.instituicao as conselho',
        //         'conselhos.uf as conselho_uf',
        //         'conselhos.numero as conselho_numero',


        //         // 'tipopessoas.t',
        //         // // Select pessoas
        //         // 'pessoas.id as pessoas_id',
        //         // 'pessoas.nome as pessoas_nome',
        //         // 'pessoas.nascimento as pessoas_nascimento',
        //         // 'pessoas.cpfcnpj as pessoas_cpfcnpj',
        //         // 'pessoas.rgie as pessoas_rgie',
        //         // 'pessoas.observacoes as pessoas_observacoes',
        //         // 'pessoas.perfil as pessoas_perfil',
        //         // 'pessoas.status as pessoas_status',
        //         // 'pessoas.ativo as pessoas_ativo',
        //         // 'pessoas.created_at as pessoas_created_at',
        //         // 'pessoas.updated_at as pessoas_updated_at',
        //         // // Select prestadores
        //         // 'prestadores.id as prestadores_id',
        //         // 'prestadores.fantasia as prestadores_fantasia',
        //         // 'prestadores.sexo as prestadores_sexo',
        //         // 'prestadores.pis as prestadores_pis',
        //         // 'prestadores.cargo_id as prestadores_cargo_id',
        //         // 'prestadores.curriculo as prestadores_curriculo',
        //         // 'prestadores.certificado as prestadores_certificado',
        //         // 'prestadores.meiativa as prestadores_meiativa',
        //         // 'prestadores.dataverificacaomei as prestadores_dataverificacaomei',
        //         // 'prestadores.ativo as prestadores_ativo',
        //         // 'prestadores.created_at as prestadores_created_at',
        //         // 'prestadores.updated_at as prestadores_updated_at',
        //         // // Select formacoes
        //         // 'formacoes.id as formacoes_id',
        //         // 'formacoes.descricao as formacoes_descricao',
        //         // 'formacoes.created_at as formacoes_created_at',
        //         // 'formacoes.updated_at as formacoes_updated_at',
        //         // 'formacoes.deleted_at as formacoes_deleted_at',
        //         // // Select enderecos
        //         // 'enderecos.id as enderecos_id',
        //         // 'enderecos.descricao as enderecos_descricao',
        //         // 'enderecos.cep as enderecos_cep',
        //         // 'enderecos.cidade_id as enderecos_cidade_id',
        //         // 'enderecos.rua as enderecos_rua',
        //         // 'enderecos.bairro as enderecos_bairro',
        //         // 'enderecos.numero as enderecos_numero',
        //         // 'enderecos.complemento as enderecos_complemento',
        //         // 'enderecos.tipo as enderecos_tipo',
        //         // 'enderecos.ativo as enderecos_ativo',
        //         // 'enderecos.created_at as enderecos_created_at',
        //         // 'enderecos.updated_at as enderecos_updated_at',
        //         // // Select cidades
        //         // 'cidades.id as cidades_id',
        //         // 'cidades.nome as cidades_nome',
        //         // 'cidades.uf as cidades_uf',
        //         // 'cidades.ativo as cidades_ativo',
        //         // 'cidades.created_at as cidades_created_at',
        //         // 'cidades.updated_at as cidades_updated_at',
        //         // // Select conselhos
        //         // 'conselhos.id as conselhos_id',
        //         // 'conselhos.instituicao as conselhos_instituicao',
        //         // 'conselhos.uf as conselhos_uf',
        //         // 'conselhos.numero as conselhos_numero',
        //         // 'conselhos.pessoa_id as conselhos_pessoa_id',
        //         // 'conselhos.ativo as conselhos_ativo',
        //         // 'conselhos.created_at as conselhos_created_at',
        //         // 'conselhos.updated_at as conselhos_updated_at',
        //     )
        //     // ->limit(10)

        //     // ->groupBy('id', 'sexo', 'formacao', 'uf', 'cidade', 'cep', 'bairro', 'numero', 'complemento', 'tipo', 'logradouro', 'conselho', 'conselho_uf', 'conselho_numero')
        //     ->groupBy('pessoas.id', 'tipopessoas.id', 'prestadores.id', 'formacoes.id', 'enderecos.id', 'cidades.id', 'conselhos.id')
        //     // ->groupByRaw(pessoas.id, prestadores.id, formacoes.id, enderecos.id, cidades.id, conselhos.id')
        //     ->orderBy('nome')
        //     // ->paginate(10);
        //     ->get();

        // // dd($result);

        // if (env("APP_ENV", 'production') == 'production') {
        //     return $result->withPath(str_replace('http:', 'https:', $result->path()));
        // } else {
        //     return $result;
        // }

        // $result = Tipopessoa::with([
        //     'pessoa.prestador.formacoes',
        //     'pessoa.enderecos.cidade',
        //     'pessoa.conselhos'
        // ])
        //     ->where('tipo', 'prestador')
        //     ->paginate(10);

        // if (env("APP_ENV", 'production') == 'production') {
        //     return $result->withPath(str_replace('http:', 'https:', $result->path()));
        // } else {
        //     return $result;
        // }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function buscaprestadorexterno(Prestador $prestador)
    {
        return Prestador::with([
            'pessoa.telefones',
            'pessoa.enderecos.cidade',
            'pessoa.conselhos',
            'pessoa.emails',
            // 'pessoa.dadobancarios',
            'formacoes'
        ])->find($prestador->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prestador $prestador)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prestador $prestador)
    {
        //
    }
}
