<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\Tiss;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use XMLWriter;

class TissService
{
    protected $xml = null;

    protected $dados = [];

    protected $sequencialItem = 1;

    protected $texto = '';

    protected $medicoes = [];
    protected $cliente = null;
    protected $tiss = null;

    protected $data = null;
    protected $hora = null;

    public function __construct($medicoes, $cliente, $tiss = null)
    {
        $this->medicoes = $medicoes;
        $this->cliente  = $cliente;
        $this->tiss     = $tiss;
    }

    public function criarXml()
    {
        DB::transaction(function () {
            $this->iniciarArquivo();

            $func = 'montar_xml_' . $this->cliente->versaoTiss;
            $this->$func();

            $this->finalizarArquivo();
        });

        return true;
    }

    public function editarXml()
    {
        DB::transaction(function () {
            $this->iniciarArquivo2();

            $func = 'montar_xml_' . $this->cliente->versaoTiss;
            $this->$func();

            $this->finalizarArquivo();
        });

        return true;
    }

    public function iniciarArquivo()
    {
        $now = Carbon::now();
        $this->data = $now->format('Y-m-d');
        $this->hora = $now->format('H-i-s');

        $nomexml    = 'tiss_' . $this->data . '_' . $this->hora . '.xml';
        $caminhoxml = storage_path('app/public') . '/tiss';
        is_dir($caminhoxml) ? true : mkdir($caminhoxml);

        $this->tiss = new Tiss();
        $this->tiss->fill([
            'cliente_id' => $this->cliente->id,
            'sequencia'  => $this->cliente->empresa->tiss_sequencialTransacao + 1,
            'data'       => $this->data,
            'hora'       => $this->hora,
            'nomexml'    => $nomexml,
            'caminhoxml' => $caminhoxml . '/' . $nomexml
        ])->save();

        $empresa = Empresa::find($this->cliente->empresa->id);
        $empresa->tiss_sequencialTransacao = $this->cliente->empresa->tiss_sequencialTransacao + 1;
        $empresa->save();

        $this->xml = new XMLWriter();
        $this->xml->openMemory();
        $this->xml->openUri($caminhoxml . '/' . $nomexml);
        $this->xml->startDocument('1.0', 'utf-8');
    }

    public function iniciarArquivo2()
    {
        $this->tiss = Tiss::find($this->tiss);
        $this->data = $this->tiss->data;
        $this->hora = $this->tiss->hora;

        $nomexml    = 'tiss_' . $this->data . '_' . $this->hora . '.xml';
        $caminhoxml = storage_path('app/public') . '/tiss';
        is_dir($caminhoxml) ? true : mkdir($caminhoxml);

        // $this->tiss->fill([
        //     'cliente_id' => $this->cliente->id,
        //     'sequencia'  => $this->cliente->empresa->tiss_sequencialTransacao + 1,
        //     'data'       => $this->data,
        //     'hora'       => $this->hora,
        //     'nomexml'    => $nomexml,
        //     'caminhoxml' => $caminhoxml . '/' . $nomexml
        // ])->save();

        // $empresa = Empresa::find($this->cliente->empresa->id);
        // $empresa->tiss_sequencialTransacao = $this->cliente->empresa->tiss_sequencialTransacao + 1;
        // $empresa->save();

        $this->xml = new XMLWriter();
        $this->xml->openMemory();
        $this->xml->openUri($caminhoxml . '/' . $nomexml);
        $this->xml->startDocument('1.0', 'utf-8');
    }

    public function finalizarArquivo()
    {
        $this->xml->endDocument();
    }

