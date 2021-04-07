<?php

namespace App\Http\Controllers\Api\App\v3_0_21;

use App\Models\Email;
use App\Http\Controllers\Controller;
use App\Models\PessoaEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PessoaEmailController extends Controller
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
        DB::transaction(function () use ($request) {
            PessoaEmail::updateOrCreate(
                [
                    'pessoa_id' => $request->pessoa_id,
                    'email_id'  => Email::firstOrCreate(
                        ['email' => $request['email']['email']]
                    )->id,
                ],
                [
                    'tipo'      => $request['tipo'],
                    'descricao' => $request['descricao'],
                    'ativo'     => true
                ]
            );
        });

        return response()->json([
            'toast' => [
                'text' => 'Email adicionado com sucesso!',
                'color' => 'success',
                'duration' => 2000
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PessoaEmail  $pessoaEmail
     * @return \Illuminate\Http\Response
     */
    public function show(PessoaEmail $pessoaEmail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PessoaEmail  $pessoaEmail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PessoaEmail $pessoaEmail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PessoaEmail  $pessoaEmail
     * @return \Illuminate\Http\Response
     */
    public function destroy(PessoaEmail $pessoaEmail)
    {
        DB::transaction(function () use ($pessoaEmail) {
            $pessoaEmail->ativo = false;
            $pessoaEmail->save();
        });

        return response()->json([
            'toast' => [
                'text' => 'Excluido com sucesso!',
                'color' => 'success',
                'duration' => 2000
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }
}
