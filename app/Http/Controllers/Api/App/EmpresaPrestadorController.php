<?php

namespace App\Http\Controllers\Api\App;

use App\EmpresaPrestador;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpresaPrestadorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $prestador = $user->pessoa->prestador;
        return EmpresaPrestador::with('empresa')
            ->where('prestador_id', $prestador->id)->get();
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
     * @param  \App\EmpresaPrestador  $empresaPrestador
     * @return \Illuminate\Http\Response
     */
    public function show(EmpresaPrestador $empresaPrestador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmpresaPrestador  $empresaPrestador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmpresaPrestador $empresaPrestador)
    {
        return $request['status'];
        DB::transaction(function () use ($request, $empresaPrestador) {
            $empresaPrestador->status = $request['status'];
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmpresaPrestador  $empresaPrestador
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmpresaPrestador $empresaPrestador)
    {
        //
    }
}
