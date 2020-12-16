<?php

namespace App\Http\Controllers\Api\Web\Geral;

use App\Empresamodulo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmpresamodulosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listAcessosEmpresa()
    {
        $acessos = Empresamodulo::with('menus', 'submenus')
            ->where('', '')
            ->orderBy('')
            ->orderBy('')
            ->orderBy('')
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Empresamodulo  $empresamodulo
     * @return \Illuminate\Http\Response
     */
    public function show(Empresamodulo $empresamodulo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empresamodulo  $empresamodulo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Empresamodulo $empresamodulo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Empresamodulo  $empresamodulo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empresamodulo $empresamodulo)
    {
        //
    }
}
