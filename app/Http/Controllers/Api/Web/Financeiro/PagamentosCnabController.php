<?php

namespace App\Http\Controllers\Api\Web\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Pagamentoexterno;
use App\Models\Pagamentointerno;
use Illuminate\Http\Request;

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
        if ($request->tipo == "Prestador") {
            $pagamentos = Pagamentoexterno::with(['pessoa.dadosbancario.banco'])
                ->where('empresa_id', $empresa_id)
                ->where('status', false)
                // ->where('situacao', "!=", "Criado")
                ->whereBetween('datainicio', [$request->data_ini, $request->data_fim])
                ->get();
        }
        if ($request->tipo == "Profissional") {
            $pagamentos = Pagamentointerno::with(['pessoa.dadosbancario.banco'])
                ->where('empresa_id', $empresa_id)
                ->where('status', false)
                // ->where('situacao', "!=", "Criado")
                ->whereBetween('datainicio', [$request->data_ini, $request->data_fim])
                ->get();
        }
        // else {
        //     return $result;
        // }

        // ->groupBy("pessoa_id");
        // ->keyBy("pessoa_id")

        foreach ($pagamentos as $key => $pagamento) {
            $tem = false;
            foreach ($result as $key => $r) {
                if ($r['profissional'] == $pagamento->pessoa_id) {
                    array_push($result[$key]['pagamentos'], $pagamento);
                    $tem = true;
                    break;
                }
            }
            if (!$tem) {
                $array = [
                    'profissional' => $pagamento->pessoa_id,
                    'nome'         => $pagamento->pessoa->nome,
                    'pagamentos'   => [$pagamento],
                    'situacao'     => $pagamento->situacao,
                    // 'valor'        => $request->tipo == "Prestador" ? array_reduce($pagamento->subtotal, "sum") : (($pagamento->salario + $pagamento->proventos) - $pagamento->descontos)
                ];
                array_push($result, $array);
            }
        }
        return $result;
    }
    public function sum($carry, $item)
    {
        $carry += $item;
        return $carry;
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
