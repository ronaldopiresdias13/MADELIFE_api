<?php

namespace App\Http\Controllers\Api\Web\Financeiro;

use App\Empresa;
use App\Http\Controllers\Controller;
use App\Pagamentopessoa;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagamentopessoasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listPagamentosByEmpresaId(Request $request)
    {
        $result = [];

        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa_id;
        $pagamentos = Pagamentopessoa::with(['pessoa.dadosbancario'])
            ->where('empresa_id', $empresa_id)
            ->where('status', false)
            ->where('ativo', true)
            ->where(DB::raw("date_format(str_to_date(pagamentopessoas.periodo1, '%Y-%m-%d'), '%Y-%m')"), "=", $request['mes'])
            ->get();
        // ->groupBy("pessoa_id");
        // ->keyBy("pessoa_id")


        foreach ($pagamentos as $key => $pagamento) {
            $tem = false;
            foreach ($result as $key => $r) {
                // echo ($r['profissional'] == $pagamento->pessoa->nome);
                if ($r['profissional'] == $pagamento->pessoa->nome) {
                    array_push($r['pagamentos'], $pagamento);
                    $tem = true;
                    break;
                }
            }
            // echo ($tem);
            if (!$tem) {
                $array = ['profissional' => $pagamento->pessoa->nome, 'pagamentos' => [$pagamento]];
                array_push($result, $array);
            }
            // if (!key_exists($pagamento->pessoa->nome, $result)) {
            //     $result[$pagamento->pessoa->nome] = [];
            // }
            // array_push($result[($pagamento->pessoa->nome)], $pagamento); array("name" => "my name");
            // array_push($result[$pagamento->pessoa->nome], $pagamento);
        }
        return $result;
        return $pagamentos;
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
        $pagamentos = Pagamentopessoa::with('pessoa')
            ->where('empresa_id', $empresa_id)
            ->where('status', false)
            ->where('ativo', true)
            ->get()
            ->groupBy(function ($val) {
                return Carbon::parse($val->periodo1)->format('Y-m');
            });
        return $pagamentos;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function atualizarSituacaoPagamentoDiretoria(Request $request)
    {
        // return $request;
        DB::transaction(function () use ($request) {
            foreach ($request['pagamentos'] as $key => $pag) {
                $pagamento = Pagamentopessoa::find($pag['id']);
                $pagamento->situacao = $request['situacao'];
                $pagamento->update();
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pagamentopessoa  $pagamentopessoa
     * @return \Illuminate\Http\Response
     */
    public function show(Pagamentopessoa $pagamentopessoa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pagamentopessoa  $pagamentopessoa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pagamentopessoa $pagamentopessoa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pagamentopessoa  $pagamentopessoa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pagamentopessoa $pagamentopessoa)
    {
        //
    }
}
