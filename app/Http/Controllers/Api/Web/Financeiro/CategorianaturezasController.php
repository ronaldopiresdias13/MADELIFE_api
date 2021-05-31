<?php

namespace App\Http\Controllers\Api\Web\Financeiro;

use App\Models\Categorianatureza;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategorianaturezasController extends Controller
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
        return Categorianatureza::where('empresa_id', $empresa_id)
            ->where('ativo', true)
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
        DB::transaction(function () use ($request) {
            $user = $request->user();
            $empresa_id = $user->pessoa->profissional->empresa_id;
            Categorianatureza::create([
                'empresa_id' => $empresa_id,
                'descricao'  => $request['descricao'],
                'status'     => $request['status']
            ]);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Categorianatureza  $categorianatureza
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Categorianatureza $categorianatureza)
    {
        return $categorianatureza;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Categorianatureza  $categorianatureza
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categorianatureza $categorianatureza)
    {
        DB::transaction(function () use ($request, $categorianatureza) {
            $categorianatureza->update($request->all());
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Categorianatureza  $categorianatureza
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categorianatureza $categorianatureza)
    {
        $categorianatureza->ativo = false;
        $categorianatureza->save();
    }
}
