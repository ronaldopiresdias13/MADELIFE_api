<?php

namespace App\Http\Controllers\Web\Custos;

use App\Http\Controllers\Controller;
use App\Models\Custo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustosController extends Controller
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
        return Custo::where('empresa_id', $empresa_id)
            ->orderBy('descricao')
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
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        DB::transaction(function () use ($request, $empresa_id) {
            $custo = Custo::create(
                [
                    'empresa_id' => $empresa_id,
                    'descricao'  => $request->descricao,
                ]
            );
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Custo  $custo
     * @return \Illuminate\Http\Response
     */
    public function show(Custo $custo)
    {
        return $custo;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Custo  $custo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Custo $custo)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        DB::transaction(function () use ($request, $custo, $empresa_id) {
            $custo->descricao  = $request->descricao;
            $custo->empresa_id = $empresa_id;
            $custo->save();
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Custo  $custo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Custo $custo)
    {
        $custo->delete();
    }
}
