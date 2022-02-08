<?php

namespace App\Http\Controllers\Api\App\v3_1_3;

use App\Models\Endereco;
use App\Http\Controllers\Controller;
use App\Models\PessoaEndereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PessoaEnderecoController extends Controller
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
    public function store(Request $request)
    {
        // return $request;
        DB::transaction(function () use ($request) {
            PessoaEndereco::updateOrCreate(
                [
                    'pessoa_id' => $request['pessoa_id'],
                    'endereco_id'  => Endereco::firstOrCreate(
                        [
                            'cep'         => $request['cep'],
                            'cidade_id'   => $request['cidade_id'],
                            'rua'         => $request['rua'],
                            'bairro'      => $request['bairro'],
                            'numero'      => $request['numero'],
                            'complemento' => $request['complemento'],
                            'tipo'        => $request['tipo'],
                            'descricao'   => $request['descricao'],
                        ]
                    )->id,
                ],
                [
                    'ativo' => true,
                ]
            );
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PessoaEndereco  $pessoaEndereco
     * @return \Illuminate\Http\Response
     */
    public function show(PessoaEndereco $pessoaEndereco)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PessoaEndereco  $pessoaEndereco
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PessoaEndereco $pessoaEndereco)
    {
        DB::transaction(function () use ($request, $pessoaEndereco) {
            $pessoaEndereco->endereco_id  = Endereco::firstOrCreate(
                [
                    'cep'         => $request['cep'],
                    'cidade_id'   => $request['cidade_id'],
                    'rua'         => $request['rua'],
                    'bairro'      => $request['bairro'],
                    'numero'      => $request['numero'],
                    'complemento' => $request['complemento'],
                    'tipo'        => $request['tipo'],
                    'descricao'   => $request['descricao'],
                ]
            )->id;
            $pessoaEndereco->ativo = true;
            $pessoaEndereco->save();
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PessoaEndereco  $pessoaEndereco
     * @return \Illuminate\Http\Response
     */
    public function destroy(PessoaEndereco $pessoaEndereco)
    {
        $pessoaEndereco->ativo = false;
        $pessoaEndereco->save();
    }
}
