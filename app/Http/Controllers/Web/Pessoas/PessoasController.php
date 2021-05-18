<?php

namespace App\Http\Controllers\Web\Pessoas;

use App\Http\Controllers\Controller;
use App\Models\Pessoa;
use App\Models\PessoaTelefone;
use App\Models\Telefone;
use Illuminate\Http\Request;

class PessoasController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pessoa  $pessoa
     * @return \Illuminate\Http\Response
     */
    public function show(Pessoa $pessoa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pessoa  $pessoa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pessoa $pessoa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pessoa  $pessoa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pessoa $pessoa)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pessoa  $pessoa
     * @return \Illuminate\Http\Response
     */
    public function adicionarTelefone(Request $request, Pessoa $pessoa)
    {
        PessoaTelefone::firstOrCreate([
            'pessoa_id'   => $pessoa->id,
            'telefone_id' => Telefone::firstOrCreate(
                [
                    'telefone'  => $request['telefone'],
                ]
            )->id,
            'tipo'      => $request['pivot']['tipo'],
            'descricao' => $request['pivot']['descricao'],
        ]);
    }
}
