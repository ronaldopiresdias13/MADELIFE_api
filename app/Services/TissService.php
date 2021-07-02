<?php

namespace App\Services;

use XMLWriter;

class TissService
{
    protected $xml = null;

    protected $dados = [];

    protected $sequencialItem = 1;

    protected $texto = '';

    // protected $return = null;

    public function __construct($medicao, $empresa)
    {
        $this->dados['tipoTransacao']              = 'ENVIO_LOTE_GUIAS';
        $this->dados['sequencialTransacao']        = $empresa->tiss_sequencialTransacao;
        $this->dados['dataRegistroTransacao']      = substr($medicao->created_at, 0, 10);
        $this->dados['horaRegistroTransacao']      = substr($medicao->created_at, 11, 8);
        $this->dados['CNPJ']                       = $empresa->cnpj;
        $this->dados['registroANS']                = $empresa->registroANS;
        $this->dados['versaoPadrao']               = str_replace('_', '.', $medicao->cliente->versaoTiss);
        $this->dados['numeroLote']                 = '0';
        $this->dados['numeroGuiaPrestador']        = $medicao->numeroGuiaPrestador;
        $this->dados['numeroCarteira']             = $medicao->ordemservico->orcamento->homecare->paciente->numeroCarteira;
        $this->dados['atendimentoRN']              = $medicao->ordemservico->orcamento->homecare->paciente->atendimentoRN ? 'S' : 'N';
        $this->dados['nomeBeneficiario']           = $medicao->ordemservico->orcamento->homecare->paciente->pessoa->nome;
        $this->dados['cnpjContratado']             = $empresa->cnpj;
        $this->dados['nomeContratado']             = $empresa->razao;
        $this->dados['nomeProfissional']           = $medicao->profissional->pessoa->nome;
        $this->dados['conselhoProfissional']       = $medicao->profissional->conselhoProfissional;
        $this->dados['numeroConselhoProfissional'] = $medicao->profissional->numeroConselhoProfissional;
        $this->dados['UF']                         = $medicao->profissional->uf;
        $this->dados['CBOS']                       = $medicao->profissional->cbos;
        $this->dados['CNES']                       = $empresa->CNES;
        $this->dados['dataSolicitacao']            = $medicao->dataSolicitacao;
        $this->dados['caraterAtendimento']         = $medicao->ordemservico->orcamento->caraterAtendimento;
        $this->dados['indicacaoClinica']           = $medicao->ordemservico->orcamento->indicacaoClinica;
        $this->dados['tipoAtendimento']            = $medicao->ordemservico->orcamento->tipoAtendimento;
        $this->dados['indicacaoAcidente']          = $medicao->ordemservico->orcamento->indicacaoAcidente;

        $this->dados['valorProcedimentos']   = 0;
        $this->dados['valorDiarias']         = 0;
        $this->dados['valorTaxasAlugueis']   = 0;
        $this->dados['valorMateriais']       = 0;
        $this->dados['valorMedicamentos']    = 0;
        $this->dados['valorOPME']            = 0;
        $this->dados['valorGasesMedicinais'] = 0;

        $this->dados['procedimentosExecutados'] = [];
        foreach ($medicao->medicao_servicos as $key => $servicos) {
            $array = [
                'dataExecucao'          => $servicos->dataExecucao,
                'horaInicial'           => $servicos->horaInicial,
                'horaFinal'             => $servicos->horaFinal,
                'codigoTabela'          => $servicos->servico->codigoTabela,
                'codigoProcedimento'    => $servicos->servico->codtuss,
                'descricaoProcedimento' => $servicos->servico->descricao,
                'quantidadeExecutada'   => $servicos->atendido,
                'reducaoAcrescimo'      => $servicos->reducaoAcrescimo,
                'valorUnitario'         => $servicos->valor,
                'valorTotal'            => $servicos->subtotal,
            ];
            array_push($this->dados['procedimentosExecutados'], $array);
            $this->dados['valorProcedimentos'] += $servicos->subtotal;
        }

        $this->dados['outrasDespesas'] = [];
        foreach ($medicao->medicao_produtos as $key => $produtos) {
            $array = [
                'codigoDespesa'         => $produtos->produto->codigoDespesa,
                'dataExecucao'          => $produtos->dataExecucao,
                'horaInicial'           => $produtos->horaInicial,
                'horaFinal'             => $produtos->horaFinal,
                'codigoTabela'          => $produtos->produto->codigoTabela,
                'codigoProcedimento'    => $produtos->produto->codtuss,
                'quantidadeExecutada'   => $produtos->atendido,
                'unidadeMedida'         => $produtos->produto->unidademedida->codigo,
                'reducaoAcrescimo'      => $produtos->reducaoAcrescimo,
                'valorUnitario'         => $produtos->valor,
                'valorTotal'            => $produtos->subtotal,
                'descricaoProcedimento' => $produtos->produto->descricao
            ];#Pegar dados da medição-produtos
            array_push($this->dados['outrasDespesas'], $array);
            switch ($produtos->produto->codigoDespesa) {
                case '01':
                    $this->dados['valorGasesMedicinais'] += $produtos->subtotal;
                    break;
                case '02':
                    $this->dados['valorMedicamentos'] += $produtos->subtotal;
                    break;
                case '03':
                    $this->dados['valorMateriais'] += $produtos->subtotal;
                    break;
                case '05':
                    $this->dados['valorDiarias'] += $produtos->subtotal;
                    break;
                case '07':
                    $this->dados['valorTaxasAlugueis'] += $produtos->subtotal;
                    break;
                case '08':
                    $this->dados['valorOPME'] += $produtos->subtotal;
                    break;
                default:
                    break;
            }
        }

        $this->dados['valorTotalGeral'] =
        $this->dados['valorProcedimentos']   +
        $this->dados['valorDiarias']         +
        $this->dados['valorTaxasAlugueis']   +
        $this->dados['valorMateriais']       +
        $this->dados['valorMedicamentos']    +
        $this->dados['valorOPME']            +
        $this->dados['valorGasesMedicinais'];

        $this->dados['hash'] = '';
    }

