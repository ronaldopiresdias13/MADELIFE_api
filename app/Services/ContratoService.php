<?php

namespace App\Services;

// use App\Models\Orc;

use App\Models\Aph;
use App\Models\Evento;
use App\Models\Homecare;
use App\Models\Orcamento;
use App\Models\Orcamentocusto;
use App\Models\OrcamentoProduto;
use App\Models\OrcamentoServico;
use App\Models\Orccusto;
use App\Models\OrcProduto;
use App\Models\OrcServico;
use App\Models\Ordemservico;
use App\Models\Remocao;
use App\Models\User;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContratoService
{
    protected $request    = null;
    protected $orcamento  = null;
    protected $empresa_id = null;
    // protected $resposta = ['status' => false, 'orc' => null];

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
                case 'venda':
                    $venda = new Venda();
                    $venda->fill([
                        "empresa_id"   => $this->empresa_id,
                        "orcamento_id" => $this->orcamento->id,
                        "realizada"    => $this->request->venda['realizada'],
                        "data"         => $this->request->venda['data'],
                    ])->save();
                    break;
                case 'homecare':
                    $homecare = new Homecare();
                    $homecare->fill([
                        "orcamento_id" => $this->orcamento->id,
                        "paciente_id"  => $this->request->homecare['paciente_id'],
                    ])->save();
                    break;
                case 'aph':
                    $aph = new Aph();
                    $aph->fill([
                        "orcamento_id" => $this->orcamento->id,
                        "descricao"            => $this->request->aph['descricao'],
                        "endereco"             => $this->request->aph['endereco'],
                        "cep"                  => $this->request->aph['cep'],
                        "cidade_id"            => $this->request->aph['cidade_id'],
                    ])->save();
                    break;
                case 'evento':
                    $evento = new Evento();
                    $evento->fill([
                        "orcamento_id" => $this->orcamento->id,
                        "nome"         => $this->request->evento['nome'],
                        "endereco"     => $this->request->evento['endereco'],
                        "cep"          => $this->request->evento['cep'],
                        "cidade_id"    => $this->request->evento['cidade_id'],
                    ])->save();
                    break;
                case 'remocao':
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
                    "icms"                 => $item['icms'],
                    "iss"                  => $item['iss'],
                    "inss"                 => $item['inss'],
                    "descricao"            => $item['descricao'],
                ])->save();
            }

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
                // "venda_realizada"          => $this->request->venda_realizada,
                // "venda_data"               => $this->request->venda_data,
                // "homecare_paciente_id"     => $this->request->homecare_paciente_id,
                // "aph_descricao"            => $this->request->aph_descricao,
                // "aph_endereco"             => $this->request->aph_endereco,
                // "aph_cep"                  => $this->request->aph_cep,
                // "aph_cidade_id"            => $this->request->aph_cidade_id,
                // "evento_nome"              => $this->request->evento_nome,
                // "evento_endereco"          => $this->request->evento_endereco,
                // "evento_cep"               => $this->request->evento_cep,
                // "evento_cidade_id"         => $this->request->evento_cidade_id,
                // "remocao_nome"             => $this->request->remocao_nome,
                // "remocao_sexo"             => $this->request->remocao_sexo,
                // "remocao_nascimento"       => $this->request->remocao_nascimento,
                // "remocao_cpfcnpj"          => $this->request->remocao_cpfcnpj,
                // "remocao_rgie"             => $this->request->remocao_rgie,
                // "remocao_enderecoorigem"   => $this->request->remocao_enderecoorigem,
                // "remocao_cidadeorigem_id"  => $this->request->remocao_cidadeorigem_id,
                // "remocao_enderecodestino"  => $this->request->remocao_enderecodestino,
                // "remocao_cidadedestino_id" => $this->request->remocao_cidadedestino_id,
                // "remocao_observacao"       => $this->request->remocao_observacao,
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
                case 'venda':
                    $this->orcamento->venda()->update([
                        "empresa_id"   => $this->empresa_id,
                        "orcamento_id" => $this->orcamento->id,
                        "realizada"    => $this->request->venda['realizada'],
                        "data"         => $this->request->venda['data'],
                    ]);
                    break;
                case 'homecare':
                    $this->orcamento->homecare()->update([
                        "orcamento_id" => $this->orcamento->id,
                        "paciente_id"  => $this->request->homecare['paciente_id'],
                    ]);
                    break;
                case 'aph':
                    $this->orcamento->aph()->update([
                        "orcamento_id" => $this->orcamento->id,
                        "descricao"            => $this->request->aph['descricao'],
                        "endereco"             => $this->request->aph['endereco'],
                        "cep"                  => $this->request->aph['cep'],
                        "cidade_id"            => $this->request->aph['cidade_id'],
                    ]);
                    break;
                case 'evento':
                    $this->orcamento->evento()->update([
                        "orcamento_id" => $this->orcamento->id,
                        "nome"         => $this->request->evento['nome'],
                        "endereco"     => $this->request->evento['endereco'],
                        "cep"          => $this->request->evento['cep'],
                        "cidade_id"    => $this->request->evento['cidade_id'],
                    ]);
                    break;
                case 'remocao':
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
        });
    }
}
