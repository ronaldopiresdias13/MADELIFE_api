<?php

namespace App\Http\Controllers;

use App\Models\Historico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoricosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function historicoescala(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        return DB::select(
            "SELECT DATE_FORMAT(h.created_at, '%d/%m/%Y') AS datahistorico, DATE_FORMAT(h.created_at, '%H:%i') AS hora, p.nome, e.id AS escala_id, ps.nome AS paciente, e.dataentrada, e.datasaida, e.periodo, e.horaentrada, e.horasaida, e.status, e.ativo, prof.nome AS prestador, s.descricao,
                CASE h.tipo when 1 then 'Criado' when 2 then 'Alterado' when 3 then 'Excluído' end as operacao 
                FROM historicos AS h
                INNER JOIN escalas AS e
                ON e.id = h.historico_id
                INNER JOIN ordemservicos AS os
                ON os.id = e.ordemservico_id
                INNER JOIN orcamentos AS o
                ON o.id = os.orcamento_id
                INNER JOIN homecares AS hc
                ON hc.orcamento_id = o.id
                INNER JOIN pacientes AS pc
                ON pc.id = hc.paciente_id
                INNER JOIN pessoas AS ps
                ON ps.id = pc.pessoa_id
                INNER JOIN servicos AS s
                ON s.id = e.servico_id
                INNER JOIN users AS u
                ON u.id = h.user_id
                INNER JOIN pessoas AS p
                ON p.id = u.pessoa_id
                INNER JOIN profissionais AS pf
                ON pf.pessoa_id = p.id
                INNER JOIN prestadores AS pr
                ON pr.id = e.prestador_id
                INNER JOIN pessoas AS prof
                ON prof.id = pr.pessoa_id
                WHERE e.empresa_id = ?
                ORDER BY h.created_at desc",
            [
                $empresa_id,
            ]
        );
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
     * @param  \App\Models\Historico  $historico
     * @return \Illuminate\Http\Response
     */
    public function show(Historico $historico)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Historico  $historico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Historico $historico)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Historico  $historico
     * @return \Illuminate\Http\Response
     */
    public function destroy(Historico $historico)
    {
        //
    }
}
