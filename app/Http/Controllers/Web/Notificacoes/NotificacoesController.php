<?php

namespace App\Http\Controllers\Web\Notificacoes;

use App\Http\Controllers\Controller;
use App\Models\Notificacao;
use App\Models\Profissional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificacoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        return Notificacao::with(['profissional.pessoa'])->get();
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
            $profissionais = Profissional::where('ativo', true)->get();
            foreach ($profissionais as $key => $prof) {
                Notificacao::create([
                    'tipo' => $request['tipo'],
                    'titulo' => $request['titulo'],
                    'conteudo' => $request['conteudo'],
                    'link' => $request['link'],
                    'visto' => false,
                    'resolvido' => false,
                    'profissional_id' => $prof->id
                ]);
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notificacao  $notificacao
     * @return \Illuminate\Http\Response
     */
    public function show(Notificacao $notificacao)
    {
        return $notificacao;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notificacao  $notificacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notificacao $notificacao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notificacao  $notificacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notificacao $notificacao)
    {
        //
    }
}
