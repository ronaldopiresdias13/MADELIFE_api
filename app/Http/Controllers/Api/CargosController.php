<?php

namespace App\Http\Controllers\API;

use App\Cargo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CargosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cargo::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->descricao);
        $cargo = Cargo::firstOrCreate(
            ['cbo' => $request->cbo],
            ['descricao' =>  $request->descricao]
        );
        // $cargo->cbo = $request->cbo;
        // $cargo->descricao = $request->descricao;
        // $cargo->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function show(Cargo $cargo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cargo $cargo)
    {
        dd($request->cbo);
        // foreach ($request->acessos as $key => $value) {
        //     $teste = Cargo::updateOrCreate(
        //         ['cbo'  => $user->id, 'acesso' => Acesso::FirstOrCreate(['nome' => $value['nome']])->id]
        //     );
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cargo $cargo)
    {
        //
    }
}
