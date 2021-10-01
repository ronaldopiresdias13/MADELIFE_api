<?php

namespace App\Http\Controllers\Web\BaseProfissionais;

use App\Http\Controllers\Controller;
use App\Models\BaseProfissionais;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaseProfissionaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;

        return BaseProfissionais::where('empresa_id', $empresa_id)
        ->orderBy('name')
        ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;

        DB::transaction(function () use ($request, $empresa_id) {
            $baseprofissionais = new BaseProfissionais();
            $baseprofissionais->empresa_id          = $empresa_id;
            $baseprofissionais->name                = $request->name;
            $baseprofissionais->email               = $request->email;
            // $baseprofissionais->activation_code     = $request->activation_code;
            // $baseprofissionais->priv_admin          = $request->priv_admin;
            // $baseprofissionais->crypto              = $request->crypto;
            $baseprofissionais->cep                 = $request->cep;
            $baseprofissionais->endereco            = $request->endereco;
            $baseprofissionais->latitude            = $request->latitude;
            $baseprofissionais->longitude           = $request->longitude;
            $baseprofissionais->logradouro          = $request->logradouro;
            $baseprofissionais->numero              = $request->numero;
            $baseprofissionais->complemento         = $request->complemento;
            $baseprofissionais->bairro              = $request->bairro;
            $baseprofissionais->cidade              = $request->cidade;
            $baseprofissionais->uf                  = $request->uf;
            $baseprofissionais->cpf                 = $request->cpf;
            $baseprofissionais->rg                  = $request->rg;
            $baseprofissionais->pis                 = $request->pis;
            $baseprofissionais->coren               = $request->coren;
            $baseprofissionais->ccm                 = $request->ccm;
            $baseprofissionais->cnpj1               = $request->cnpj1;
            $baseprofissionais->tipo_inss           = $request->tipo_inss;
            $baseprofissionais->funcao              = $request->funcao;
            $baseprofissionais->tel1                = $request->tel1;
            $baseprofissionais->tel2                = $request->tel2;
            $baseprofissionais->tel3                = $request->tel3;
            $baseprofissionais->tel4                = $request->tel4;
            $baseprofissionais->tel5                = $request->tel5;
            $baseprofissionais->tel6                = $request->tel6;
            $baseprofissionais->complexidade        = $request->complexidade;
            $baseprofissionais->comp_grau           = $request->comp_grau;
            $baseprofissionais->disponib            = $request->disponib;
            $baseprofissionais->bloqueio            = $request->bloqueio;
            $baseprofissionais->bloqueio_obs        = $request->bloqueio_obs;
            $baseprofissionais->bloqueio_tomador    = $request->bloqueio_tomador;
            $baseprofissionais->regiao              = $request->regiao;
            $baseprofissionais->obs                 = $request->obs;
            $baseprofissionais->banco               = $request->banco;
            $baseprofissionais->agencia             = $request->agencia;
            $baseprofissionais->conta               = $request->conta;
            $baseprofissionais->conta_digito        = $request->conta_digito;
            $baseprofissionais->tipo_conta          = $request->tipo_conta;
            $baseprofissionais->conta_terceiro      = $request->conta_terceiro;
            $baseprofissionais->nome_terceiro       = $request->nome_terceiro;
            $baseprofissionais->cpf_terceiro        = $request->cpf_terceiro;
            $baseprofissionais->obs1                = $request->obs1;
            $baseprofissionais->endereco_terceiro   = $request->endereco_terceiro;
            $baseprofissionais->numero_contrato     = $request->numero_contrato;
            $baseprofissionais->dt_inclusao         = $request->dt_inclusao;
            $baseprofissionais->dt_nascimento       = $request->dt_nascimento;
            $baseprofissionais->quem_inclui         = $request->quem_inclui;
            $baseprofissionais->foto                = $request->foto;
            $baseprofissionais->documentos          = $request->documentos;
            $baseprofissionais->nome_documentos     = $request->nome_documentos;
            $baseprofissionais->tam_documentos      = $request->tam_documentos;
            $baseprofissionais->logo                = $request->logo;
            $baseprofissionais->save();
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BaseProfissionais $baseprofissionais)
    {
        return $baseprofissionais;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BaseProfissionais $baseprofissionais)
    {
        DB::transaction(function () use ($request, $baseprofissionais) {
            $baseprofissionais->update($request->all());
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BaseProfissionais $baseprofissionais)
    {
        $baseprofissionais->delete();
    }
}