    function iniciarArquivo()
    {
        // echo $oXMLWriter->outputMemory ();
        $this->xml = new XMLWriter();
        $this->xml->openMemory();
        // $this->xml->openUri('file:///home/lucas/Área de Trabalho/zzzz/Laravel/Exemplo TISS TUSS/XMLs/output.xml');
        $this->xml->startDocument('1.0', 'utf-8');
    }

    function finalizarArquivo()
    {
        $this->xml->endDocument();
    }

    public function gerar_xml_3_02_00()
    {
        $this->iniciarArquivo();
        $this->xml->startElement('ans:mensagemTISS');
            $this->xml->writeAttribute('xmlns:ans', 'http://www.ans.gov.br/padroes/tiss/schemas');
            $this->xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
            $this->xml->writeAttribute('xsi:schemaLocation', 'http://www.ans.gov.br/padroes/tiss/schemas tissV3_02_00.xsd');
            $this->xml->startElement('ans:cabecalho');
                $this->xml->startElement('ans:identificacaoTransacao');
                    $this->xml->startElement('ans:tipoTransacao');
                        $this->xml->text($this->dados['tipoTransacao']);
                        $this->texto .= $this->dados['tipoTransacao'];
                    $this->xml->endElement();#ans:tipoTransacao
                    $this->xml->startElement('ans:sequencialTransacao');
                        $this->xml->text($this->dados['sequencialTransacao']);
                        $this->texto .= $this->dados['sequencialTransacao'];
                    $this->xml->endElement();#ans:sequencialTransacao
                    $this->xml->startElement('ans:dataRegistroTransacao');
                        $this->xml->text($this->dados['dataRegistroTransacao']);
                        $this->texto .= $this->dados['dataRegistroTransacao'];
                    $this->xml->endElement();#ans:dataRegistroTransacao
                    $this->xml->startElement('ans:horaRegistroTransacao');
                        $this->xml->text($this->dados['horaRegistroTransacao']);
                        $this->texto .= $this->dados['horaRegistroTransacao'];
                    $this->xml->endElement();#ans:horaRegistroTransacao
                $this->xml->endElement();#ans:identificacaoTransacao>
                $this->xml->startElement('ans:origem');
                    $this->xml->startElement('ans:identificacaoPrestador');
                        $this->xml->startElement('ans:CNPJ');
                            $this->xml->text($this->dados['CNPJ']);
                            $this->texto .= $this->dados['CNPJ'];
                        $this->xml->endElement();#ans:CNPJ');
                    $this->xml->endElement();#ans:identificacaoPrestador
                $this->xml->endElement();#ans:origem
                $this->xml->startElement('ans:destino');
                    $this->xml->startElement('ans:registroANS');
                        $this->xml->text($this->dados['registroANS']);
                        $this->texto .= $this->dados['registroANS'];
                    $this->xml->endElement();#ans:registroANS
                $this->xml->endElement();#ans:destino
                $this->xml->startElement('ans:versaoPadrao');
                    $this->xml->text($this->dados['versaoPadrao']);
                    $this->texto .= $this->dados['versaoPadrao'];
                $this->xml->endElement();#ans:versaoPadrao
            $this->xml->endElement();#ans:cabecalho
            $this->xml->startElement('ans:prestadorParaOperadora');
                $this->xml->startElement('ans:loteGuias');
                    $this->xml->startElement('ans:numeroLote');
                        $this->xml->text($this->dados['numeroLote']);
                        $this->texto .= $this->dados['numeroLote'];
                    $this->xml->endElement();#ans:numeroLote');
                    $this->xml->startElement('ans:guiasTISS');
                        $this->xml->startElement('ans:guiaSP-SADT'); // Foreach
                            $this->xml->startElement('ans:cabecalhoGuia');
                                $this->xml->startElement('ans:registroANS');
                                    $this->xml->text($this->dados['registroANS']);
                                    $this->texto .= $this->dados['registroANS'];
                                $this->xml->endElement();#ans:registroANS
                                $this->xml->startElement('ans:numeroGuiaPrestador');
                                    $this->xml->text($this->dados['numeroGuiaPrestador']);
                                    $this->texto .= $this->dados['numeroGuiaPrestador'];
                                $this->xml->endElement();#ans:numeroGuiaPrestador
                            $this->xml->endElement();#ans:cabecalhoGuia
                            $this->xml->startElement('ans:dadosBeneficiario');
                                $this->xml->startElement('ans:numeroCarteira');
                                    $this->xml->text($this->dados['numeroCarteira']);
                                    $this->texto .= $this->dados['numeroCarteira'];
                                $this->xml->endElement();#ans:numeroCarteira
                                $this->xml->startElement('ans:atendimentoRN');
                                    $this->xml->text($this->dados['atendimentoRN']);
                                    $this->texto .= $this->dados['atendimentoRN'];
                                $this->xml->endElement();#ans:atendimentoRN
                                $this->xml->startElement('ans:nomeBeneficiario');
                                    $this->xml->text($this->dados['nomeBeneficiario']);
                                    $this->texto .= $this->dados['nomeBeneficiario'];
                                $this->xml->endElement();#ans:nomeBeneficiario
                            $this->xml->endElement();#ans:dadosBeneficiario
                            $this->xml->startElement('ans:dadosSolicitante');
                                $this->xml->startElement('ans:contratadoSolicitante');
                                    $this->xml->startElement('ans:cnpjContratado');
                                        $this->xml->text($this->dados['cnpjContratado']);
                                        $this->texto .= $this->dados['cnpjContratado'];
                                    $this->xml->endElement();#ans:cnpjContratado
                                    $this->xml->startElement('ans:nomeContratado');
                                        $this->xml->text($this->dados['nomeContratado']);
                                        $this->texto .= $this->dados['nomeContratado'];
                                    $this->xml->endElement();#ans:nomeContratado
                                $this->xml->endElement();#ans:contratadoSolicitante
                                $this->xml->startElement('ans:profissionalSolicitante');
                                    $this->xml->startElement('ans:nomeProfissional');
                                        $this->xml->text($this->dados['nomeProfissional']);
                                        $this->texto .= $this->dados['nomeProfissional'];
                                    $this->xml->endElement();#ans:nomeProfissional
                                    $this->xml->startElement('ans:conselhoProfissional');
                                        $this->xml->text($this->dados['conselhoProfissional']);
                                        $this->texto .= $this->dados['conselhoProfissional'];
                                    $this->xml->endElement();#ans:conselhoProfissional
                                    $this->xml->startElement('ans:numeroConselhoProfissional');
                                        $this->xml->text($this->dados['numeroConselhoProfissional']);
                                        $this->texto .= $this->dados['numeroConselhoProfissional'];
                                    $this->xml->endElement();#ans:numeroConselhoProfissional
                                    $this->xml->startElement('ans:UF');
                                        $this->xml->text($this->dados['UF']);
                                        $this->texto .= $this->dados['UF'];
                                    $this->xml->endElement();#ans:UF
                                    $this->xml->startElement('ans:CBOS');
                                        $this->xml->text($this->dados['CBOS']);
                                        $this->texto .= $this->dados['CBOS'];
                                    $this->xml->endElement();#ans:CBOS
                                $this->xml->endElement();#ans:profissionalSolicitante
                            $this->xml->endElement();#ans:dadosSolicitante
                            $this->xml->startElement('ans:dadosSolicitacao');
                                $this->xml->startElement('ans:dataSolicitacao');
                                    $this->xml->text($this->dados['dataSolicitacao']);
                                    $this->texto .= $this->dados['dataSolicitacao'];
                                $this->xml->endElement();#ans:dataSolicitacao
                                $this->xml->startElement('ans:caraterAtendimento');
                                    $this->xml->text($this->dados['caraterAtendimento']);
                                    $this->texto .= $this->dados['caraterAtendimento'];
                                $this->xml->endElement();#ans:caraterAtendimento
                                $this->xml->startElement('ans:indicacaoClinica');
                                    $this->xml->text($this->dados['indicacaoClinica']);
                                    $this->texto .= $this->dados['indicacaoClinica'];
                                $this->xml->endElement();#ans:indicacaoClinica
                            $this->xml->endElement();#ans:dadosSolicitacao
                            $this->xml->startElement('ans:dadosExecutante');
                                $this->xml->startElement('ans:contratadoExecutante');
                                    $this->xml->startElement('ans:cnpjContratado');
                                        $this->xml->text($this->dados['cnpjContratado']);
                                        $this->texto .= $this->dados['cnpjContratado'];
                                    $this->xml->endElement();#ans:cnpjContratado
                                    $this->xml->startElement('ans:nomeContratado');
                                        $this->xml->text($this->dados['nomeContratado']);
                                        $this->texto .= $this->dados['nomeContratado'];
                                    $this->xml->endElement();#ans:nomeContratado
                                $this->xml->endElement();#ans:contratadoExecutante
                                $this->xml->startElement('ans:CNES');
                                    $this->xml->text($this->dados['CNES']);
                                    $this->texto .= $this->dados['CNES'];
                                $this->xml->endElement();#ans:CNES
                            $this->xml->endElement();#ans:dadosExecutante
                            $this->xml->startElement('ans:dadosAtendimento');
                                $this->xml->startElement('ans:tipoAtendimento');
                                    $this->xml->text($this->dados['tipoAtendimento']);
                                    $this->texto .= $this->dados['tipoAtendimento'];
                                $this->xml->endElement();#ans:tipoAtendimento
                                $this->xml->startElement('ans:indicacaoAcidente');
                                    $this->xml->text($this->dados['indicacaoAcidente']);
                                    $this->texto .= $this->dados['indicacaoAcidente'];
                                $this->xml->endElement();#ans:indicacaoAcidente
                            $this->xml->endElement();#ans:dadosAtendimento

                            $this->xml->startElement('ans:procedimentosExecutados');
                                foreach ($this->dados['procedimentosExecutados'] as $key => $value) {
                                    $this->xml->startElement('ans:procedimentoExecutado');
                                        $this->xml->startElement('ans:dataExecucao');
                                            $this->xml->text($value['dataExecucao']);
                                            $this->texto .= $value['dataExecucao'];
                                        $this->xml->endElement();#ans:dataExecucao
                                        $this->xml->startElement('ans:horaInicial');
                                            $this->xml->text($value['horaInicial']);
                                            $this->texto .= $value['horaInicial'];
                                        $this->xml->endElement();#ans:horaInicial
                                        $this->xml->startElement('ans:horaFinal');
                                            $this->xml->text($value['horaFinal']);
                                            $this->texto .= $value['horaFinal'];
                                        $this->xml->endElement();#ans:horaFinal
                                        $this->xml->startElement('ans:procedimento');
                                            $this->xml->startElement('ans:codigoTabela');
                                                $this->xml->text($value['codigoTabela']);
                                                $this->texto .= $value['codigoTabela'];
                                            $this->xml->endElement();#ans:codigoTabela
                                            $this->xml->startElement('ans:codigoProcedimento');
                                                $this->xml->text($value['codigoProcedimento']);
                                                $this->texto .= $value['codigoProcedimento'];
                                            $this->xml->endElement();#ans:codigoProcedimento
                                            $this->xml->startElement('ans:descricaoProcedimento');
                                                $this->xml->text($value['descricaoProcedimento']);
                                                $this->texto .= $value['descricaoProcedimento'];
                                            $this->xml->endElement();#ans:descricaoProcedimento
                                        $this->xml->endElement();#ans:procedimento
                                        $this->xml->startElement('ans:quantidadeExecutada');
                                            $this->xml->text($value['quantidadeExecutada']);
                                            $this->texto .= $value['quantidadeExecutada'];
                                        $this->xml->endElement();#ans:quantidadeExecutada
                                        $this->xml->startElement('ans:reducaoAcrescimo');
                                            $this->xml->text($value['reducaoAcrescimo']);
                                            $this->texto .= $value['reducaoAcrescimo'];
                                        $this->xml->endElement();#ans:reducaoAcrescimo
                                        $this->xml->startElement('ans:valorUnitario');
                                            $this->xml->text($value['valorUnitario']);
                                            $this->texto .= $value['valorUnitario'];
                                        $this->xml->endElement();#ans:valorUnitario
                                        $this->xml->startElement('ans:valorTotal');
                                            $this->xml->text($value['valorTotal']);
                                            $this->texto .= $value['valorTotal'];
                                        $this->xml->endElement();#ans:valorTotal
                                    $this->xml->endElement();#ans:procedimentoExecutado
                                }
                            $this->xml->endElement();#ans:procedimentosExecutados

                            $this->xml->startElement('ans:outrasDespesas');
                                foreach ($this->dados['outrasDespesas'] as $key => $value) {
                                    $this->xml->startElement('ans:despesa');
                                        $this->xml->startElement('ans:codigoDespesa');
                                            $this->xml->text($value['codigoDespesa']);
                                            $this->texto .= $value['codigoDespesa'];
                                        $this->xml->endElement();#ans:codigoDespesa
                                        $this->xml->startElement('ans:servicosExecutados');
                                            $this->xml->startElement('ans:dataExecucao');
                                                $this->xml->text($value['dataExecucao']);
                                                $this->texto .= $value['dataExecucao'];
                                            $this->xml->endElement();#ans:dataExecucao
                                            $this->xml->startElement('ans:horaInicial');
                                                $this->xml->text($value['horaInicial']);
                                                $this->texto .= $value['horaInicial'];
                                            $this->xml->endElement();#ans:horaInicial
                                            $this->xml->startElement('ans:horaFinal');
                                                $this->xml->text($value['horaFinal']);
                                                $this->texto .= $value['horaFinal'];
                                            $this->xml->endElement();#ans:horaFinal
                                            $this->xml->startElement('ans:codigoTabela');
                                                $this->xml->text($value['codigoTabela']);
                                                $this->texto .= $value['codigoTabela'];
                                            $this->xml->endElement();#ans:codigoTabela
                                            $this->xml->startElement('ans:codigoProcedimento');
                                                $this->xml->text($value['codigoProcedimento']);
                                                $this->texto .= $value['codigoProcedimento'];
                                            $this->xml->endElement();#ans:codigoProcedimento
                                            $this->xml->startElement('ans:quantidadeExecutada');
                                                $this->xml->text($value['quantidadeExecutada']);
                                                $this->texto .= $value['quantidadeExecutada'];
                                            $this->xml->endElement();#ans:quantidadeExecutada
                                            $this->xml->startElement('ans:unidadeMedida');
                                                $this->xml->text($value['unidadeMedida']);
                                                $this->texto .= $value['unidadeMedida'];
                                            $this->xml->endElement();#ans:unidadeMedida
                                            $this->xml->startElement('ans:reducaoAcrescimo');
                                                $this->xml->text($value['reducaoAcrescimo']);
                                                $this->texto .= $value['reducaoAcrescimo'];
                                            $this->xml->endElement();#ans:reducaoAcrescimo
                                            $this->xml->startElement('ans:valorUnitario');
                                                $this->xml->text($value['valorUnitario']);
                                                $this->texto .= $value['valorUnitario'];
                                            $this->xml->endElement();#ans:valorUnitario
                                            $this->xml->startElement('ans:valorTotal');
                                                $this->xml->text($value['valorTotal']);
                                                $this->texto .= $value['valorTotal'];
                                            $this->xml->endElement();#ans:valorTotal
                                            $this->xml->startElement('ans:descricaoProcedimento');
                                                $this->xml->text($value['descricaoProcedimento']);
                                                $this->texto .= $value['descricaoProcedimento'];
                                            $this->xml->endElement();#ans:descricaoProcedimento
                                        $this->xml->endElement();#ans:servicosExecutados
                                    $this->xml->endElement();#ans:despesa
                                }
                            $this->xml->endElement();#ans:outrasDespesas

                            $this->xml->startElement('ans:valorTotal');
                                $this->xml->startElement('ans:valorProcedimentos');
                                    $this->xml->text($this->dados['valorProcedimentos']);
                                    $this->texto .= $this->dados['valorProcedimentos'];
                                $this->xml->endElement();#ans:valorProcedimentos
                                $this->xml->startElement('ans:valorDiarias');
                                    $this->xml->text($this->dados['valorDiarias']);
                                    $this->texto .= $this->dados['valorDiarias'];
                                $this->xml->endElement();#ans:valorDiarias
                                $this->xml->startElement('ans:valorTaxasAlugueis');
                                    $this->xml->text($this->dados['valorTaxasAlugueis']);
                                    $this->texto .= $this->dados['valorTaxasAlugueis'];
                                $this->xml->endElement();#ans:valorTaxasAlugueis
                                $this->xml->startElement('ans:valorMateriais');
                                    $this->xml->text($this->dados['valorMateriais']);
                                    $this->texto .= $this->dados['valorMateriais'];
                                $this->xml->endElement();#ans:valorMateriais
                                $this->xml->startElement('ans:valorMedicamentos');
                                    $this->xml->text($this->dados['valorMedicamentos']);
                                    $this->texto .= $this->dados['valorMedicamentos'];
                                $this->xml->endElement();#ans:valorMedicamentos
                                $this->xml->startElement('ans:valorOPME');
                                    $this->xml->text($this->dados['valorOPME']);
                                    $this->texto .= $this->dados['valorOPME'];
                                $this->xml->endElement();#ans:valorOPME
                                $this->xml->startElement('ans:valorGasesMedicinais');
                                    $this->xml->text($this->dados['valorGasesMedicinais']);
                                    $this->texto .= $this->dados['valorGasesMedicinais'];
                                $this->xml->endElement();#ans:valorGasesMedicinais
                                $this->xml->startElement('ans:valorTotalGeral');
                                    $this->xml->text($this->dados['valorTotalGeral']);
                                    $this->texto .= $this->dados['valorTotalGeral'];
                                $this->xml->endElement();#ans:valorTotalGeral
                            $this->xml->endElement();#ans:valorTotal>

                        $this->xml->endElement();#ans:guiaSP-SADT>
                    $this->xml->endElement();#ans:guiasTISS>
                $this->xml->endElement();#ans:loteGuias>
            $this->xml->endElement();#ans:prestadorParaOperadora>
            $this->xml->startElement('ans:epilogo');
                $this->xml->startElement('ans:hash');
                    $this->xml->text(hash('ripemd160', $this->texto));
                $this->xml->endElement();#ans:hash');
            $this->xml->endElement();#ans:epilogo>
        $this->xml->endElement();#ans:mensagemTISS>
        $this->finalizarArquivo();

        // return $this->texto;
        return base64_encode($this->xml->outputMemory());
        // return $this->return;
    }

