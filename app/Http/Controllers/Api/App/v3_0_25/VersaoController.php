<?php

namespace App\Http\Controllers\Api\App\v3_0_25;

use App\Http\Controllers\Controller;
use App\Models\Versao;
use Illuminate\Http\Request;

class VersaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function verificarVersaoApp(Request $request)
    {
        $versao = Versao::where('aplicacao', 'ml')->where('plataforma', $request->plataforma)->pluck('versao');

        if($request->versao < $versao[0]){

            return response()->json([
                'alert' => [
                    'title' => 'Ops!',
                    'text' => 'Por favor atualize seu aplicativo!'
                ]
            ], 200)
                ->header('Content-Type', 'application/json');

        }

        return null;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
