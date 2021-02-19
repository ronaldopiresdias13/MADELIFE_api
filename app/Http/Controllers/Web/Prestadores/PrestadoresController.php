<?php

namespace App\Http\Controllers\Web\Prestadores;

use App\Http\Controllers\Controller;
use App\Models\Pessoa;
use App\Models\Prestador;
use App\Models\Tipopessoa;
use Illuminate\Http\Request;

class PrestadoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listRecrutamento(Request $request)
    {
        // $pessoas = Pessoa::join('prestadores', 'pessoas.id', '=', 'prestadores.pessoa_id')
        // ->join('formacoes', 'formacoes.id', '=', 'prestadores.pessoa_id')
        // ->where([
        //     // Where pessoas
        //     ['pessoas.ativo', true]
        // ])
        // ->select(
        //     // Select pessoas
        //     'pessoas.id as pessoa_id',
        //     'pessoas.nome as pessoa_nome',
        //     'pessoas.nascimento as pessoa_nascimento',
        //     'pessoas.cpfcnpj as pessoa_cpfcnpj',
        //     'pessoas.rgie as pessoa_rgie',
        //     'pessoas.observacoes as pessoa_observacoes',
        //     'pessoas.perfil as pessoa_perfil',
        //     'pessoas.status as pessoa_status',
        //     'pessoas.ativo as pessoa_ativo',
        //     'pessoas.created_at as pessoa_created_at',
        //     'pessoas.updated_at as pessoa_updated_at',
        //     // Select prestadores
        //     'prestadores.id as prestadores_id',
        //     'prestadores.fantasia as prestadores_fantasia',
        //     'prestadores.sexo as prestadores_sexo',
        //     'prestadores.pis as prestadores_pis',
        //     'prestadores.cargo_id as prestadores_cargo_id',
        //     'prestadores.curriculo as prestadores_curriculo',
        //     'prestadores.certificado as prestadores_certificado',
        //     'prestadores.meiativa as prestadores_meiativa',
        //     'prestadores.dataverificacaomei as prestadores_dataverificacaomei',
        //     'prestadores.ativo as prestadores_ativo',
        //     'prestadores.created_at as prestadores_created_at',
        //     'prestadores.updated_at as prestadores_updated_at',
        // )
        // ->get();

        // return $pessoas;

        return Tipopessoa::with([
            'pessoa.prestador.formacoes',
            'pessoa.enderecos.cidade',
            'pessoa.conselhos'
        ])
        ->where('tipo', 'prestador')
        ->paginate(10);
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
     * @param  \App\Models\Prestador  $prestador
     * @return \Illuminate\Http\Response
     */
    public function show(Prestador $prestador)
    {
        //
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
