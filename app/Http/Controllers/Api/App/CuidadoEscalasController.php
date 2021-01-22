<?php

namespace App\Http\Controllers\Api\App;

use App\Models\CuidadoEscala;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CuidadoEscalasController extends Controller
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
     * @param  \App\CuidadoEscala  $cuidadoEscala
     * @return \Illuminate\Http\Response
     */
    public function show(CuidadoEscala $cuidadoEscala)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CuidadoEscala  $cuidadoEscala
     * @return \Illuminate\Http\Response
     */
    public function updateCuidado(Request $request, CuidadoEscala $cuidadoEscala)
    {
        DB::transaction(function () use ($request, $cuidadoEscala) {
            $cuidadoEscala->update($request->all());
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CuidadoEscala  $cuidadoEscala
     * @return \Illuminate\Http\Response
     */
    public function destroy(CuidadoEscala $cuidadoEscala)
    {
        //
    }
}
