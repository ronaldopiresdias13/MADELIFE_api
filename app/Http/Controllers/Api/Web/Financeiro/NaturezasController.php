<?php

namespace App\Http\Controllers\Api\Web\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Natureza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NaturezasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        return Natureza::where('empresa_id', $empresa_id)
            ->where('ativo', true)
            ->orderBy('codigo')
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
        DB::transaction(function () use ($request) {
            $user = $request->user();
            $empresa_id = $user->pessoa->profissional->empresa_id;
            Natureza::create([
                'empresa_id'           => $empresa_id,
                'codigo'               => $request['codigo'],
                'descricao'            => $request['descricao'],
                'tipo'                 => $request['tipo'],
                'status'               => $request['status'],
                'categorianatureza_id' => $request['categorianatureza_id'],
            ]);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Natureza  $natureza
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Natureza $natureza)
    {
        return $natureza;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Natureza  $natureza
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Natureza $natureza)
    {
        $natureza->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Natureza  $natureza
     * @return \Illuminate\Http\Response
     */
    public function destroy(Natureza $natureza)
    {
        $natureza->ativo = false;
        $natureza->save();
    }
}
