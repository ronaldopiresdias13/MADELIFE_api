<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserAcesso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAcessoController extends Controller
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
        // return $request;
        DB::transaction(function () use ($request) {
            foreach ($request['acesso_id'] as $key => $acesso) {
                $user_acesso = UserAcesso::firstOrCreate([
                    'user_id'   => $request['user_id'],
                    'acesso_id' => $acesso,
                ]);
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserAcesso  $userAcesso
     * @return \Illuminate\Http\Response
     */
    public function show(UserAcesso $userAcesso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserAcesso  $userAcesso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserAcesso $userAcesso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserAcesso  $userAcesso
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserAcesso $userAcesso)
    {
        $userAcesso->delete();
    }
}
