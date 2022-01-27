<?php

namespace App\Http\Controllers\Api\Web\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Pagamentoexterno;
use App\Models\Pagamentointerno;
use App\Models\Pagamentopessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PagamentosCnabController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listPagamentosByEmpresaId(Request $request)
    {
        // return $request->tipo;
        $result = [];
        $pagamentos = [];
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        // if ($request->tipo == "Prestador") {
        //     $pagamentos = Pagamentoexterno::with(['pessoa.dadosbancario.banco'])
        //         ->where('empresa_id', $empresa_id)
        //         ->where('status', false)
        //         ->where('situacao', "!=", "Criado")
        //         ->whereBetween('datainicio', [$request->data_ini, $request->data_fim])
        //         ->get();
        // }
        // if ($request->tipo == "Profissional") {
        //     $pagamentos = Pagamentointerno::with(['pessoa.dadosbancario.banco'])
        //         ->where('empresa_id', $empresa_id)
        //         ->where('status', false)
        //         ->where('situacao', "!=", "Criado")
        //         ->whereBetween('datainicio', [$request->data_ini, $request->data_fim])
        //         ->get();
        // }
        if ($request->tipo == "Prestador") {
            $pagamentos = Pagamentopessoa::with(['pessoa.dadosbancario.banco'])
                ->where('empresa_id', $empresa_id)
                ->where('status', false)
                ->where('tipopessoa', 'Prestador Externo')
                ->where('situacao', "!=", "Criado")
                ->whereBetween('periodo1', [$request->data_ini, $request->data_fim])
                ->get();
        }
        if ($request->tipo == "Profissional") {
            $pagamentos = Pagamentopessoa::with(['pessoa.dadosbancario.banco'])
                ->where('empresa_id', $empresa_id)
                ->where('status', false)
                ->where('situacao', "!=", "Criado")
                ->where('tipopessoa', 'Profissional Interno')
                ->whereBetween('periodo1', [$request->data_ini, $request->data_fim])
                ->get();
        }
        foreach ($pagamentos as $key => $pagamento) {
            $tem = false;
            foreach ($result as $key => $r) {
                if ($r['profissional'] == $pagamento->pessoa_id && $r['situacao'] == $pagamento->situacao) {
                    array_push($result[$key]['pagamentos'], $pagamento);
                    $tem = true;
                    break;
                }
            }
            if (!$tem) {
                $array = [
                    'profissional' => $pagamento->pessoa_id,
                    'nome'         => $pagamento->pessoa->nome,
                    'cpfcnpj'      => $pagamento->pessoa->cpfcnpj,
                    'pagamentos'   => [$pagamento],
                    'situacao'     => $pagamento->situacao,
                ];
                array_push($result, $array);
            }
        }
        return $result;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function groupByPagamentoByMesAndEmpresaId(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $pagamentosexternos = DB::select(
            "
                SELECT DATE_FORMAT(pgp.periodo1, '%Y-%m') AS periodo, pgp.id, p.nome, pgp.valor, pgp.situacao, pgp.tipopessoa as tipo
                FROM pagamentopessoas AS pgp
                INNER JOIN pessoas AS p
                ON p.id = pgp.pessoa_id
                WHERE pgp.empresa_id = ?
                AND pgp.`status` = 0
                AND pgp.deleted_at IS NULL
                AND pgp.situacao != 'Criado'
            ",
            [
                $empresa_id,
            ]
        );
        return $this->groupByPagamentos($pagamentosexternos);
    }
    public function groupByPagamentos($pagamentos)
    {
        $result = [];
        foreach ($pagamentos as $key => $pagamento) {
            $tem = false;
            $array['total'] = 0;
            foreach ($result as $key => $r) {
                if ($r['periodo'] == $pagamento->periodo && $r['tipo'] == $pagamento->tipo && $r['situacao'] == $pagamento->situacao) {
                    array_push($result[$key]['pagamentos'], $pagamento);
                    $result[$key]['total'] += $pagamento->valor;
                    $tem = true;
                    break;
                }
            }
            if (!$tem) {
                $array['total'] += $pagamento->valor;
                $array = [
                    'periodo' => $pagamento->periodo,
                    'tipo'         => $pagamento->tipo,
                    'situacao'         => $pagamento->situacao,
                    'pagamentos'   => [$pagamento],
                    'total'     => $array['total'],
                ];
                array_push($result, $array);
            }
        }
        return $result;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pagamentoexterno  $pagamentoexterno
     * @return \Illuminate\Http\Response
     */
    public function atualizarSituacaoPagamentoDiretoria(Request $request)
    {
        // return $request;
        DB::transaction(function () use ($request) {
            foreach ($request['pagamentos'] as $key => $pag) {
                // if ($pag['tipo'] == "Prestador Externo") {
                //     $pagamento = Pagamentoexterno::find($pag['id']);
                //     $pagamento->situacao = $request['situacao'];
                //     $pagamento->update();
                // }
                //  else {
                //     $pagamento = Pagamentointerno::find($pag['id']);
                //     $pagamento->situacao = $request['situacao'];
                //     $pagamento->update();
                // }
                $pagamento = Pagamentopessoa::find($pag['id']);
                $pagamento->situacao = $request['situacao'];
                $pagamento->update();
            }
        });
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