    public function gerar_xml_3_05_00()
    {
        $this->iniciarArquivo();
        $this->xml->startElement('ans:mensagemTISS');
            $this->xml->writeAttribute('xmlns:ans', 'http://www.ans.gov.br/padroes/tiss/schemas');
            $this->xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
            $this->xml->writeAttribute('xsi:schemaLocation', 'http://www.ans.gov.br/padroes/tiss/schemas tissV3_05_00.xsd');

            $this->xml->startElement('ans:cabecalho');
                $this->xml->startElement('ans:identificacaoTransacao');
                    $this->xml->startElement('ans:tipoTransacao');
                        $this->xml->text($this->dados['tipoTransacao']);
                        $this->texto .= $this->dados['tipoTransacao'];
                    $this->xml->endElement();#ans:tipoTransacao
                    $this->xml->startElement('ans:sequencialTransacao');
                        $this->xml->text($this->dados['sequencialTransacao']);
                        $this->texto .= $this->dados['sequencialTransacao'];
                    $this->xml->endElement();#ans:sequencialTransacao
                    $this->xml->startElement('ans:dataRegistroTransacao');
                        $this->xml->text($this->dados['dataRegistroTransacao']);
                        $this->texto .= $this->dados['dataRegistroTransacao'];
                    $this->xml->endElement();#ans:dataRegistroTransacao
                    $this->xml->startElement('ans:horaRegistroTransacao');
                        $this->xml->text($this->dados['horaRegistroTransacao']);
                        $this->texto .= $this->dados['horaRegistroTransacao'];
                    $this->xml->endElement();#ans:horaRegistroTransacao
                $this->xml->endElement();#ans:identificacaoTransacao
                $this->xml->startElement('ans:origem');
                    $this->xml->startElement('ans:identificacaoPrestador');
                        $this->xml->startElement('ans:CNPJ');
                            $this->xml->text($this->dados['CNPJ']);
                            $this->texto .= $this->dados['CNPJ'];
                        $this->xml->endElement();#ans:CNPJ
                    $this->xml->endElement();#ans:identificacaoPrestador
                $this->xml->endElement();#ans:origem
                $this->xml->startElement('ans:destino');
                    $this->xml->startElement('ans:registroANS');
                        $this->xml->text($this->dados['registroANS']);
                        $this->texto .= $this->dados['registroANS'];
                    $this->xml->endElement();#ans:registroANS
                $this->xml->endElement();#ans:destino
                $this->xml->startElement('ans:Padrao');
                    $this->xml->text($this->dados['versaoPadrao']);
                    $this->texto .= $this->dados['versaoPadrao'];
                $this->xml->endElement();#ans:Padrao
            $this->xml->endElement();#ans:cabecalho

            $this->xml->startElement('ans:prestadorParaOperadora');
                $this->xml->startElement('ans:loteGuias');
                    $this->xml->startElement('ans:numeroLote');
                        $this->xml->text($this->dados['numeroLote']);
                        $this->texto .= $this->dados['numeroLote'];
                    $this->xml->endElement();#ans:numeroLote
                    $this->xml->startElement('ans:guiasTISS');
                        $this->xml->startElement('ans:guiaSP-SADT');
                            $this->xml->startElement('ans:cabecalhoGuia');
                                $this->xml->startElement('ans:registroANS');
                                    $this->xml->text($this->dados['registroANS']);
                                    $this->texto .= $this->dados['registroANS'];
                                $this->xml->endElement();#ans:registroANS
                                $this->xml->startElement('ans:numeroGuiaPrestador');
                                    $this->xml->text($this->dados['numeroGuiaPrestador']);
                                    $this->texto .= $this->dados['numeroGuiaPrestador'];
                                $this->xml->endElement();#ans:numeroGuiaPrestador
                            $this->xml->endElement();#ans:cabecalhoGuia
                            $this->xml->startElement('ans:dadosBeneficiario');
                                $this->xml->startElement('ans:numeroCarteira');
                                    $this->xml->text($this->dados['numeroCarteira']);
                                    $this->texto .= $this->dados['numeroCarteira'];
                                $this->xml->endElement();#ans:numeroCarteira
                                $this->xml->startElement('ans:atendimentoRN');
                                    $this->xml->text($this->dados['atendimentoRN']);
                                    $this->texto .= $this->dados['atendimentoRN'];
                                $this->xml->endElement();#ans:atendimentoRN
                                $this->xml->startElement('ans:nomeBeneficiario');
                                    $this->xml->text($this->dados['nomeBeneficiario']);
                                    $this->texto .= $this->dados['nomeBeneficiario'];
                                $this->xml->endElement();#ans:nomeBeneficiario
                            $this->xml->endElement();#ans:dadosBeneficiario
                            $this->xml->startElement('ans:dadosSolicitante');
                                $this->xml->startElement('ans:contratadoSolicitante');
                                    $this->xml->startElement('ans:cnpjContratado');
                                        $this->xml->text($this->dados['cnpjContratado']);
                                        $this->texto .= $this->dados['cnpjContratado'];
                                    $this->xml->endElement();#ans:cnpjContratado
                                    $this->xml->startElement('ans:nomeContratado');
                                        $this->xml->text($this->dados['nomeContratado']);
                                        $this->texto .= $this->dados['nomeContratado'];
                                    $this->xml->endElement();#ans:nomeContratado
                                $this->xml->endElement();#ans:contratadoSolicitante
                                $this->xml->startElement('ans:profissionalSolicitante');
                                    $this->xml->startElement('ans:nomeProfissional');
                                        $this->xml->text($this->dados['nomeProfissional']);
                                        $this->texto .= $this->dados['nomeProfissional'];
                                    $this->xml->endElement();#ans:nomeProfissional
                                    $this->xml->startElement('ans:conselhoProfissional');
                                        $this->xml->text($this->dados['conselhoProfissional']);
                                        $this->texto .= $this->dados['conselhoProfissional'];
                                    $this->xml->endElement();#ans:conselhoProfissional
                                    $this->xml->startElement('ans:numeroConselhoProfissional');
                                        $this->xml->text($this->dados['numeroConselhoProfissional']);
                                        $this->texto .= $this->dados['numeroConselhoProfissional'];
                                    $this->xml->endElement();#ans:numeroConselhoProfissional
                                    $this->xml->startElement('ans:UF');
                                        $this->xml->text($this->dados['UF']);
                                        $this->texto .= $this->dados['UF'];
                                    $this->xml->endElement();#ans:UF
                                    $this->xml->startElement('ans:CBOS');
                                        $this->xml->text($this->dados['CBOS']);
                                        $this->texto .= $this->dados['CBOS'];
                                    $this->xml->endElement();#ans:CBOS
                                $this->xml->endElement();#ans:profissionalSolicitante
                            $this->xml->endElement();#ans:dadosSolicitante
                            $this->xml->startElement('ans:dadosSolicitacao');
                                $this->xml->startElement('ans:dataSolicitacao');
                                    $this->xml->text($this->dados['dataSolicitacao']);
                                    $this->texto .= $this->dados['dataSolicitacao'];
                                $this->xml->endElement();#ans:dataSolicitacao
                                $this->xml->startElement('ans:caraterAtendimento');
                                    $this->xml->text($this->dados['caraterAtendimento']);
                                    $this->texto .= $this->dados['caraterAtendimento'];
                                $this->xml->endElement();#ans:caraterAtendimento
                                $this->xml->startElement('ans:indicacaoClinica');
                                    $this->xml->text($this->dados['indicacaoClinica']);
                                    $this->texto .= $this->dados['indicacaoClinica'];
                                $this->xml->endElement();#ans:indicacaoClinica
                            $this->xml->endElement();#ans:dadosSolicitacao
                            $this->xml->startElement('ans:dadosExecutante');
                                $this->xml->startElement('ans:contratadoExecutante');
                                    $this->xml->startElement('ans:cnpjContratado');
                                        $this->xml->text($this->dados['cnpjContratado']);
                                        $this->texto .= $this->dados['cnpjContratado'];
                                    $this->xml->endElement();#ans:cnpjContratado
                                    $this->xml->startElement('ans:nomeContratado');
                                        $this->xml->text($this->dados['nomeContratado']);
                                        $this->texto .= $this->dados['nomeContratado'];
                                    $this->xml->endElement();#ans:nomeContratado
                                $this->xml->endElement();#ans:contratadoExecutante
                                $this->xml->startElement('ans:CNES');
                                    $this->xml->text($this->dados['CNES']);
                                    $this->texto .= $this->dados['CNES'];
                                $this->xml->endElement();#ans:CNES
                            $this->xml->endElement();#ans:dadosExecutante
                            $this->xml->startElement('ans:dadosAtendimento');
                                $this->xml->startElement('ans:tipoAtendimento');
                                    $this->xml->text($this->dados['tipoAtendimento']);
                                    $this->texto .= $this->dados['tipoAtendimento'];
                                $this->xml->endElement();#ans:tipoAtendimento
                                $this->xml->startElement('ans:indicacaoAcidente');
                                    $this->xml->text($this->dados['indicacaoAcidente']);
                                    $this->texto .= $this->dados['indicacaoAcidente'];
                                $this->xml->endElement();#ans:indicacaoAcidente
                            $this->xml->endElement();#ans:dadosAtendimento


                            $this->xml->startElement('ans:procedimentosExecutados');
                                foreach ($this->dados['procedimentosExecutados'] as $key => $value) {
                                    $this->xml->startElement('ans:procedimentoExecutado');
                                        $this->xml->startElement('ans:sequencialItem');
                                            $this->xml->text($this->sequencialItem);
                                            $this->texto .= $this->dados['sequencialIt'];
                                        $this->xml->endElement();#ans:sequencialItem
                                        $this->xml->startElement('ans:dataExecucao');
                                            $this->xml->text($value['dataExecucao']);
                                            $this->texto .= $this->dados['dataExecucao'];
                                        $this->xml->endElement();#ans:dataExecucao
                                        $this->xml->startElement('ans:horaInicial');
                                            $this->xml->text($value['horaInicial']);
                                            $this->texto .= $this->dados['horaInicial'];
                                        $this->xml->endElement();#ans:horaInicial
                                        $this->xml->startElement('ans:horaFinal');
                                            $this->xml->text($value['horaFinal']);
                                            $this->texto .= $this->dados['horaFinal'];
                                        $this->xml->endElement();#ans:horaFinal
                                        $this->xml->startElement('ans:procedimento');
                                            $this->xml->startElement('ans:codigoTabela');
                                                $this->xml->text($value['codigoTabela']);
                                                $this->texto .= $this->dados['codigoTabela'];
                                            $this->xml->endElement();#ans:codigoTabela
                                            $this->xml->startElement('ans:codigoProcedimento');
                                                $this->xml->text($value['codigoProcedimento']);
                                                $this->texto .= $this->dados['codigoProcedimento'];
                                            $this->xml->endElement();#ans:codigoProcedimento
                                            $this->xml->startElement('ans:descricaoProcedimento');
                                                $this->xml->text($value['descricaoProcedimento']);
                                                $this->texto .= $this->dados['descricaoProcedimento'];
                                            $this->xml->endElement();#ans:descricaoProcedimento
                                        $this->xml->endElement();#ans:procedimento
                                        $this->xml->startElement('ans:quantidadeExecutada');
                                            $this->xml->text($value['quantidadeExecutada']);
                                            $this->texto .= $this->dados['quantidadeExecutada'];
                                        $this->xml->endElement();#ans:quantidadeExecutada
                                        $this->xml->startElement('ans:reducaoAcrescimo');
                                            $this->xml->text($value['reducaoAcrescimo']);
                                            $this->texto .= $this->dados['reducaoAcrescimo'];
                                        $this->xml->endElement();#ans:reducaoAcrescimo
                                        $this->xml->startElement('ans:valorUnitario');
                                            $this->xml->text($value['valorUnitario']);
                                            $this->texto .= $this->dados['valorUnitario'];
                                        $this->xml->endElement();#ans:valorUnitario
                                        $this->xml->startElement('ans:valorTotal');
                                            $this->xml->text($value['valorTotal']);
                                            $this->texto .= $this->dados['valorTotal'];
                                        $this->xml->endElement();#ans:valorTotal
                                    $this->xml->endElement();#ans:procedimentoExecutado
                                    $this->sequencialItem += 1;
                                }
                            $this->xml->endElement();#ans:procedimentosExecutados

                            $this->xml->startElement('ans:outrasDespesas');
                                foreach ($this->dados['outrasDespesas'] as $key => $value) {
                                    $this->xml->startElement('ans:despesa');
                                        $this->xml->startElement('ans:sequencialItem');
                                            $this->xml->text($this->sequencialItem);
                                            $this->texto .= $this->dados['sequencialIt'];
                                        $this->xml->endElement();#ans:sequencialItem
                                        $this->xml->startElement('ans:codigoDespesa');
                                            $this->xml->text($value['codigoDespesa']);
                                            $this->texto .= $this->dados['codigoDespesa'];
                                        $this->xml->endElement();#ans:codigoDespesa
                                        $this->xml->startElement('ans:servicosExecutados');
                                            $this->xml->startElement('ans:dataExecucao');
                                                $this->xml->text($value['dataExecucao']);
                                                $this->texto .= $this->dados['dataExecucao'];
                                            $this->xml->endElement();#ans:dataExecucao
                                            $this->xml->startElement('ans:horaInicial');
                                                $this->xml->text($value['horaInicial']);
                                                $this->texto .= $this->dados['horaInicial'];
                                            $this->xml->endElement();#ans:horaInicial
                                            $this->xml->startElement('ans:horaFinal');
                                                $this->xml->text($value['horaFinal']);
                                                $this->texto .= $this->dados['horaFinal'];
                                            $this->xml->endElement();#ans:horaFinal
                                            $this->xml->startElement('ans:codigoTabela');
                                                $this->xml->text($value['codigoTabela']);
                                                $this->texto .= $this->dados['codigoTabela'];
                                            $this->xml->endElement();#ans:codigoTabela
                                            $this->xml->startElement('ans:codigoProcedimento');
                                                $this->xml->text($value['codigoProcedimento']);
                                                $this->texto .= $this->dados['codigoProcedimento'];
                                            $this->xml->endElement();#ans:codigoProcedimento
                                            $this->xml->startElement('ans:quantidadeExecutada');
                                                $this->xml->text($value['quantidadeExecutada']);
                                                $this->texto .= $this->dados['quantidadeExecutada'];
                                            $this->xml->endElement();#ans:quantidadeExecutada
                                            $this->xml->startElement('ans:unidadeMedida');
                                                $this->xml->text($value['unidadeMedida']);
                                                $this->texto .= $this->dados['unidadeMedida'];
                                            $this->xml->endElement();#ans:unidadeMedida
                                            $this->xml->startElement('ans:reducaoAcrescimo');
                                                $this->xml->text($value['reducaoAcrescimo']);
                                                $this->texto .= $this->dados['reducaoAcrescimo'];
                                            $this->xml->endElement();#ans:reducaoAcrescimo
                                            $this->xml->startElement('ans:valorUnitario');
                                                $this->xml->text($value['valorUnitario']);
                                                $this->texto .= $this->dados['valorUnitario'];
                                            $this->xml->endElement();#ans:valorUnitario
                                            $this->xml->startElement('ans:valorTotal');
                                                $this->xml->text($value['valorTotal']);
                                                $this->texto .= $this->dados['valorTotal'];
                                            $this->xml->endElement();#ans:valorTotal
                                            $this->xml->startElement('ans:descricaoProcedimento');
                                                $this->xml->text($value['descricaoProcedimento']);
                                                $this->texto .= $this->dados['descricaoProcedimento'];
                                            $this->xml->endElement();#ans:descricaoProcedimento
                                        $this->xml->endElement();#ans:servicosExecutados
                                    $this->xml->endElement();#ans:despesa
                                    $this->sequencialItem += 1;
                                }
                            $this->xml->endElement();#ans:outrasDespesas>


                            $this->xml->startElement('ans:valorTotal');
                                $this->xml->startElement('ans:valorProcedimentos');
                                    $this->xml->text($this->dados['valorProcedimentos']);
                                    $this->texto .= $this->dados['valorProcedimentos'];
                                $this->xml->endElement();#ans:valorProcedimentos
                                $this->xml->startElement('ans:valorDiarias');
                                    $this->xml->text($this->dados['valorDiarias']);
                                    $this->texto .= $this->dados['valorDiarias'];
                                $this->xml->endElement();#ans:valorDiarias
                                $this->xml->startElement('ans:valorTaxasAlugueis');
                                    $this->xml->text($this->dados['valorTaxasAlugueis']);
                                    $this->texto .= $this->dados['valorTaxasAlugueis'];
                                $this->xml->endElement();#ans:valorTaxasAlugueis
                                $this->xml->startElement('ans:valorMateriais');
                                    $this->xml->text($this->dados['valorMateriais']);
                                    $this->texto .= $this->dados['valorMateriais'];
                                $this->xml->endElement();#ans:valorMateriais
                                $this->xml->startElement('ans:valorMedicamentos');
                                    $this->xml->text($this->dados['valorMedicamentos']);
                                    $this->texto .= $this->dados['valorMedicamentos'];
                                $this->xml->endElement();#ans:valorMedicamentos
                                $this->xml->startElement('ans:valorOPME');
                                    $this->xml->text($this->dados['valorOPME']);
                                    $this->texto .= $this->dados['valorOPME'];
                                $this->xml->endElement();#ans:valorOPME
                                $this->xml->startElement('ans:valorGasesMedicinais');
                                    $this->xml->text($this->dados['valorGasesMedicinais']);
                                    $this->texto .= $this->dados['valorGasesMedicinais'];
                                $this->xml->endElement();#ans:valorGasesMedicinais
                                $this->xml->startElement('ans:valorTotalGeral');
                                    $this->xml->text($this->dados['valorTotalGeral']);
                                    $this->texto .= $this->dados['valorTotalGeral'];
                                $this->xml->endElement();#ans:valorTotalGeral
                            $this->xml->endElement();#ans:valorTotal


                        $this->xml->endElement();#ans:guiaSP-SADT
                    $this->xml->endElement();#ans:guiasTISS
                $this->xml->endElement();#ans:loteGuias
            $this->xml->endElement();#ans:prestadorParaOperadora
            $this->xml->startElement('ans:epilogo');
                $this->xml->startElement('ans:hash');
                    $this->xml->text(hash('ripemd160', $this->texto));
                $this->xml->endElement();#ans:hash
            $this->xml->endElement();#ans:epilogo
        $this->xml->endElement();#ans:mensagemTISS
        $this->finalizarArquivo();

        // return null;
        return base64_encode($this->xml->outputMemory());
        // return $this->return;
        // return $this->dados;
    }
}
