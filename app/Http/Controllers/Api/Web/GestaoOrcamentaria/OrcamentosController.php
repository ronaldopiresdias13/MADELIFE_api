<?php

namespace App\Http\Controllers\Api\Web\GestaoOrcamentaria;

use App\Empresa;
use App\Http\Controllers\Controller;
use App\Mail\SendOrcamento;
use App\Orcamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrcamentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Empresa $empresa)
    {
        return Orcamento::with([
            'cliente.pessoa:id,nome',
            'homecare.paciente.pessoa:id,nome',
            'ordemservico:id,status',
            'servicos',
            'orcamentocustos',
            'produtos',
            'cidade'
            // 'produtos' => function ($query) {
            //     $query->select('id');
            // }
        ])
            ->where('empresa_id', $empresa->id)
            ->where('ativo', true)
            ->orderBy('id', 'desc')
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
        //
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
    public function enviarOrcamentoPorEmail(Request $request)
    {
        $file = $request->file('file');
        $request = json_decode($request->emails, true);
        // return $request;

        if ($file && $file->isValid()) {
            // $md5 = md5_file($file);
            // $caminho = 'orcamentos/teste';
            // $nome = $md5 . '.' . $file->extension();
            // $upload = $file->storeAs($caminho, $nome);
            // $nomeOriginal = $file->getClientOriginalName();
            Mail::send(new SendOrcamento($request, $file));
        }
    }
}
