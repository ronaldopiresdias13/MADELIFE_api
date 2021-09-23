<?php

namespace App\Http\Controllers\Api\Web\AreaClinica;

use App\Models\Transcricao;
use App\Models\Empresa;
use App\Models\TranscricaoProduto;
use App\Models\Horariomedicamento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TranscricoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $empresa_id = $user->pessoa->profissional->empresa->id;
        return Transcricao::with(['ordemservico.orcamento.homecare.paciente.pessoa', 'profissional.pessoa', 'itensTranscricao.produto', 'itensTranscricao.horariomedicamentos'])
            ->where('empresa_id', $empresa_id)
            ->where('ativo', true)
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
        DB::transaction(function () use ($request) {
            $user = $request->user();
            $empresa_id = $user->pessoa->profissional->empresa->id;
            $transcricao = Transcricao::create([
                'empresa_id'      => $empresa_id,
                'ordemservico_id' => $request->ordemservico_id,
                'profissional_id' => $user->pessoa->profissional->id,
                'medico'          => $request->medico,
                'receita'         => $request->receita,
                'crm'             => $request->crm,
            ]);

            foreach ($request->itensTranscricao as $key => $iten) {
                $transcricao_produto = TranscricaoProduto::firstOrCreate([
                    'transcricao_id' => $transcricao->id,
                    'produto_id'     => $iten['produto']['id'],
                    'quantidade'     => $iten['quantidade'],
                    'apresentacao'   => $iten['apresentacao'],
                    'via'            => $iten['via'],
                    'frequencia'     => $iten['frequencia'],
                    'tempo'          => $iten['tempo'],
                    'status'         => $iten['status'],
                    'observacao'     => $iten['observacao'],
                ]);
                foreach ($iten['horariomedicamentos'] as $key => $horario) {
                    $horario_medicamento = Horariomedicamento::create([
                        'transcricao_produto_id' => $transcricao_produto->id,
                        'horario'                => $horario['horario']
                    ]);
                }
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transcricao  $transcricao
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Transcricao $transcricao)
    {
        $transcricao->itensTranscricao;
        if ($transcricao->itensTranscricao) {
            foreach ($transcricao->itensTranscricao as $key => $itens) {
                $itens->produto;
                $itens->horariomedicamentos;
            }
        }
        return $transcricao;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transcricao  $transcricao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transcricao $transcricao)
    {
        // $transcricao->update($request->all());
        DB::transaction(function () use ($request, $transcricao) {
            // $transcricao->empresa_id      = $request->empresa_id;
            $transcricao->ordemservico_id = $request->ordemservico_id;
            // $transcricao->profissional_id = $request->profissional_id;
            $transcricao->medico          = $request->medico;
            $transcricao->receita         = $request->receita;
            $transcricao->crm             = $request->crm;
            $transcricao->save();

            foreach ($request->itensTranscricao as $key => $iten) {
                $transcricao_produto = TranscricaoProduto::updateOrCreate(
                    [
                        'id' => $iten['id'],
                    ],
                    [
                        'produto_id'     => $iten['produto']['id'],
                        'transcricao_id' => $transcricao['id'],
                        'quantidade'     => $iten['quantidade'],
                        'apresentacao'   => $iten['apresentacao'],
                        'via'            => $iten['via'],
                        'frequencia'     => $iten['frequencia'],
                        'tempo'          => $iten['tempo'],
                        'status'         => $iten['status'],
                        'observacao'     => $iten['observacao'],
                    ]
                );
                foreach ($transcricao_produto->horariomedicamentos as $key => $horario) {
                    $horario->delete();
                }
                // $transcricao_produto->horariomedicamentos->delete();
                foreach ($iten['horariomedicamentos'] as $key => $horario) {
                    $horario_medicamento = Horariomedicamento::create([
                        'transcricao_produto_id' => $transcricao_produto->id,
                        'horario'                => $horario['horario']
                    ]);
                }
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transcricao  $transcricao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transcricao $transcricao)
    {
        $transcricao->ativo = false;
        $transcricao->save();
    }


    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listaTranscricoes(Request $request)
    {
        $user = $request->user();
        $profissional = $user->pessoa->profissional;

        $transcricoes = Transcricao::with([
            'ordemservico' => function ($query) {
                $query->select('id', 'orcamento_id');
                $query->with(['orcamento' => function ($query) {
                    $query->select('id');
                    $query->with(['homecare' => function ($query) {
                        $query->select('id', 'orcamento_id', 'paciente_id');
                        $query->with(['paciente' => function ($query) {
                            $query->select('id', 'pessoa_id');
                            $query->with(['pessoa' => function ($query) {
                                $query->select('id', 'nome');
                            }]);
                        }]);
                    }]);
                }]);
            }, 'profissional' => function ($query) {
                $query->select('id', 'pessoa_id');
                $query->with(['pessoa' => function ($query) {
                    $query->select('id', 'nome');
                }]);
            }
        ])
            ->where('empresa_id', $profissional->empresa_id)
            ->where('ativo', true)
            ->get(['id', 'ordemservico_id', 'profissional_id']);

        return $transcricoes;
    }
    public function quantidadeTranscricoes(Empresa $empresa)
    {
        return Transcricao::where('empresa_id', $empresa['id'])->where('ativo', 1)->count();
    }
}
