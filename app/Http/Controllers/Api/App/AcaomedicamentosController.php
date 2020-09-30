<?php

namespace App\Http\Controllers\Api\App;

use App\Acaomedicamento;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcaomedicamentosController extends Controller
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
            $user = $request->user();
            $prestador = $user->pessoa->prestador->id;
            Acaomedicamento::create([
                'transcricao_produto_id' => $request['transcricao_produto_id'],
                'prestador_id' => $prestador,
                'data' => $request['data'],
                'hora' => $request['hora'],
                'observacao' => $request['observacao'],
                'status' => $request['status'],
                'escala_id' => $request['escala_id'],
            ]);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Acaomedicamento  $acaomedicamento
     * @return \Illuminate\Http\Response
     */
    public function show(Acaomedicamento $acaomedicamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Acaomedicamento  $acaomedicamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Acaomedicamento $acaomedicamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Acaomedicamento  $acaomedicamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Acaomedicamento $acaomedicamento)
    {
        //
    }
}
