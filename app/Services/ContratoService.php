<?php

namespace App\Services;

// use App\Models\Orc;

use App\Models\Aph;
use App\Models\Evento;
use App\Models\Historicoorcamento;
use App\Models\Homecare;
use App\Models\Orcamento;
use App\Models\Orcamentocusto;
use App\Models\OrcamentoProduto;
use App\Models\OrcamentoServico;
use App\Models\Ordemservico;
use App\Models\Remocao;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContratoService
{
    protected $request    = null;
    protected $orcamento  = null;
    protected $empresa_id = null;

    public function __construct(Request $request, Orcamento $orcamento = null)
    {
        $this->request   = $request;
        $this->orcamento = $orcamento;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $this->request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        DB::transaction(function () {
            $this->empresa_id = $this->request->user()->pessoa->profissional->empresa_id;
            if (!$this->empresa_id) {
                return 'Error';
            }

            $this->orcamento = new Orcamento();
            $this->orcamento->fill([
                "empresa_id"        => $this->empresa_id,
                "cliente_id"        => $this->request->cliente_id,
                "numero"            => $this->request->numero,
                "tipo"              => $this->request->tipo,
                "data"              => $this->request->data,
                "quantidade"        => $this->request->quantidade,
                "unidade"           => $this->request->unidade,
                "cidade_id"         => $this->request->cidade_id,
                "processo"          => $this->request->processo,
                "situacao"          => $this->request->situacao,
                "descricao"         => $this->request->descricao,
                "valortotalproduto" => $this->request->valortotalproduto,
                "valortotalcusto"   => $this->request->valortotalcusto,
                "valortotalservico" => $this->request->valortotalservico,
                "observacao"        => $this->request->observacao,
                "status"            => $this->request->status,
            ])->save();

            $ordemservico = new Ordemservico();
            $ordemservico->fill([
                "empresa_id"             => $this->empresa_id,
                "codigo"                 => $this->request->ordemservico['codigo'],
                "orcamento_id"           => $this->orcamento->id,
                "responsavel_id"         => $this->request->ordemservico['responsavel_id'],
                "inicio"                 => $this->request->ordemservico['inicio'],
                "fim"                    => $this->request->ordemservico['fim'],
                "status"                 => $this->request->ordemservico['status'],
                "montagemequipe"         => $this->request->ordemservico['montagemequipe'],
                "realizacaoprocedimento" => $this->request->ordemservico['realizacaoprocedimento'],
                "descricaomotivo"        => $this->request->ordemservico['descricaomotivo'],
                "dataencerramento"       => $this->request->ordemservico['dataencerramento'],
                "motivo"                 => $this->request->ordemservico['motivo'],
                "profissional_id"        => $this->request->ordemservico['profissional_id'],
            ])->save();

            switch ($this->orcamento->tipo) {
                case 'Venda':
                    $venda = new Venda();
                    $venda->fill([
                        "empresa_id"   => $this->empresa_id,
                        "orcamento_id" => $this->orcamento->id,
                        "realizada"    => $this->request->venda['realizada'],
                        "data"         => $this->request->venda['data'],
                    ])->save();
                    break;
                case 'Home Care':
                    $homecare = new Homecare();
                    $homecare->fill([
                        "orcamento_id" => $this->orcamento->id,
                        "paciente_id"  => $this->request->homecare['paciente_id'],
                    ])->save();
                    break;
                case 'APH':
                    $aph = new Aph();
                    $aph->fill([
                        "orcamento_id" => $this->orcamento->id,
                        "descricao"            => $this->request->aph['descricao'],
                        "endereco"             => $this->request->aph['endereco'],
                        "cep"                  => $this->request->aph['cep'],
                        "cidade_id"            => $this->request->aph['cidade_id'],
                    ])->save();
                    break;
                case 'Evento':
                    $evento = new Evento();
                    $evento->fill([
                        "orcamento_id" => $this->orcamento->id,
                        "nome"         => $this->request->evento['nome'],
                        "endereco"     => $this->request->evento['endereco'],
                        "cep"          => $this->request->evento['cep'],
                        "cidade_id"    => $this->request->evento['cidade_id'],
                    ])->save();
                    break;
                case 'Remocao':
                    $remocao = new Remocao();
                    $remocao->fill([
                        "orcamento_id"     => $this->orcamento->id,
                        "nome"             => $this->request->remocao['nome'],
                        "sexo"             => $this->request->remocao['sexo'],
                        "nascimento"       => $this->request->remocao['nascimento'],
                        "cpfcnpj"          => $this->request->remocao['cpfcnpj'],
                        "rgie"             => $this->request->remocao['rgie'],
                        "enderecoorigem"   => $this->request->remocao['enderecoorigem'],
                        "cidadeorigem_id"  => $this->request->remocao['cidadeorigem_id'],
                        "enderecodestino"  => $this->request->remocao['enderecodestino'],
                        "cidadedestino_id" => $this->request->remocao['cidadedestino_id'],
                        "observacao"       => $this->request->remocao['observacao'],
                    ])->save();
                    break;
            }

            foreach ($this->request->produtos as $item) {
                $orcamento_produto = new OrcamentoProduto();
                $orcamento_produto->fill([
                    "orcamento_id"         => $this->orcamento->id,
                    "produto_id"           => $item['produto_id'],
                    "quantidade"           => $item['quantidade'],
                    "valorunitario"        => $item['valorunitario'],
                    "subtotal"             => $item['subtotal'],
                    "custo"                => $item['custo'],
                    "subtotalcusto"        => $item['subtotalcusto'],
                    "valorresultadomensal" => $item['valorresultadomensal'],
                    "valorcustomensal"     => $item['valorcustomensal'],
                    "locacao"              => $item['locacao'],
                    "descricao"            => $item['descricao'],
                ])->save();
            }

            foreach ($this->request->servicos as $item) {
                $orcamento_servico = new OrcamentoServico();
                $orcamento_servico->fill([
                    "orcamento_id"         => $this->orcamento->id,
                    "servico_id"           => $item['servico_id'],
                    "quantidade"           => $item['quantidade'],
                    "basecobranca"         => $item['basecobranca'],
                    "frequencia"           => $item['frequencia'],
                    "valorunitario"        => $item['valorunitario'],
                    "subtotal"             => $item['subtotal'],
                    "custo"                => $item['custo'],
                    "custodiurno"          => $item['custodiurno'],
                    "custonoturno"         => $item['custonoturno'],
                    "subtotalcusto"        => $item['subtotalcusto'],
                    "valorresultadomensal" => $item['valorresultadomensal'],
                    "valorcustomensal"     => $item['valorcustomensal'],
                    "horascuidadodiurno"   => $item['horascuidadodiurno'],
                    "horascuidadonoturno"  => $item['horascuidadonoturno'],
                    "icms"                 => $item['icms'],
                    "iss"                  => $item['iss'],
                    "inss"                 => $item['inss'],
                    "descricao"            => $item['descricao'],
                ])->save();
            }



            // $ordemservico = Ordemservico::where('orcamento_id', $orcamento->id)->where('ativo', true)->first();

            // if ($ordemservico) {
            //     foreach ($ordemservico->servicos as $key => $servico) {
            //         OrdemservicoServico::find($servico->pivot->id)->delete();
            //         // $servico->delete();
            //     }

            //     foreach ($orcamento->servicos as $key => $servico) {
            //         OrdemservicoServico::create(
            //             [
            //                 'ordemservico_id'  => $ordemservico->id,
            //                 'servico_id'       => $servico->id,
            //                 'descricao'        => $servico['pivot']['basecobranca'],
            //                 'valordiurno'      => $servico['pivot']['custodiurno'],
            //                 'valornoturno'     => $servico['pivot']['custonoturno'],
            //             ]
            //         );
            //     }
            // }



            foreach ($this->request->custos as $item) {
                $orcamentocusto = new Orcamentocusto();
                $orcamentocusto->fill([
                    "orcamento_id"  => $this->orcamento->id,
                    "descricao"     => $item['descricao'],
                    "quantidade"    => $item['quantidade'],
                    "unidade"       => $item['unidade'],
                    "valorunitario" => $item['valorunitario'],
                    "valortotal"    => $item['valortotal'],
                ])->save();
            }

            Historicoorcamento::create([
                'orcamento_id' => $this->orcamento->id,
                'historico'    => json_encode($this->request->all()),
            ]);

            return ['status' => true, 'orcamento' => $this->orcamento->id];
        });
    }

    public function update()
    {
        DB::transaction(function () {
            $this->orcamento->fill([
                "cliente_id"        => $this->request->cliente_id,
                "numero"            => $this->request->numero,
                "tipo"              => $this->request->tipo,
                "data"              => $this->request->data,
                "quantidade"        => $this->request->quantidade,
                "unidade"           => $this->request->unidade,
                "cidade_id"         => $this->request->cidade_id,
                "processo"          => $this->request->processo,
                "situacao"          => $this->request->situacao,
                "descricao"         => $this->request->descricao,
                "valortotalproduto" => $this->request->valortotalproduto,
                "valortotalcusto"   => $this->request->valortotalcusto,
                "valortotalservico" => $this->request->valortotalservico,
                "observacao"        => $this->request->observacao,
                "status"            => $this->request->status,
            ])->save();

            $this->orcamento->ordemservico()->update([
                "codigo"                 => $this->request->ordemservico['codigo'],
                "orcamento_id"           => $this->orcamento->id,
                "responsavel_id"         => $this->request->ordemservico['responsavel_id'],
                "inicio"                 => $this->request->ordemservico['inicio'],
                "fim"                    => $this->request->ordemservico['fim'],
                "status"                 => $this->request->ordemservico['status'],
                "montagemequipe"         => $this->request->ordemservico['montagemequipe'],
                "realizacaoprocedimento" => $this->request->ordemservico['realizacaoprocedimento'],
                "descricaomotivo"        => $this->request->ordemservico['descricaomotivo'],
                "dataencerramento"       => $this->request->ordemservico['dataencerramento'],
                "motivo"                 => $this->request->ordemservico['motivo'],
                "profissional_id"        => $this->request->ordemservico['profissional_id'],
            ]);

            switch ($this->orcamento->tipo) {
                case 'Venda':
                    $this->orcamento->venda()->update([
                        "empresa_id"   => $this->empresa_id,
                        "orcamento_id" => $this->orcamento->id,
                        "realizada"    => $this->request->venda['realizada'],
                        "data"         => $this->request->venda['data'],
                    ]);
                    break;
                case 'Home Care':
                    $this->orcamento->homecare()->update([
                        "orcamento_id" => $this->orcamento->id,
                        "paciente_id"  => $this->request->homecare['paciente_id'],
                    ]);
                    break;
                case 'APH':
                    $this->orcamento->aph()->update([
                        "orcamento_id" => $this->orcamento->id,
                        "descricao"            => $this->request->aph['descricao'],
                        "endereco"             => $this->request->aph['endereco'],
                        "cep"                  => $this->request->aph['cep'],
                        "cidade_id"            => $this->request->aph['cidade_id'],
                    ]);
                    break;
                case 'Evento':
                    $this->orcamento->evento()->update([
                        "orcamento_id" => $this->orcamento->id,
                        "nome"         => $this->request->evento['nome'],
                        "endereco"     => $this->request->evento['endereco'],
                        "cep"          => $this->request->evento['cep'],
                        "cidade_id"    => $this->request->evento['cidade_id'],
                    ]);
                    break;
                case 'Remocao':
                    $this->orcamento->remocao()->update([
                        "orcamento_id"     => $this->orcamento->id,
                        "nome"             => $this->request->remocao['nome'],
                        "sexo"             => $this->request->remocao['sexo'],
                        "nascimento"       => $this->request->remocao['nascimento'],
                        "cpfcnpj"          => $this->request->remocao['cpfcnpj'],
                        "rgie"             => $this->request->remocao['rgie'],
                        "enderecoorigem"   => $this->request->remocao['enderecoorigem'],
                        "cidadeorigem_id"  => $this->request->remocao['cidadeorigem_id'],
                        "enderecodestino"  => $this->request->remocao['enderecodestino'],
                        "cidadedestino_id" => $this->request->remocao['cidadedestino_id'],
                        "observacao"       => $this->request->remocao['observacao'],
                    ]);
                    break;
            }

            $this->orcamento->produtos()->delete();

            foreach ($this->request->produtos as $item) {
                OrcamentoProduto::updateOrCreate(
                    [
                        "orcamento_id"         => $this->orcamento->id,
                        "produto_id"           => $item['produto_id'],
                        "quantidade"           => $item['quantidade'],
                        "valorunitario"        => $item['valorunitario'],
                        "subtotal"             => $item['subtotal'],
                        "custo"                => $item['custo'],
                        "subtotalcusto"        => $item['subtotalcusto'],
                        "valorresultadomensal" => $item['valorresultadomensal'],
                        "valorcustomensal"     => $item['valorcustomensal'],
                        "locacao"              => $item['locacao'],
                        "descricao"            => $item['descricao'],
                    ],
                    [
                        "ativo"                => true,
                    ]
                );
            }

            $this->orcamento->servicos()->delete();

            foreach ($this->request->servicos as $item) {
                OrcamentoServico::updateOrCreate(
                    [
                        "orcamento_id"         => $this->orcamento->id,
                        "servico_id"           => $item['servico_id'],
                        "quantidade"           => $item['quantidade'],
                        "basecobranca"         => $item['basecobranca'],
                        "frequencia"           => $item['frequencia'],
                        "valorunitario"        => $item['valorunitario'],
                        "subtotal"             => $item['subtotal'],
                        "custo"                => $item['custo'],
                        "custodiurno"          => $item['custodiurno'],
                        "custonoturno"         => $item['custonoturno'],
                        "subtotalcusto"        => $item['subtotalcusto'],
                        "valorresultadomensal" => $item['valorresultadomensal'],
                        "valorcustomensal"     => $item['valorcustomensal'],
                        "horascuidadodiurno"   => $item['horascuidadodiurno'],
                        "horascuidadonoturno"  => $item['horascuidadonoturno'],
                        "icms"                 => $item['icms'],
                        "iss"                  => $item['iss'],
                        "inss"                 => $item['inss'],
                        "descricao"            => $item['descricao'],
                    ],
                    [
                        "ativo"                => true,
                    ]
                );
            }

            $this->orcamento->custos()->delete();

            foreach ($this->request->custos as $item) {
                Orcamentocusto::updateOrCreate(
                    [
                        "orcamento_id"  => $this->orcamento->id,
                        "descricao"     => $item['descricao'],
                        "quantidade"    => $item['quantidade'],
                        "unidade"       => $item['unidade'],
                        "valorunitario" => $item['valorunitario'],
                        "valortotal"    => $item['valortotal'],
                    ],
                    [
                        "ativo"         => true,
                    ]
                );
            }

            Historicoorcamento::create([
                'orcamento_id' => $this->orcamento->id,
                'historico'    => json_encode($this->request->all()),
            ]);

            return ['status' => true, 'orcamento' => $this->orcamento->id];
        });
    }
}
