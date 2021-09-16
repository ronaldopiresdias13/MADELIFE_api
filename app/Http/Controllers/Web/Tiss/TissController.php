<?php

namespace App\Http\Controllers\Web\Tiss;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Medicao;
use App\Models\Tiss;
use App\Services\TissService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TissController extends Controller
{
    public function gerarXml(Request $request, Cliente $cliente)
    {
        $medicoes = Medicao::with(
            'medicao_produtos.produto',
            'medicao_servicos.servico',
            'ordemservico.orcamento.homecare.paciente.pessoa'
        )
            ->whereIn('id', $request->medicoes)
            ->get();

        if ($cliente->versaoTiss) {
            $tissService = new TissService($medicoes, $cliente);
            $resposta = $tissService->criarXml();

            if ($resposta) {
                return response()->json('Ok!\nSalvo com Sucesso!', 200)->header('Content-Type', 'text/plain');
            } else {
                throw ValidationException::withMessages([
                    'tiss' => ['Erro ao gerar o TISS. Verifique se todos os dados estão corretos'],
                ]);
            }
        } else {
            return response()->json('Erro!\nVersão do TISS não informado no cadastro do Cliente!', 400)->header('Content-Type', 'text/plain');
        }
    }

    public function editarXml(Request $request, Cliente $cliente)
    {
        $medicoes = Medicao::with(
            'medicao_produtos.produto',
            'medicao_servicos.servico',
            'ordemservico.orcamento.homecare.paciente.pessoa'
        )
            ->whereIn('id', $request->medicoes)
            ->get();

        if ($cliente->versaoTiss) {
            $tiss = $request['tiss_id'];
            $tissService = new TissService($medicoes, $cliente, $tiss);
            $resposta = $tissService->editarXml();

            if ($resposta) {
                $medicoes = Medicao::where('tiss_id', $tiss)
                    ->whereNotIn('id', $request->medicoes)
                    ->get();
                foreach ($medicoes as $key => $medicao) {
                    $medicao->tiss_id = null;
                    $medicao->save();
                }
                return response()->json('Ok!\nSalvo com Sucesso!', 200)->header('Content-Type', 'text/plain');
            } else {
                throw ValidationException::withMessages([
                    'tiss' => ['Erro ao gerar o TISS. Verifique se todos os dados estão corretos'],
                ]);
            }
        } else {
            return response()->json('Erro!\nVersão do TISS não informado no cadastro do Cliente!', 400)->header('Content-Type', 'text/plain');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = Tiss::with('cliente.pessoa', 'medicoes')->orderBy('created_at', 'desc');

        if ($request->paginate) {
            $result = $result->paginate($request['per_page'] ? $request['per_page'] : 15);
        } else {
            $result = $result->get();
        }

        if (env("APP_ENV", 'production') == 'production') {
            return $result->withPath(str_replace('http:', 'https:', $result->path()));
        } else {
            return $result;
        }
    }

    public function downloadTiss(Tiss $tiss)
    {
        $headers = [
            'Content-type'        => 'text/txt',
        ];
        return response()->download($tiss->caminhoxml, $tiss->nomexml, $headers);
    }
}