    public function montar_xml_3_02_00()
    {
        $dados = [];
        $dados['valorProcedimentos']   = 0;
        $dados['valorDiarias']         = 0;
        $dados['valorTaxasAlugueis']   = 0;
        $dados['valorMateriais']       = 0;
        $dados['valorMedicamentos']    = 0;
        $dados['valorOPME']            = 0;
        $dados['valorGasesMedicinais'] = 0;

        $this->xml->startElement('ans:mensagemTISS');
        $this->xml->writeAttribute('xmlns:ans', 'http://www.ans.gov.br/padroes/tiss/schemas');
        $this->xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $this->xml->writeAttribute('xsi:schemaLocation', 'http://www.ans.gov.br/padroes/tiss/schemas tissV3_02_00.xsd');
        $this->xml->startElement('ans:cabecalho');
        $this->xml->startElement('ans:identificacaoTransacao');
        $this->xml->startElement('ans:tipoTransacao');
        $this->xml->text('ENVIO_LOTE_GUIAS');
        $this->xml->endElement(); #ans:tipoTransacao
        $this->xml->startElement('ans:sequencialTransacao');
        $this->xml->text($this->tiss->sequencia);
        $this->texto .= $this->tiss->sequencia;
        $this->xml->endElement(); #ans:sequencialTransacao
        $this->xml->startElement('ans:dataRegistroTransacao');
        $this->xml->text($this->data);
        $this->texto .= $this->data;
        $this->xml->endElement(); #ans:dataRegistroTransacao
        $this->xml->startElement('ans:horaRegistroTransacao');
        $this->xml->text($this->hora);
        $this->texto .= $this->hora;
        $this->xml->endElement(); #ans:horaRegistroTransacao
        $this->xml->endElement(); #ans:identificacaoTransacao
        $this->xml->startElement('ans:origem');
        $this->xml->startElement('ans:identificacaoPrestador');
        $this->xml->startElement('ans:CNPJ');
        $this->xml->text($this->cliente->empresa->cnpj);
        $this->texto .= $this->cliente->empresa->cnpj;
        $this->xml->endElement(); #ans:CNPJ');
        $this->xml->endElement(); #ans:identificacaoPrestador
        $this->xml->endElement(); #ans:origem
        $this->xml->startElement('ans:destino');
        $this->xml->startElement('ans:registroANS');
        $this->xml->text($this->cliente->registroAns);
        $this->texto .= $this->cliente->registroAns;
        $this->xml->endElement(); #ans:registroANS
        $this->xml->endElement(); #ans:destino
        $this->xml->startElement('ans:versaoPadrao');
        $this->xml->text(str_replace('_', '.', $this->cliente->versaoTiss));
        $this->texto .= str_replace('_', '.', $this->cliente->versaoTiss);
        $this->xml->endElement(); #ans:versaoPadrao
        $this->xml->endElement(); #ans:cabecalho
        $this->xml->startElement('ans:prestadorParaOperadora');
        $this->xml->startElement('ans:loteGuias');
        $this->xml->startElement('ans:numeroLote');
        $this->xml->text('0');
        $this->texto .= '0';
        $this->xml->endElement(); #ans:numeroLote');
        $this->xml->startElement('ans:guiasTISS');
        foreach ($this->medicoes as $key => $medicao) {
            $this->xml->startElement('ans:guiaSP-SADT'); // Foreach
            $this->xml->startElement('ans:cabecalhoGuia');
            $this->xml->startElement('ans:registroANS');
            $this->xml->text($medicao->cliente->registroAns);
            $this->texto .= $medicao->cliente->registroAns;
            $this->xml->endElement(); #ans:registroANS
            $this->xml->startElement('ans:numeroGuiaPrestador');
            $this->xml->text($medicao->numeroGuiaPrestador);
            $this->texto .= $medicao->numeroGuiaPrestador;
            $this->xml->endElement(); #ans:numeroGuiaPrestador
            $this->xml->endElement(); #ans:cabecalhoGuia
            $this->xml->startElement('ans:dadosBeneficiario');
            $this->xml->startElement('ans:numeroCarteira');
            $this->xml->text($medicao->ordemservico->orcamento->homecare->paciente->numeroCarteira);
            $this->texto .= $medicao->ordemservico->orcamento->homecare->paciente->numeroCarteira;
            $this->xml->endElement(); #ans:numeroCarteira
            $this->xml->startElement('ans:atendimentoRN');
            $this->xml->text($medicao->ordemservico->orcamento->homecare->paciente->atendimentoRN ? 'S' : 'N');
            $this->texto .= $medicao->ordemservico->orcamento->homecare->paciente->atendimentoRN ? 'S' : 'N';
            $this->xml->endElement(); #ans:atendimentoRN
            $this->xml->startElement('ans:nomeBeneficiario');
            $this->xml->text($medicao->ordemservico->orcamento->homecare->paciente->pessoa->nome);
            $this->texto .= $medicao->ordemservico->orcamento->homecare->paciente->pessoa->nome;
            $this->xml->endElement(); #ans:nomeBeneficiario
            $this->xml->endElement(); #ans:dadosBeneficiario
            $this->xml->startElement('ans:dadosSolicitante');
            $this->xml->startElement('ans:contratadoSolicitante');
            $this->xml->startElement('ans:cnpjContratado');
            $this->xml->text($this->cliente->empresa->cnpj);
            $this->texto .= $this->cliente->empresa->cnpj;
            $this->xml->endElement(); #ans:cnpjContratado
            $this->xml->startElement('ans:nomeContratado');
            $this->xml->text($this->cliente->empresa->razao);
            $this->texto .= $this->cliente->empresa->razao;
            $this->xml->endElement(); #ans:nomeContratado
            $this->xml->endElement(); #ans:contratadoSolicitante
            $this->xml->startElement('ans:profissionalSolicitante');
            $this->xml->startElement('ans:nomeProfissional');
            $this->xml->text($medicao->profissional->pessoa->nome);
            $this->texto .= $medicao->profissional->pessoa->nome;
            $this->xml->endElement(); #ans:nomeProfissional
            $this->xml->startElement('ans:conselhoProfissional');
            $this->xml->text($medicao->profissional->conselhoProfissional);
            $this->texto .= $medicao->profissional->conselhoProfissional;
            $this->xml->endElement(); #ans:conselhoProfissional
            $this->xml->startElement('ans:numeroConselhoProfissional');
            $this->xml->text($medicao->profissional->numeroConselhoProfissional);
            $this->texto .= $medicao->profissional->numeroConselhoProfissional;
            $this->xml->endElement(); #ans:numeroConselhoProfissional
            $this->xml->startElement('ans:UF');
            $this->xml->text($medicao->profissional->uf);
            $this->texto .= $medicao->profissional->uf;
            $this->xml->endElement(); #ans:UF
            $this->xml->startElement('ans:CBOS');
            $this->xml->text($medicao->profissional->cbos);
            $this->texto .= $medicao->profissional->cbos;
            $this->xml->endElement(); #ans:CBOS
            $this->xml->endElement(); #ans:profissionalSolicitante
            $this->xml->endElement(); #ans:dadosSolicitante
            $this->xml->startElement('ans:dadosSolicitacao');
            $this->xml->startElement('ans:dataSolicitacao');
            $this->xml->text($medicao->dataSolicitacao);
            $this->texto .= $medicao->dataSolicitacao;
            $this->xml->endElement(); #ans:dataSolicitacao
            $this->xml->startElement('ans:caraterAtendimento');
            $this->xml->text($medicao->ordemservico->orcamento->caraterAtendimento);
            $this->texto .= $medicao->ordemservico->orcamento->caraterAtendimento;
            $this->xml->endElement(); #ans:caraterAtendimento
            $this->xml->startElement('ans:indicacaoClinica');
            $this->xml->text($medicao->ordemservico->orcamento->indicacaoClinica);
            $this->texto .= $medicao->ordemservico->orcamento->indicacaoClinica;
            $this->xml->endElement(); #ans:indicacaoClinica
            $this->xml->endElement(); #ans:dadosSolicitacao
            $this->xml->startElement('ans:dadosExecutante');
            $this->xml->startElement('ans:contratadoExecutante');
            $this->xml->startElement('ans:cnpjContratado');
            $this->xml->text($this->cliente->empresa->cnpj);
            $this->texto .= $this->cliente->empresa->cnpj;
            $this->xml->endElement(); #ans:cnpjContratado
            $this->xml->startElement('ans:nomeContratado');
            $this->xml->text($this->cliente->empresa->razao);
            $this->texto .= $this->cliente->empresa->razao;
            $this->xml->endElement(); #ans:nomeContratado
            $this->xml->endElement(); #ans:contratadoExecutante
            $this->xml->startElement('ans:CNES');
            $this->xml->text($this->cliente->empresa->CNES);
            $this->texto .= $this->cliente->empresa->CNES;
            $this->xml->endElement(); #ans:CNES
            $this->xml->endElement(); #ans:dadosExecutante
            $this->xml->startElement('ans:dadosAtendimento');
            $this->xml->startElement('ans:tipoAtendimento');
            $this->xml->text($medicao->ordemservico->orcamento->tipoatentendimento);
            $this->texto .= $medicao->ordemservico->orcamento->tipoatentendimento;
            $this->xml->endElement(); #ans:tipoAtendimento
            $this->xml->startElement('ans:indicacaoAcidente');
            $this->xml->text($medicao->ordemservico->orcamento->indicacaoacidente);
            $this->texto .= $medicao->ordemservico->orcamento->indicacaoacidente;
            $this->xml->endElement(); #ans:indicacaoAcidente
            $this->xml->endElement(); #ans:dadosAtendimento

            $this->xml->startElement('ans:procedimentosExecutados');
            foreach ($medicao->medicao_servicos as $key => $servico) {
                $this->xml->startElement('ans:procedimentoExecutado');
                $this->xml->startElement('ans:dataExecucao');
                $this->xml->text($servico->dataExecucao);
                $this->texto .= $servico->dataExecucao;
                $this->xml->endElement(); #ans:dataExecucao
                $this->xml->startElement('ans:horaInicial');
                $this->xml->text($servico->horaInicial . ":00");
                $this->texto .= $servico->horaInicial . ":00";
                $this->xml->endElement(); #ans:horaInicial
                $this->xml->startElement('ans:horaFinal');
                $this->xml->text($servico->horaFinal . ":00");
                $this->texto .= $servico->horaFinal . ":00";
                $this->xml->endElement(); #ans:horaFinal
                $this->xml->startElement('ans:procedimento');
                $this->xml->startElement('ans:codigoTabela');
                $this->xml->text($servico->servico->codigoTabela);
                $this->texto .= $servico->servico->codigoTabela;
                $this->xml->endElement(); #ans:codigoTabela
                $this->xml->startElement('ans:codigoProcedimento');
                $this->xml->text($servico->servico->codtuss);
                $this->texto .= $servico->servico->codtuss;
                $this->xml->endElement(); #ans:codigoProcedimento
                $this->xml->startElement('ans:descricaoProcedimento');
                $this->xml->text($servico->servico->descricao);
                $this->texto .= $servico->servico->descricao;
                $this->xml->endElement(); #ans:descricaoProcedimento
                $this->xml->endElement(); #ans:procedimento
                $this->xml->startElement('ans:quantidadeExecutada');
                $this->xml->text($servico->atendido == 0 ? 1 : $servico->atendido);
                $this->texto .= $servico->atendido == 0 ? 1 : $servico->atendido;
                $this->xml->endElement(); #ans:quantidadeExecutada
                $this->xml->startElement('ans:reducaoAcrescimo');
                $this->xml->text($servico->reducaoAcrescimo != '0' ? $servico->reducaoAcrescimo : '1');
                $this->texto .= $servico->reducaoAcrescimo != '0' ? $servico->reducaoAcrescimo : '1';
                $this->xml->endElement(); #ans:reducaoAcrescimo
                $this->xml->startElement('ans:valorUnitario');
                $this->xml->text($servico->valor);
                $this->texto .= $servico->valor;
                $this->xml->endElement(); #ans:valorUnitario
                $this->xml->startElement('ans:valorTotal');
                $this->xml->text($servico->subtotal);
                $this->texto .= $servico->subtotal;
                $this->xml->endElement(); #ans:valorTotal
                $this->xml->endElement(); #ans:procedimentoExecutado
                $dados['valorProcedimentos'] += $servico->subtotal;
            }
            $this->xml->endElement(); #ans:procedimentosExecutados
            if (count($medicao->medicao_produtos) > 0) {
                $this->xml->startElement('ans:outrasDespesas');
                foreach ($medicao->medicao_produtos as $key => $produto) {
                    $this->xml->startElement('ans:despesa');
                    $this->xml->startElement('ans:codigoDespesa');
                    $this->xml->text($produto->produto->codigoDespesa);
                    $this->texto .= $produto->produto->codigoDespesa;
                    $this->xml->endElement(); #ans:codigoDespesa
                    $this->xml->startElement('ans:servicosExecutados');
                    $this->xml->startElement('ans:dataExecucao');
                    $this->xml->text($produto->dataExecucao);
                    $this->texto .= $produto->dataExecucao;
                    $this->xml->endElement(); #ans:dataExecucao
                    $this->xml->startElement('ans:horaInicial');
                    $this->xml->text($produto->horaInicial . ":00");
                    $this->texto .= $produto->horaInicial . ":00";
                    $this->xml->endElement(); #ans:horaInicial
                    $this->xml->startElement('ans:horaFinal');
                    $this->xml->text($produto->horaFinal . ":00");
                    $this->texto .= $produto->horaFinal . ":00";
                    $this->xml->endElement(); #ans:horaFinal
                    $this->xml->startElement('ans:codigoTabela');
                    $this->xml->text($produto->produto->codigoTabela);
                    $this->texto .= $produto->produto->codigoTabela;
                    $this->xml->endElement(); #ans:codigoTabela
                    $this->xml->startElement('ans:codigoProcedimento');
                    $this->xml->text($produto->produto->codtuss);
                    $this->texto .= $produto->produto->codtuss;
                    $this->xml->endElement(); #ans:codigoProcedimento
                    $this->xml->startElement('ans:quantidadeExecutada');
                    $this->xml->text($produto->atendido);
                    $this->texto .= $produto->atendido;
                    $this->xml->endElement(); #ans:quantidadeExecutada
                    $this->xml->startElement('ans:unidadeMedida');
                    $this->xml->text($produto->produto->unidademedida->codigo);
                    $this->texto .= $produto->produto->unidademedida->codigo;
                    $this->xml->endElement(); #ans:unidadeMedida
                    $this->xml->startElement('ans:reducaoAcrescimo');
                    $this->xml->text($produto->reducaoAcrescimo);
                    $this->texto .= $produto->reducaoAcrescimo;
                    $this->xml->endElement(); #ans:reducaoAcrescimo
                    $this->xml->startElement('ans:valorUnitario');
                    $this->xml->text($produto->valor);
                    $this->texto .= $produto->valor;
                    $this->xml->endElement(); #ans:valorUnitario
                    $this->xml->startElement('ans:valorTotal');
                    $this->xml->text($produto->subtotal);
                    $this->texto .= $produto->subtotal;
                    $this->xml->endElement(); #ans:valorTotal
                    $this->xml->startElement('ans:descricaoProcedimento');
                    $this->xml->text($produto->produto->descricao);
                    $this->texto .= $produto->produto->descricao;
                    $this->xml->endElement(); #ans:descricaoProcedimento
                    $this->xml->endElement(); #ans:servicosExecutados
                    $this->xml->endElement(); #ans:despesa
                    switch ($produto->produto->codigoDespesa) {
                        case '01':
                            $dados['valorGasesMedicinais'] += $produto->subtotal;
                            break;
                        case '02':
                            $dados['valorMedicamentos'] += $produto->subtotal;
                            break;
                        case '03':
                            $dados['valorMateriais'] += $produto->subtotal;
                            break;
                        case '05':
                            $dados['valorDiarias'] += $produto->subtotal;
                            break;
                        case '07':
                            $dados['valorTaxasAlugueis'] += $produto->subtotal;
                            break;
                        case '08':
                            $dados['valorOPME'] += $produto->subtotal;
                            break;
                        default:
                            break;
                    }
                }
                $this->xml->endElement(); #ans:outrasDespesas
            }
            $dados['valorTotalGeral'] =
                $dados['valorProcedimentos']   +
                $dados['valorDiarias']         +
                $dados['valorTaxasAlugueis']   +
                $dados['valorMateriais']       +
                $dados['valorMedicamentos']    +
                $dados['valorOPME']            +
                $dados['valorGasesMedicinais'];
            $this->xml->startElement('ans:valorTotal');
            $this->xml->startElement('ans:valorProcedimentos');
            $this->xml->text($dados['valorProcedimentos']);
            $this->texto .= $dados['valorProcedimentos'];
            $this->xml->endElement(); #ans:valorProcedimentos
            $this->xml->startElement('ans:valorDiarias');
            $this->xml->text($dados['valorDiarias']);
            $this->texto .= $dados['valorDiarias'];
            $this->xml->endElement(); #ans:valorDiarias
            $this->xml->startElement('ans:valorTaxasAlugueis');
            $this->xml->text($dados['valorTaxasAlugueis']);
            $this->texto .= $dados['valorTaxasAlugueis'];
            $this->xml->endElement(); #ans:valorTaxasAlugueis
            $this->xml->startElement('ans:valorMateriais');
            $this->xml->text($dados['valorMateriais']);
            $this->texto .= $dados['valorMateriais'];
            $this->xml->endElement(); #ans:valorMateriais
            $this->xml->startElement('ans:valorMedicamentos');
            $this->xml->text($dados['valorMedicamentos']);
            $this->texto .= $dados['valorMedicamentos'];
            $this->xml->endElement(); #ans:valorMedicamentos
            $this->xml->startElement('ans:valorOPME');
            $this->xml->text($dados['valorOPME']);
            $this->texto .= $dados['valorOPME'];
            $this->xml->endElement(); #ans:valorOPME
            $this->xml->startElement('ans:valorGasesMedicinais');
            $this->xml->text($dados['valorGasesMedicinais']);
            $this->texto .= $dados['valorGasesMedicinais'];
            $this->xml->endElement(); #ans:valorGasesMedicinais
            $this->xml->startElement('ans:valorTotalGeral');
            $this->xml->text($dados['valorTotalGeral']);
            $this->texto .= $dados['valorTotalGeral'];
            $this->xml->endElement(); #ans:valorTotalGeral
            $this->xml->endElement(); #ans:valorTotal>

            $this->xml->endElement(); #ans:guiaSP-SADT>

            $medicao->tiss_id = $this->tiss->id;
            $medicao->save();
        }
        $this->xml->endElement(); #ans:guiasTISS>
        $this->xml->endElement(); #ans:loteGuias>
        $this->xml->endElement(); #ans:prestadorParaOperadora>
        $this->xml->startElement('ans:epilogo');
        $this->xml->startElement('ans:hash');
        $this->xml->text(hash('ripemd160', $this->texto));
        $this->xml->endElement(); #ans:hash');
        $this->xml->endElement(); #ans:epilogo>
        $this->xml->endElement(); #ans:mensagemTISS>
    }

