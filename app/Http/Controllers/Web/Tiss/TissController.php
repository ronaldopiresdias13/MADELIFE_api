<?php

namespace App\Http\Controllers\Web\Tiss;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Medicao;
use App\Models\Profissional;
use App\Services\TissService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use XMLWriter;

class TissController extends Controller
{
    protected $xml = null;

    protected $dados = [];

    protected $sequencialItem = 1;

    protected $texto = '';

    public function gerarXml(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        $medicao = Medicao::with(
            'medicao_produtos.produto',
            'medicao_servicos.servico',
            'ordemservico.orcamento.homecare.paciente.pessoa'
        )->find($request->medicoes);
        $empresa = Empresa::find($empresa_id);

        if ($medicao->cliente->versaoTiss) {
            $func = 'gerar_xml_' . $medicao->cliente->versaoTiss;
            $tissService = new TissService($medicao, $empresa);
            $resposta = $tissService->$func();

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
    public function iniciarArquivo()
    {
        $this->xml = new XMLWriter();
        $this->xml->openMemory();
        $this->xml->startDocument('1.0', 'utf-8');
    }

    public function finalizarArquivo()
    {
        $this->xml->endDocument();
    }

    public function gerar_xml_3_02_00($medicoes, $cliente)
    {
        $now = Carbon::now();
        $dados = [];
        $dados['valorProcedimentos']   = 0;
        $dados['valorDiarias']         = 0;
        $dados['valorTaxasAlugueis']   = 0;
        $dados['valorMateriais']       = 0;
        $dados['valorMedicamentos']    = 0;
        $dados['valorOPME']            = 0;
        $dados['valorGasesMedicinais'] = 0;
        $this->iniciarArquivo();
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
        $this->xml->text($cliente->empresa->tiss_sequencialTransacao);
        $this->texto .= $cliente->empresa->tiss_sequencialTransacao;
        $this->xml->endElement(); #ans:sequencialTransacao
        $this->xml->startElement('ans:dataRegistroTransacao');
        $this->xml->text($now->format('Y-m-d'));
        $this->texto .= $now->format('Y-m-d');
        $this->xml->endElement(); #ans:dataRegistroTransacao
        $this->xml->startElement('ans:horaRegistroTransacao');
        $this->xml->text($now->format('H:i:s'));
        $this->texto .= $now->format('H:i:s');
        $this->xml->endElement(); #ans:horaRegistroTransacao
        $this->xml->endElement(); #ans:identificacaoTransacao
        $this->xml->startElement('ans:origem');
        $this->xml->startElement('ans:identificacaoPrestador');
        $this->xml->startElement('ans:CNPJ');
        $this->xml->text($cliente->empresa->cnpj);
        $this->texto .= $cliente->empresa->cnpj;
        $this->xml->endElement(); #ans:CNPJ');
        $this->xml->endElement(); #ans:identificacaoPrestador
        $this->xml->endElement(); #ans:origem
        $this->xml->startElement('ans:destino');
        $this->xml->startElement('ans:registroANS');
        $this->xml->text($cliente->registroAns);
        $this->texto .= $cliente->registroAns;
        $this->xml->endElement(); #ans:registroANS
        $this->xml->endElement(); #ans:destino
        $this->xml->startElement('ans:versaoPadrao');
        $this->xml->text(str_replace('_', '.', $cliente->versaoTiss));
        $this->texto .= str_replace('_', '.', $cliente->versaoTiss);
        $this->xml->endElement(); #ans:versaoPadrao
        $this->xml->endElement(); #ans:cabecalho
        $this->xml->startElement('ans:prestadorParaOperadora');
        $this->xml->startElement('ans:loteGuias');
        $this->xml->startElement('ans:numeroLote');
        $this->xml->text('0');
        $this->texto .= '0';
        $this->xml->endElement(); #ans:numeroLote');
        $this->xml->startElement('ans:guiasTISS');
        foreach ($medicoes as $key => $medicao) {
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
            $this->xml->text($cliente->empresa->cnpj);
            $this->texto .= $cliente->empresa->cnpj;
            $this->xml->endElement(); #ans:cnpjContratado
            $this->xml->startElement('ans:nomeContratado');
            $this->xml->text($cliente->empresa->razao);
            $this->texto .= $cliente->empresa->razao;
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
            $this->xml->text($cliente->empresa->cnpj);
            $this->texto .= $cliente->empresa->cnpj;
            $this->xml->endElement(); #ans:cnpjContratado
            $this->xml->startElement('ans:nomeContratado');
            $this->xml->text($cliente->empresa->razao);
            $this->texto .= $cliente->empresa->razao;
            $this->xml->endElement(); #ans:nomeContratado
            $this->xml->endElement(); #ans:contratadoExecutante
            $this->xml->startElement('ans:CNES');
            $this->xml->text($cliente->empresa->CNES);
            $this->texto .= $cliente->empresa->CNES;
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
                $dados['valorProcedimentos'] += $servico->subtotal;
            }
            $this->xml->endElement(); #ans:procedimentosExecutados

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
        $this->finalizarArquivo();

        return base64_encode($this->xml->outputMemory());
    }

    public function gerar_xml_3_05_00($medicoes, $cliente)
    {
        $now = Carbon::now();
        $dados = [];
        $dados['valorProcedimentos']   = 0;
        $dados['valorDiarias']         = 0;
        $dados['valorTaxasAlugueis']   = 0;
        $dados['valorMateriais']       = 0;
        $dados['valorMedicamentos']    = 0;
        $dados['valorOPME']            = 0;
        $dados['valorGasesMedicinais'] = 0;
        $this->iniciarArquivo();
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
        $this->xml->text($this->dados['sequencialTransacao']);
        $this->texto .= $this->dados['sequencialTransacao'];
        $this->xml->endElement(); #ans:sequencialTransacao
        $this->xml->startElement('ans:dataRegistroTransacao');
        $this->xml->text($this->dados['dataRegistroTransacao']);
        $this->texto .= $this->dados['dataRegistroTransacao'];
        $this->xml->endElement(); #ans:dataRegistroTransacao
        $this->xml->startElement('ans:horaRegistroTransacao');
        $this->xml->text($this->dados['horaRegistroTransacao']);
        $this->texto .= $this->dados['horaRegistroTransacao'];
        $this->xml->endElement(); #ans:horaRegistroTransacao
        $this->xml->endElement(); #ans:identificacaoTransacao
        $this->xml->startElement('ans:origem');
        $this->xml->startElement('ans:identificacaoPrestador');
        $this->xml->startElement('ans:CNPJ');
        $this->xml->text($this->dados['CNPJ']);
        $this->texto .= $this->dados['CNPJ'];
        $this->xml->endElement(); #ans:CNPJ
        $this->xml->endElement(); #ans:identificacaoPrestador
        $this->xml->endElement(); #ans:origem
        $this->xml->startElement('ans:destino');
        $this->xml->startElement('ans:registroANS');
        $this->xml->text($this->dados['registroANS']);
        $this->texto .= $this->dados['registroANS'];
        $this->xml->endElement(); #ans:registroANS
        $this->xml->endElement(); #ans:destino
        $this->xml->startElement('ans:Padrao');
        $this->xml->text($this->dados['versaoPadrao']);
        $this->texto .= $this->dados['versaoPadrao'];
        $this->xml->endElement(); #ans:Padrao
        $this->xml->endElement(); #ans:cabecalho

        $this->xml->startElement('ans:prestadorParaOperadora');
        $this->xml->startElement('ans:loteGuias');
        $this->xml->startElement('ans:numeroLote');
        $this->xml->text($this->dados['numeroLote']);
        $this->texto .= $this->dados['numeroLote'];
        $this->xml->endElement(); #ans:numeroLote
        $this->xml->startElement('ans:guiasTISS');
        $this->xml->startElement('ans:guiaSP-SADT');
        $this->xml->startElement('ans:cabecalhoGuia');
        $this->xml->startElement('ans:registroANS');
        $this->xml->text($this->dados['registroANS']);
        $this->texto .= $this->dados['registroANS'];
        $this->xml->endElement(); #ans:registroANS
        $this->xml->startElement('ans:numeroGuiaPrestador');
        $this->xml->text($this->dados['numeroGuiaPrestador']);
        $this->texto .= $this->dados['numeroGuiaPrestador'];
        $this->xml->endElement(); #ans:numeroGuiaPrestador
        $this->xml->endElement(); #ans:cabecalhoGuia
        $this->xml->startElement('ans:dadosBeneficiario');
        $this->xml->startElement('ans:numeroCarteira');
        $this->xml->text($this->dados['numeroCarteira']);
        $this->texto .= $this->dados['numeroCarteira'];
        $this->xml->endElement(); #ans:numeroCarteira
        $this->xml->startElement('ans:atendimentoRN');
        $this->xml->text($this->dados['atendimentoRN']);
        $this->texto .= $this->dados['atendimentoRN'];
        $this->xml->endElement(); #ans:atendimentoRN
        $this->xml->startElement('ans:nomeBeneficiario');
        $this->xml->text($this->dados['nomeBeneficiario']);
        $this->texto .= $this->dados['nomeBeneficiario'];
        $this->xml->endElement(); #ans:nomeBeneficiario
        $this->xml->endElement(); #ans:dadosBeneficiario
        $this->xml->startElement('ans:dadosSolicitante');
        $this->xml->startElement('ans:contratadoSolicitante');
        $this->xml->startElement('ans:cnpjContratado');
        $this->xml->text($this->dados['cnpjContratado']);
        $this->texto .= $this->dados['cnpjContratado'];
        $this->xml->endElement(); #ans:cnpjContratado
        $this->xml->startElement('ans:nomeContratado');
        $this->xml->text($this->dados['nomeContratado']);
        $this->texto .= $this->dados['nomeContratado'];
        $this->xml->endElement(); #ans:nomeContratado
        $this->xml->endElement(); #ans:contratadoSolicitante
        $this->xml->startElement('ans:profissionalSolicitante');
        $this->xml->startElement('ans:nomeProfissional');
        $this->xml->text($this->dados['nomeProfissional']);
        $this->texto .= $this->dados['nomeProfissional'];
        $this->xml->endElement(); #ans:nomeProfissional
        $this->xml->startElement('ans:conselhoProfissional');
        $this->xml->text($this->dados['conselhoProfissional']);
        $this->texto .= $this->dados['conselhoProfissional'];
        $this->xml->endElement(); #ans:conselhoProfissional
        $this->xml->startElement('ans:numeroConselhoProfissional');
        $this->xml->text($this->dados['numeroConselhoProfissional']);
        $this->texto .= $this->dados['numeroConselhoProfissional'];
        $this->xml->endElement(); #ans:numeroConselhoProfissional
        $this->xml->startElement('ans:UF');
        $this->xml->text($this->dados['UF']);
        $this->texto .= $this->dados['UF'];
        $this->xml->endElement(); #ans:UF
        $this->xml->startElement('ans:CBOS');
        $this->xml->text($this->dados['CBOS']);
        $this->texto .= $this->dados['CBOS'];
        $this->xml->endElement(); #ans:CBOS
        $this->xml->endElement(); #ans:profissionalSolicitante
        $this->xml->endElement(); #ans:dadosSolicitante
        $this->xml->startElement('ans:dadosSolicitacao');
        $this->xml->startElement('ans:dataSolicitacao');
        $this->xml->text($this->dados['dataSolicitacao']);
        $this->texto .= $this->dados['dataSolicitacao'];
        $this->xml->endElement(); #ans:dataSolicitacao
        $this->xml->startElement('ans:caraterAtendimento');
        $this->xml->text($this->dados['caraterAtendimento']);
        $this->texto .= $this->dados['caraterAtendimento'];
        $this->xml->endElement(); #ans:caraterAtendimento
        $this->xml->startElement('ans:indicacaoClinica');
        $this->xml->text($this->dados['indicacaoClinica']);
        $this->texto .= $this->dados['indicacaoClinica'];
        $this->xml->endElement(); #ans:indicacaoClinica
        $this->xml->endElement(); #ans:dadosSolicitacao
        $this->xml->startElement('ans:dadosExecutante');
        $this->xml->startElement('ans:contratadoExecutante');
        $this->xml->startElement('ans:cnpjContratado');
        $this->xml->text($this->dados['cnpjContratado']);
        $this->texto .= $this->dados['cnpjContratado'];
        $this->xml->endElement(); #ans:cnpjContratado
        $this->xml->startElement('ans:nomeContratado');
        $this->xml->text($this->dados['nomeContratado']);
        $this->texto .= $this->dados['nomeContratado'];
        $this->xml->endElement(); #ans:nomeContratado
        $this->xml->endElement(); #ans:contratadoExecutante
        $this->xml->startElement('ans:CNES');
        $this->xml->text($this->dados['CNES']);
        $this->texto .= $this->dados['CNES'];
        $this->xml->endElement(); #ans:CNES
        $this->xml->endElement(); #ans:dadosExecutante
        $this->xml->startElement('ans:dadosAtendimento');
        $this->xml->startElement('ans:tipoAtendimento');
        $this->xml->text($this->dados['tipoAtendimento']);
        $this->texto .= $this->dados['tipoAtendimento'];
        $this->xml->endElement(); #ans:tipoAtendimento
        $this->xml->startElement('ans:indicacaoAcidente');
        $this->xml->text($this->dados['indicacaoAcidente']);
        $this->texto .= $this->dados['indicacaoAcidente'];
        $this->xml->endElement(); #ans:indicacaoAcidente
        $this->xml->endElement(); #ans:dadosAtendimento


        $this->xml->startElement('ans:procedimentosExecutados');
        foreach ($medicoes->medicao_servicos as $key => $servico) {
            $this->xml->startElement('ans:procedimentoExecutado');
            $this->xml->startElement('ans:sequencialItem');
            $this->xml->text($this->sequencialItem);
            $this->texto .= $this->dados['sequencialIt'];
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
        }
        $this->xml->endElement(); #ans:procedimentosExecutados

        $this->xml->startElement('ans:outrasDespesas');
        foreach ($medicoes->medicao_produtos as $key => $produto) {
            $this->xml->startElement('ans:despesa');
            $this->xml->startElement('ans:sequencialItem');
            $this->xml->text($this->sequencialItem);
            $this->texto .= $this->dados['sequencialIt'];
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
        }
        $this->xml->endElement(); #ans:outrasDespesas>


        $this->xml->startElement('ans:valorTotal');
        $this->xml->startElement('ans:valorProcedimentos');
        $this->xml->text($this->dados['valorProcedimentos']);
        $this->texto .= $this->dados['valorProcedimentos'];
        $this->xml->endElement(); #ans:valorProcedimentos
        $this->xml->startElement('ans:valorDiarias');
        $this->xml->text($this->dados['valorDiarias']);
        $this->texto .= $this->dados['valorDiarias'];
        $this->xml->endElement(); #ans:valorDiarias
        $this->xml->startElement('ans:valorTaxasAlugueis');
        $this->xml->text($this->dados['valorTaxasAlugueis']);
        $this->texto .= $this->dados['valorTaxasAlugueis'];
        $this->xml->endElement(); #ans:valorTaxasAlugueis
        $this->xml->startElement('ans:valorMateriais');
        $this->xml->text($this->dados['valorMateriais']);
        $this->texto .= $this->dados['valorMateriais'];
        $this->xml->endElement(); #ans:valorMateriais
        $this->xml->startElement('ans:valorMedicamentos');
        $this->xml->text($this->dados['valorMedicamentos']);
        $this->texto .= $this->dados['valorMedicamentos'];
        $this->xml->endElement(); #ans:valorMedicamentos
        $this->xml->startElement('ans:valorOPME');
        $this->xml->text($this->dados['valorOPME']);
        $this->texto .= $this->dados['valorOPME'];
        $this->xml->endElement(); #ans:valorOPME
        $this->xml->startElement('ans:valorGasesMedicinais');
        $this->xml->text($this->dados['valorGasesMedicinais']);
        $this->texto .= $this->dados['valorGasesMedicinais'];
        $this->xml->endElement(); #ans:valorGasesMedicinais
        $this->xml->startElement('ans:valorTotalGeral');
        $this->xml->text($this->dados['valorTotalGeral']);
        $this->texto .= $this->dados['valorTotalGeral'];
        $this->xml->endElement(); #ans:valorTotalGeral
        $this->xml->endElement(); #ans:valorTotal


        $this->xml->endElement(); #ans:guiaSP-SADT
        $this->xml->endElement(); #ans:guiasTISS
        $this->xml->endElement(); #ans:loteGuias
        $this->xml->endElement(); #ans:prestadorParaOperadora
        $this->xml->startElement('ans:epilogo');
        $this->xml->startElement('ans:hash');
        $this->xml->text(hash('ripemd160', $this->texto));
        $this->xml->endElement(); #ans:hash
        $this->xml->endElement(); #ans:epilogo
        $this->xml->endElement(); #ans:mensagemTISS
        $this->finalizarArquivo();

        return base64_encode($this->xml->outputMemory());
    }
}
