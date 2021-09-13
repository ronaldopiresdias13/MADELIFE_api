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
        // return $request;
        // $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        $medicoes = Medicao::with(
            'medicao_produtos.produto',
            'medicao_servicos.servico',
            'ordemservico.orcamento.homecare.paciente.pessoa'
        )
            ->whereIn('id', $request->medicoes)
            ->get();

        // return $medicao;
        // $empresa = Empresa::find($empresa_id);

        if ($cliente->versaoTiss) {
            $func = 'gerar_xml_' . $cliente->versaoTiss;
            $tissService = new TissService($medicoes, $cliente);
            $resposta = $tissService->$func($medicoes, $cliente);

            if ($resposta) {
                // return $resposta;
                return response()->json(['tiss' => $resposta], 200)->header('Content-Type', 'application/xml');
            } else {
                throw ValidationException::withMessages([
                    'tiss' => ['Erro ao gerar o TISS. Verifique se todos os dados estão corretos'],
                ]);
            }
        } else {
            return response()->json('Erro!\nVersão do TISS não informado no cadastro do Cliente!', 400)->header('Content-Type', 'text/plain');
        }




        // $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        // $medicao = Medicao::with(
        //     'medicao_produtos.produto',
        //     'medicao_servicos.servico',
        //     'ordemservico.orcamento.homecare.paciente.pessoa'
        // )->find($request->medicoes);
        // $empresa = Empresa::find($empresa_id);

        // if ($medicao->cliente->versaoTiss) {
        //     $func = 'gerar_xml_' . $medicao->cliente->versaoTiss;
        //     // $tissService = new TissService($medicao, $empresa);
        //     // $resposta = $tissService->$func();
        //     $tissService = new TissService($medicao, $empresa);
        //     $resposta = $tissService->$func();

        //     if ($resposta) {
        //         // return $resposta;
        //         return response()->json(['tiss' => $resposta], 200)->header('Content-Type', 'application/xml');
        //     } else {
        //         throw ValidationException::withMessages([
        //             'tiss' => ['Erro ao gerar o TISS. Verifique se todos os dados estão corretos'],
        //         ]);
        //     }
        // } else {
        //     return response()->json('Erro!\nVersão do TISS não informado no cadastro do Cliente!', 400)->header('Content-Type', 'text/plain');
        // }
    }

    public function gerarXmlPorCliente(Request $request, Cliente $cliente)
    {
        // return $request;
        // $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        $medicao = Medicao::with(
            'medicao_produtos.produto',
            'medicao_servicos.servico',
            'ordemservico.orcamento.homecare.paciente.pessoa'
        )
            ->whereIn('id', $request->medicoes)
            ->get();

        // return $medicao;
        // $empresa = Empresa::find($empresa_id);

        if ($cliente->versaoTiss) {
            $func = 'gerar_xml_' . $cliente->versaoTiss;
            // $tissService = new TissService($medicao, $empresa);
            $resposta = $this->$func($medicao, $cliente);

            if ($resposta) {
                // return $resposta;
                return response()->json(['tiss' => $resposta], 200)->header('Content-Type', 'application/xml');
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
        $result = Tiss::orderBy('created_at', 'desc');

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tiss = new Tiss();
        $tiss->fill([
            'sequencia' => $request->sequencia,
            'caminhoxml' => $request->caminhoxml
        ]);
        $tiss->save();
    }
}