    public function montar_xml_3_05_00()
    {
        $dados = [];
        $dados['valorProcedimentos']   = 0;
        $dados['valorDiarias']         = 0;
        $dados['valorTaxasAlugueis']   = 0;
        $dados['valorMateriais']       = 0;
        $dados['valorMedicamentos']    = 0;
        $dados['valorOPME']            = 0;
        $dados['valorGasesMedicinais'] = 0;

        $this->xml->startElement('ans:mensagemTISS');
        $this->xml->writeAttribute('xmlns:ans', 'http://www.ans.gov.br/padroes/tiss/schemas');
        $this->xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $this->xml->writeAttribute('xsi:schemaLocation', 'http://www.ans.gov.br/padroes/tiss/schemas tissV3_05_00.xsd');

        $this->xml->startElement('ans:cabecalho');
        $this->xml->startElement('ans:identificacaoTransacao');
        $this->xml->startElement('ans:tipoTransacao');
        $this->xml->text('ENVIO_LOTE_GUIAS');
        $this->xml->endElement(); #ans:tipoTransacao
        $this->xml->startElement('ans:sequencialTransacao');
        $this->xml->text($this->tiss->sequencia);
        $this->texto .= $this->tiss->sequencia;
        $this->xml->endElement(); #ans:sequencialTransacao
        $this->xml->startElement('ans:dataRegistroTransacao');
        $this->xml->text($this->data);
        $this->texto .= $this->data;
        $this->xml->endElement(); #ans:dataRegistroTransacao
        $this->xml->startElement('ans:horaRegistroTransacao');
        $this->xml->text($this->hora);
        $this->texto .= $this->hora;
        $this->xml->endElement(); #ans:horaRegistroTransacao
        $this->xml->endElement(); #ans:identificacaoTransacao
        $this->xml->startElement('ans:origem');
        $this->xml->startElement('ans:identificacaoPrestador');
        $this->xml->startElement('ans:CNPJ');
        $this->xml->text($this->cliente->empresa->cnpj);
        $this->texto .= $this->cliente->empresa->cnpj;
        $this->xml->endElement(); #ans:CNPJ
        $this->xml->endElement(); #ans:identificacaoPrestador
        $this->xml->endElement(); #ans:origem
        $this->xml->startElement('ans:destino');
        $this->xml->startElement('ans:registroANS');
        $this->xml->text($this->cliente->registroAns);
        $this->texto .= $this->cliente->registroAns;
        $this->xml->endElement(); #ans:registroANS
        $this->xml->endElement(); #ans:destino
        $this->xml->startElement('ans:Padrao');
        $this->xml->text(str_replace('_', '.', $this->cliente->versaoTiss));
        $this->texto .= str_replace('_', '.', $this->cliente->versaoTiss);
        $this->xml->endElement(); #ans:Padrao
        $this->xml->endElement(); #ans:cabecalho

        $this->xml->startElement('ans:prestadorParaOperadora');
        $this->xml->startElement('ans:loteGuias');
        $this->xml->startElement('ans:numeroLote');
        $this->xml->text('0');
        $this->texto .= '0';
        $this->xml->endElement(); #ans:numeroLote
        $this->xml->startElement('ans:guiasTISS');
        foreach ($this->medicoes as $key => $medicao) {
            $this->xml->startElement('ans:guiaSP-SADT');
            $this->xml->startElement('ans:cabecalhoGuia');
            $this->xml->startElement('ans:registroANS');
            $this->xml->text($medicao->cliente->registroAns);
            $this->texto .= $medicao->cliente->registroAns;
            $this->xml->endElement(); #ans:registroANS
            $this->xml->startElement('ans:numeroGuiaPrestador');
            $this->xml->text($medicao->numeroGuiaPrestador);
            $this->texto .= $medicao->numeroGuiaPrestador;
            $this->xml->endElement(); #ans:numeroGuiaPrestador
            $this->xml->endElement(); #ans:cabecalhoGuia
            $this->xml->startElement('ans:dadosBeneficiario');
            $this->xml->startElement('ans:numeroCarteira');
            $this->xml->text($medicao->ordemservico->orcamento->homecare->paciente->numeroCarteira);
            $this->texto .= $medicao->ordemservico->orcamento->homecare->paciente->numeroCarteira;
            $this->xml->endElement(); #ans:numeroCarteira
            $this->xml->startElement('ans:atendimentoRN');
            $this->xml->text($medicao->ordemservico->orcamento->homecare->paciente->atendimentoRN ? 'S' : 'N');
            $this->texto .= $medicao->ordemservico->orcamento->homecare->paciente->atendimentoRN ? 'S' : 'N';
            $this->xml->endElement(); #ans:atendimentoRN
            $this->xml->startElement('ans:nomeBeneficiario');
            $this->xml->text($medicao->ordemservico->orcamento->homecare->paciente->pessoa->nome);
            $this->texto .= $medicao->ordemservico->orcamento->homecare->paciente->pessoa->nome;
            $this->xml->endElement(); #ans:nomeBeneficiario
            $this->xml->endElement(); #ans:dadosBeneficiario
            $this->xml->startElement('ans:dadosSolicitante');
            $this->xml->startElement('ans:contratadoSolicitante');
            $this->xml->startElement('ans:cnpjContratado');
            $this->xml->text($this->cliente->empresa->cnpj);
            $this->texto .= $this->cliente->empresa->cnpj;
            $this->xml->endElement(); #ans:cnpjContratado
            $this->xml->startElement('ans:nomeContratado');
            $this->xml->text($this->cliente->empresa->razao);
            $this->texto .= $this->cliente->empresa->razao;
            $this->xml->endElement(); #ans:nomeContratado
            $this->xml->endElement(); #ans:contratadoSolicitante
            $this->xml->startElement('ans:profissionalSolicitante');
            $this->xml->startElement('ans:nomeProfissional');
            $this->xml->text($medicao->profissional->pessoa->nome);
            $this->texto .= $medicao->profissional->pessoa->nome;
            $this->xml->endElement(); #ans:nomeProfissional
            $this->xml->startElement('ans:conselhoProfissional');
            $this->xml->text($medicao->profissional->conselhoProfissional);
            $this->texto .= $medicao->profissional->conselhoProfissional;
            $this->xml->endElement(); #ans:conselhoProfissional
            $this->xml->startElement('ans:numeroConselhoProfissional');
            $this->xml->text($medicao->profissional->numeroConselhoProfissional);
            $this->texto .= $medicao->profissional->numeroConselhoProfissional;
            $this->xml->endElement(); #ans:numeroConselhoProfissional
            $this->xml->startElement('ans:UF');
            $this->xml->text($medicao->profissional->uf);
            $this->texto .= $medicao->profissional->uf;
            $this->xml->endElement(); #ans:UF
            $this->xml->startElement('ans:CBOS');
            $this->xml->text($medicao->profissional->cbos);
            $this->texto .= $medicao->profissional->cbos;
            $this->xml->endElement(); #ans:CBOS
            $this->xml->endElement(); #ans:profissionalSolicitante
            $this->xml->endElement(); #ans:dadosSolicitante
            $this->xml->startElement('ans:dadosSolicitacao');
            $this->xml->startElement('ans:dataSolicitacao');
            $this->xml->text($medicao->dataSolicitacao);
            $this->texto .= $medicao->dataSolicitacao;
            $this->xml->endElement(); #ans:dataSolicitacao
            $this->xml->startElement('ans:caraterAtendimento');
            $this->xml->text($medicao->ordemservico->orcamento->caraterAtendimento);
            $this->texto .= $medicao->ordemservico->orcamento->caraterAtendimento;
            $this->xml->endElement(); #ans:caraterAtendimento
            $this->xml->startElement('ans:indicacaoClinica');
            $this->xml->text($medicao->ordemservico->orcamento->indicacaoClinica);
            $this->texto .= $medicao->ordemservico->orcamento->indicacaoClinica;
            $this->xml->endElement(); #ans:indicacaoClinica
            $this->xml->endElement(); #ans:dadosSolicitacao
            $this->xml->startElement('ans:dadosExecutante');
            $this->xml->startElement('ans:contratadoExecutante');
            $this->xml->startElement('ans:cnpjContratado');
            $this->xml->text($this->cliente->empresa->cnpj);
            $this->texto .= $this->cliente->empresa->cnpj;
            $this->xml->endElement(); #ans:cnpjContratado
            $this->xml->startElement('ans:nomeContratado');
            $this->xml->text($this->cliente->empresa->razao);
            $this->texto .= $this->cliente->empresa->razao;
            $this->xml->endElement(); #ans:nomeContratado
            $this->xml->endElement(); #ans:contratadoExecutante
            $this->xml->startElement('ans:CNES');
            $this->xml->text($this->cliente->empresa->CNES);
            $this->texto .= $this->cliente->empresa->CNES;
            $this->xml->endElement(); #ans:CNES
            $this->xml->endElement(); #ans:dadosExecutante
            $this->xml->startElement('ans:dadosAtendimento');
            $this->xml->startElement('ans:tipoAtendimento');
            $this->xml->text($medicao->ordemservico->orcamento->tipoAtendimento);
            $this->texto .= $medicao->ordemservico->orcamento->tipoAtendimento;
            $this->xml->endElement(); #ans:tipoAtendimento
            $this->xml->startElement('ans:indicacaoAcidente');
            $this->xml->text($medicao->ordemservico->orcamento->indicacaoAcidente);
            $this->texto .= $medicao->ordemservico->orcamento->indicacaoAcidente;
            $this->xml->endElement(); #ans:indicacaoAcidente
            $this->xml->endElement(); #ans:dadosAtendimento


            $this->xml->startElement('ans:procedimentosExecutados');
            foreach ($medicao->medicao_servicos as $key => $servico) {
                $this->xml->startElement('ans:procedimentoExecutado');
                $this->xml->startElement('ans:sequencialItem');
                $this->xml->text($this->sequencialItem);
                $this->texto .= $this->sequencialItem;
                $this->xml->endElement(); #ans:sequencialItem
                $this->xml->startElement('ans:dataExecucao');
                $this->xml->text($servico->dataExecucao);
                $this->texto .= $servico->dataExecucao;
                $this->xml->endElement(); #ans:dataExecucao
                $this->xml->startElement('ans:horaInicial');
                $this->xml->text($servico->horaInicial);
                $this->texto .= $servico->horaInicial;
                $this->xml->endElement(); #ans:horaInicial
                $this->xml->startElement('ans:horaFinal');
                $this->xml->text($servico->horaFinal);
                $this->texto .= $servico->horaFinal;
                $this->xml->endElement(); #ans:horaFinal
                $this->xml->startElement('ans:procedimento');
                $this->xml->startElement('ans:codigoTabela');
                $this->xml->text($servico->servico->codigoTabela);
                $this->texto .= $servico->servico->codigoTabela;
                $this->xml->endElement(); #ans:codigoTabela
                $this->xml->startElement('ans:codigoProcedimento');
                $this->xml->text($servico->servico->codtuss);
                $this->texto .= $servico->servico->codtuss;
                $this->xml->endElement(); #ans:codigoProcedimento
                $this->xml->startElement('ans:descricaoProcedimento');
                $this->xml->text($servico->servico->descricao);
                $this->texto .= $servico->servico->descricao;
                $this->xml->endElement(); #ans:descricaoProcedimento
                $this->xml->endElement(); #ans:procedimento
                $this->xml->startElement('ans:quantidadeExecutada');
                $this->xml->text($servico->atendido);
                $this->texto .= $servico->atendido;
                $this->xml->endElement(); #ans:quantidadeExecutada
                $this->xml->startElement('ans:reducaoAcrescimo');
                $this->xml->text($servico->reducaoAcrescimo);
                $this->texto .= $servico->reducaoAcrescimo;
                $this->xml->endElement(); #ans:reducaoAcrescimo
                $this->xml->startElement('ans:valorUnitario');
                $this->xml->text($servico->valor);
                $this->texto .= $servico->valor;
                $this->xml->endElement(); #ans:valorUnitario
                $this->xml->startElement('ans:valorTotal');
                $this->xml->text($servico->subtotal);
                $this->texto .= $servico->subtotal;
                $this->xml->endElement(); #ans:valorTotal
                $this->xml->endElement(); #ans:procedimentoExecutado
                $this->sequencialItem += 1;
                $dados['valorProcedimentos'] += $servico->subtotal;
            }
            $this->xml->endElement(); #ans:procedimentosExecutados

            $this->xml->startElement('ans:outrasDespesas');
            foreach ($medicao->medicao_produtos as $key => $produto) {
                $this->xml->startElement('ans:despesa');
                $this->xml->startElement('ans:sequencialItem');
                $this->xml->text($this->sequencialItem);
                $this->texto .= $this->sequencialItem;
                $this->xml->endElement(); #ans:sequencialItem
                $this->xml->startElement('ans:codigoDespesa');
                $this->xml->text($produto->produto->codigoDespesa);
                $this->texto .= $produto->produto->codigoDespesa;
                $this->xml->endElement(); #ans:codigoDespesa
                $this->xml->startElement('ans:servicosExecutados');
                $this->xml->startElement('ans:dataExecucao');
                $this->xml->text($produto->dataExecucao);
                $this->texto .= $produto->dataExecucao;
                $this->xml->endElement(); #ans:dataExecucao
                $this->xml->startElement('ans:horaInicial');
                $this->xml->text($produto->horaInicial);
                $this->texto .= $produto->horaInicial;
                $this->xml->endElement(); #ans:horaInicial
                $this->xml->startElement('ans:horaFinal');
                $this->xml->text($produto->horaFinal);
                $this->texto .= $produto->horaFinal;
                $this->xml->endElement(); #ans:horaFinal
                $this->xml->startElement('ans:codigoTabela');
                $this->xml->text($produto->produto->codigoTabela);
                $this->texto .= $produto->produto->codigoTabela;
                $this->xml->endElement(); #ans:codigoTabela
                $this->xml->startElement('ans:codigoProcedimento');
                $this->xml->text($produto->produto->codtuss);
                $this->texto .= $produto->produto->codtuss;
                $this->xml->endElement(); #ans:codigoProcedimento
                $this->xml->startElement('ans:quantidadeExecutada');
                $this->xml->text($produto->atendido);
                $this->texto .= $produto->atendido;
                $this->xml->endElement(); #ans:quantidadeExecutada
                $this->xml->startElement('ans:unidadeMedida');
                $this->xml->text($produto->produto->unidademedida->codigo);
                $this->texto .= $produto->produto->unidademedida->codigo;
                $this->xml->endElement(); #ans:unidadeMedida
                $this->xml->startElement('ans:reducaoAcrescimo');
                $this->xml->text($produto->reducaoAcrescimo);
                $this->texto .= $produto->reducaoAcrescimo;
                $this->xml->endElement(); #ans:reducaoAcrescimo
                $this->xml->startElement('ans:valorUnitario');
                $this->xml->text($produto->valor);
                $this->texto .= $produto->valor;
                $this->xml->endElement(); #ans:valorUnitario
                $this->xml->startElement('ans:valorTotal');
                $this->xml->text($produto->subtotal);
                $this->texto .= $produto->subtotal;
                $this->xml->endElement(); #ans:valorTotal
                $this->xml->startElement('ans:descricaoProcedimento');
                $this->xml->text($produto->produto->descricao);
                $this->texto .= $produto->produto->descricao;
                $this->xml->endElement(); #ans:descricaoProcedimento
                $this->xml->endElement(); #ans:servicosExecutados
                $this->xml->endElement(); #ans:despesa
                $this->sequencialItem += 1;
                switch ($produto->produto->codigoDespesa) {
                    case '01':
                        $dados['valorGasesMedicinais'] += $produto->subtotal;
                        break;
                    case '02':
                        $dados['valorMedicamentos'] += $produto->subtotal;
                        break;
                    case '03':
                        $dados['valorMateriais'] += $produto->subtotal;
                        break;
                    case '05':
                        $dados['valorDiarias'] += $produto->subtotal;
                        break;
                    case '07':
                        $dados['valorTaxasAlugueis'] += $produto->subtotal;
                        break;
                    case '08':
                        $dados['valorOPME'] += $produto->subtotal;
                        break;
                    default:
                        break;
                }
            }
            $this->xml->endElement(); #ans:outrasDespesas>
            $dados['valorTotalGeral'] =
                $dados['valorProcedimentos']   +
                $dados['valorDiarias']         +
                $dados['valorTaxasAlugueis']   +
                $dados['valorMateriais']       +
                $dados['valorMedicamentos']    +
                $dados['valorOPME']            +
                $dados['valorGasesMedicinais'];
            $this->xml->startElement('ans:valorTotal');
            $this->xml->startElement('ans:valorProcedimentos');
            $this->xml->text($dados['valorProcedimentos']);
            $this->texto .= $dados['valorProcedimentos'];
            $this->xml->endElement(); #ans:valorProcedimentos
            $this->xml->startElement('ans:valorDiarias');
            $this->xml->text($dados['valorDiarias']);
            $this->texto .= $dados['valorDiarias'];
            $this->xml->endElement(); #ans:valorDiarias
            $this->xml->startElement('ans:valorTaxasAlugueis');
            $this->xml->text($dados['valorTaxasAlugueis']);
            $this->texto .= $dados['valorTaxasAlugueis'];
            $this->xml->endElement(); #ans:valorTaxasAlugueis
            $this->xml->startElement('ans:valorMateriais');
            $this->xml->text($dados['valorMateriais']);
            $this->texto .= $dados['valorMateriais'];
            $this->xml->endElement(); #ans:valorMateriais
            $this->xml->startElement('ans:valorMedicamentos');
            $this->xml->text($dados['valorMedicamentos']);
            $this->texto .= $dados['valorMedicamentos'];
            $this->xml->endElement(); #ans:valorMedicamentos
            $this->xml->startElement('ans:valorOPME');
            $this->xml->text($dados['valorOPME']);
            $this->texto .= $dados['valorOPME'];
            $this->xml->endElement(); #ans:valorOPME
            $this->xml->startElement('ans:valorGasesMedicinais');
            $this->xml->text($dados['valorGasesMedicinais']);
            $this->texto .= $dados['valorGasesMedicinais'];
            $this->xml->endElement(); #ans:valorGasesMedicinais
            $this->xml->startElement('ans:valorTotalGeral');
            $this->xml->text($dados['valorTotalGeral']);
            $this->texto .= $dados['valorTotalGeral'];
            $this->xml->endElement(); #ans:valorTotalGeral
            $this->xml->endElement(); #ans:valorTotal
            $this->xml->endElement(); #ans:guiaSP-SADT

            $medicao->tiss_id = $this->tiss->id;
            $medicao->save();
        }
        $this->xml->endElement(); #ans:guiasTISS
        $this->xml->endElement(); #ans:loteGuias
        $this->xml->endElement(); #ans:prestadorParaOperadora
        $this->xml->startElement('ans:epilogo');
        $this->xml->startElement('ans:hash');
        $this->xml->text(hash('ripemd160', $this->texto));
        $this->xml->endElement(); #ans:hash
        $this->xml->endElement(); #ans:epilogo
        $this->xml->endElement(); #ans:mensagemTISS
    }
}
