<?php

namespace App\Http\Controllers\Api\Novo\Web;

use App\Http\Controllers\Controller;
use App\TranscricaoProduto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TranscricaoProdutoController extends Controller
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
     * @param  \App\TranscricaoProduto  $transcricaoProduto
     * @return \Illuminate\Http\Response
     */
    public function show(TranscricaoProduto $transcricaoProduto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TranscricaoProduto  $transcricaoProduto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TranscricaoProduto $transcricaoProduto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TranscricaoProduto  $transcricaoProduto
     * @return \Illuminate\Http\Response
     */
    public function destroy(TranscricaoProduto $transcricaoProduto)
    {
        //
    }

    /*-------------------- ROTAS CUSTON --------------------*/

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listaDeMedicamentosNaoRealizadosComFiltro(Request $request)
    {
        $hoje = getdate();
        $data = $hoje['year'] . '-' . ($hoje['mon'] < 10 ? '0' . $hoje['mon'] : $hoje['mon']) . '-' . $hoje['mday'];

        return DB::select(
            'SELECT p.descricao, pes.nome, e.dataentrada, hm.horario FROM transcricao_produto tp
            INNER JOIN produtos p ON p.id = tp.produto_id
            INNER JOIN transcricoes t ON t.id = tp.transcricao_id
            INNER JOIN ordemservicos os ON os.id = t.ordemservico_id
            INNER JOIN orcamentos o ON o.id = os.orcamento_id
            INNER JOIN homecares h ON h.orcamento_id = o.id
            INNER JOIN pacientes pac ON pac.id = h.paciente_id
            INNER JOIN pessoas pes ON pes.id = pac.pessoa_id
            INNER JOIN escalas e ON e.ordemservico_id = os.id
            INNER JOIN horariomedicamentos hm ON hm.transcricao_produto_id = tp.id
            WHERE NOT EXISTS (SELECT * FROM acaomedicamentos am WHERE tp.id = am.transcricao_produto_id)
            AND os.id LIKE ?
            AND e.dataentrada BETWEEN ? AND ?',
            [
                $request['os'] ? $request['os'] : '%',
                $request['dataIni'] ? $request['dataIni'] : $data,
                $request['dataFim'] ? $request['dataFim'] : $data
            ]
        );
    }
}
