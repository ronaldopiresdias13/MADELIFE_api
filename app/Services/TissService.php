<?php

namespace App\Services;

use XMLWriter;

class TissService
{
    protected $xml = null;

    protected $dados = [];

    protected $sequencialItem = 1;

    public function __construct($versao, $request)
    {


        $this->dados['tipoTransacao']              = $request->tipoTransacao;
        $this->dados['sequencialTransacao']        = 1;#Pegar sequencia salva no cadastro da empresa
        $this->dados['dataRegistroTransacao']      = $request->dataRegistroTransacao;#Verificar se essa data deve ser a data de conclusão do lote ou a data da geração do xml
        $this->dados['horaRegistroTransacao']      = $request->horaRegistroTransacao;#Verificar se essa hora deve ser a hora de conclusão do lote ou a hora da geração do xml
        $this->dados['CNPJ']                       = '12316361000120';#Pegar do usuário logado ou da medição
        $this->dados['registroANS']                = '41.858';#Pegar do cadastro do Cliente/Convênio
        $this->dados['Padrao']                     = $versao;
        $this->dados['numeroLote']                 = '0';
        $this->dados['numeroGuiaPrestador']        = '202104';
        $this->dados['numeroCarteira']             = '8355.51.02398.01-8';#Pegar essa informação do cadastro do paciente
        $this->dados['atendimentoRN']              = 'N';#Recem Nascido, Pegar do cadastro do paciente
        $this->dados['nomeBeneficiario']           = 'ANTONIO GOMES DOS SANTOS';#Pegar do cadastro do paciente
        $this->dados['cnpjContratado']             = '12316361000120';#Pegar do cadastro da empresa
        $this->dados['nomeContratado']             = 'HOME CARE ENFERLIFE HOSPITALAR LTDA';#Pegar do cadastro da empresa
        $this->dados['nomeProfissional']           = 'EDILSON MAGAVER BRAZ TEIXEIRA';#Pegar do cadastro do profissional, passar id do profissional solicitante
        $this->dados['conselhoProfissional']       = '02';#Pegar do cadastro do profissional, passar id do profissional solicitante
        $this->dados['numeroConselhoProfissional'] = '000250197';#Pegar do cadastro do profissional, passar id do profissional solicitante
        $this->dados['UF']                         = '35';#Pegar do cadastro do profissional, passar id do profissional solicitante
        $this->dados['CBOS']                       = '223505';#Pegar do cadastro do profissional, passar id do profissional solicitante
        $this->dados['CNES']                       = '9652191';#Pegar do cadastro da empresa
        $this->dados['dataSolicitacao']            = '2021-05-10';#Pegar data da criação da medição // acho que a data dever ser antes da data de execução
        $this->dados['caraterAtendimento']         = '1';#Eletiva ou Urgencia/Emergencia#Pegar do orçamento
        $this->dados['indicacaoClinica']           = 'EQUIPE MULTIDISCIPLINAR';#Digitado a mão#Pegar do Orçamento
        $this->dados['tipoAtendimento']            = '06';#Lista no teams#Pegar do Orçamento
        $this->dados['indicacaoAcidente']          = '9';#Lista no Teams#Pegar do Orçamento


        $this->dados['procedimentosExecutados']    = [
            [
                'dataExecucao'          => '2021-05-10',
                'horaInicial'           => '10:00:00',
                'horaFinal'             => '11:00:00',
                'codigoTabela'          => '22',#Pegar do cadastro de serviço
                'codigoProcedimento'    => '50000241',#Pegar do cadastro de serviço
                'descricaoProcedimento' => 'CONSULTA DOMICILIAR EM FISIOTERAPIA',#Pegar do cadastro de serviço
                'quantidadeExecutada'   => '8',#Pegar da medição
                'reducaoAcrescimo'      => '1.00',#Pegar da medição
                'valorUnitario'         => '70.00',#Pegar da medição
                'valorTotal'            => '560.00'#Pegar da medição
            ]
        ];#Pegar dados da escala

        $this->dados['outrasDespesas']    = [
            [
                'codigoDespesa'         => '01',#Pegar do cadastro de produtos
                'dataExecucao'          => '2021-05-10',
                'horaInicial'           => '10:00:00',
                'horaFinal'             => '11:00:00',
                'codigoTabela'          => '22',#Pegar do cadastro de produtos
                'codigoProcedimento'    => '90002440',#Pegar do cadastro de produtos // Creio que é o TUSS
                'quantidadeExecutada'   => '31.00',#Pegar da medição
                'unidadeMedida'         => '036',#Pegar do cadastro de produto
                'reducaoAcrescimo'      => '1.00',#Pegar da medição
                'valorUnitario'         => '0.83',#Pegar da medição
                'valorTotal'            => '25.73',#Pegar da medição
                'descricaoProcedimento' => 'SORO FISIOLOGICO 10ML'#Pegar do cadastro de serviço
            ]
        ];#Pegar dados da medição-produtos
    }

    function iniciarArquivo()
    {
        // echo $oXMLWriter->outputMemory ();


        $this->xml = new XMLWriter();
        $this->xml->openMemory();
        // $this->xml->openUri('file:///home/lucas/Área de Trabalho/zzzz/Laravel/Exemplo TISS TUSS/XMLs/output.xml');
        $this->xml->startDocument('1.0', 'utf-8');
    }

    function finalizarArquivo() {
        $this->xml->endDocument();
    }

    public function gerar_xml_3_02_00()
    {
        $this->iniciarArquivo();
        $this->xml->startElement('ans:mensagemTISS');
            $this->xml->writeAttribute('xmlns:ans="http://www.ans.gov.br/padroes/tiss/schemas"');
            $this->xml->writeAttribute('xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"');
            $this->xml->writeAttribute('xsi:schemaLocation="http://www.ans.gov.br/padroes/tiss/schemas tissV3_02_00.xsd"');
            $this->xml->startElement('ans:cabecalho');
                $this->xml->startElement('ans:identificacaoTransacao');
                    $this->xml->startElement('ans:tipoTransacao');
                        $this->xml->text($this->dados['tipoTransacao']);
                    $this->xml->endElement();#ans:tipoTransacao
                    $this->xml->startElement('ans:sequencialTransacao');
                        $this->xml->text($this->dados['sequencialTransacao']);
                    $this->xml->endElement();#ans:sequencialTransacao
                    $this->xml->startElement('ans:dataRegistroTransacao');
                        $this->xml->text($this->dados['dataRegistroTransacao']);
                    $this->xml->endElement();#ans:dataRegistroTransacao
                    $this->xml->startElement('ans:horaRegistroTransacao');
                        $this->xml->text($this->dados['horaRegistroTransacao']);
                    $this->xml->endElement();#ans:horaRegistroTransacao
                $this->xml->endElement();#ans:identificacaoTransacao>
                $this->xml->startElement('ans:origem');
                    $this->xml->startElement('ans:identificacaoPrestador');
                        $this->xml->startElement('ans:CNPJ');
                            $this->xml->text($this->dados['CNPJ']);
                        $this->xml->endElement();#ans:CNPJ');
                    $this->xml->endElement();#ans:identificacaoPrestador
                $this->xml->endElement();#ans:origem
                $this->xml->startElement('ans:destino');
                    $this->xml->startElement('ans:registroANS');
                        $this->xml->text($this->dados['registroANS']);
                    $this->xml->endElement();#ans:registroANS
                $this->xml->endElement();#ans:destino
                $this->xml->startElement('ans:versaoPadrao');
                    $this->xml->text($this->dados['Padrao']);
                $this->xml->endElement();#ans:versaoPadrao
            $this->xml->endElement();#ans:cabecalho
            $this->xml->startElement('ans:prestadorParaOperadora');
                $this->xml->startElement('ans:loteGuias');
                    $this->xml->startElement('ans:numeroLote');
                        $this->xml->text('69');
                    $this->xml->endElement();#ans:numeroLote');
                    $this->xml->startElement('ans:guiasTISS');
                        $this->xml->startElement('ans:guiaSP-SADT'); // Foreach
                            $this->xml->startElement('ans:cabecalhoGuia');
                                $this->xml->startElement('ans:registroANS');
                                    $this->xml->text($this->dados['registroANS']);
                                $this->xml->endElement();#ans:registroANS
                                $this->xml->startElement('ans:numeroGuiaPrestador');
                                    $this->xml->text($this->dados['numeroGuiaPrestador']);
                                $this->xml->endElement();#ans:numeroGuiaPrestador
                            $this->xml->endElement();#ans:cabecalhoGuia
                            $this->xml->startElement('ans:dadosBeneficiario');
                                $this->xml->startElement('ans:numeroCarteira');
                                    $this->xml->text($this->dados['numeroCarteira']);
                                $this->xml->endElement();#ans:numeroCarteira
                                $this->xml->startElement('ans:atendimentoRN');
                                    $this->xml->text($this->dados['atendimentoRN']);
                                $this->xml->endElement();#ans:atendimentoRN
                                $this->xml->startElement('ans:nomeBeneficiario');
                                    $this->xml->text($this->dados['nomeBeneficiario']);
                                $this->xml->endElement();#ans:nomeBeneficiario
                            $this->xml->endElement();#ans:dadosBeneficiario
                            $this->xml->startElement('ans:dadosSolicitante');
                                $this->xml->startElement('ans:contratadoSolicitante');
                                    $this->xml->startElement('ans:cnpjContratado');
                                        $this->xml->text($this->dados['cnpjContratado']);
                                    $this->xml->endElement();#ans:cnpjContratado
                                    $this->xml->startElement('ans:nomeContratado');
                                        $this->xml->text($this->dados['nomeContratado']);
                                    $this->xml->endElement();#ans:nomeContratado
                                $this->xml->endElement();#ans:contratadoSolicitante
                                $this->xml->startElement('ans:profissionalSolicitante');
                                    $this->xml->startElement('ans:nomeProfissional');
                                        $this->xml->text($this->dados['nomeProfissional']);
                                    $this->xml->endElement();#ans:nomeProfissional
                                    $this->xml->startElement('ans:conselhoProfissional');
                                        $this->xml->text($this->dados['conselhoProfissional']);
                                    $this->xml->endElement();#ans:conselhoProfissional
                                    $this->xml->startElement('ans:numeroConselhoProfissional');
                                        $this->xml->text($this->dados['numeroConselhoProfissional']);
                                    $this->xml->endElement();#ans:numeroConselhoProfissional
                                    $this->xml->startElement('ans:UF');
                                        $this->xml->text($this->dados['UF']);
                                    $this->xml->endElement();#ans:UF
                                    $this->xml->startElement('ans:CBOS');
                                        $this->xml->text($this->dados['CBOS']);
                                    $this->xml->endElement();#ans:CBOS
                                $this->xml->endElement();#ans:profissionalSolicitante
                            $this->xml->endElement();#ans:dadosSolicitante
                            $this->xml->startElement('ans:dadosSolicitacao');
                                $this->xml->startElement('ans:dataSolicitacao');
                                    $this->xml->text($this->dados['dataSolicitacao']);
                                $this->xml->endElement();#ans:dataSolicitacao
                                $this->xml->startElement('ans:caraterAtendimento');
                                    $this->xml->text('1');
                                $this->xml->endElement();#ans:caraterAtendimento
                                $this->xml->startElement('ans:indicacaoClinica');
                                    $this->xml->text('INTERNACAO DOMICILIAR 24H');
                                $this->xml->endElement();#ans:indicacaoClinica
                            $this->xml->endElement();#ans:dadosSolicitacao
                            $this->xml->startElement('ans:dadosExecutante');
                                $this->xml->startElement('ans:contratadoExecutante');
                                    $this->xml->startElement('ans:cnpjContratado');
                                        $this->xml->text('2316361000120');
                                    $this->xml->endElement();#ans:cnpjContratado
                                    $this->xml->startElement('ans:nomeContratado');
                                        $this->xml->text('HOME CARE ENFERLIFE HOSPITALAR LTDA');
                                    $this->xml->endElement();#ans:nomeContratado
                                $this->xml->endElement();#ans:contratadoExecutante
                                $this->xml->startElement('ans:CNES');
                                    $this->xml->text('9652191');
                                $this->xml->endElement();#ans:CNES
                            $this->xml->endElement();#ans:dadosExecutante
                            $this->xml->startElement('ans:dadosAtendimento');
                                $this->xml->startElement('ans:tipoAtendimento');
                                    $this->xml->text('06');
                                $this->xml->endElement();#ans:tipoAtendimento
                                $this->xml->startElement('ans:indicacaoAcidente');
                                    $this->xml->text('9');
                                $this->xml->endElement();#ans:indicacaoAcidente
                            $this->xml->endElement();#ans:dadosAtendimento
                            $this->xml->startElement('ans:procedimentosExecutados'); // Foreach Serviços
                                $this->xml->startElement('ans:procedimentoExecutado');
                                    $this->xml->startElement('ans:dataExecucao');
                                        $this->xml->text('2021-06-03');
                                    $this->xml->endElement();#ans:dataExecucao
                                    $this->xml->startElement('ans:horaInicial');
                                        $this->xml->text('10:00:00');
                                    $this->xml->endElement();#ans:horaInicial
                                    $this->xml->startElement('ans:horaFinal');
                                        $this->xml->text('11:00:00');
                                    $this->xml->endElement();#ans:horaFinal
                                    $this->xml->startElement('ans:procedimento');
                                        $this->xml->startElement('ans:codigoTabela');
                                            $this->xml->text('22');
                                        $this->xml->endElement();#ans:codigoTabela
                                        $this->xml->startElement('ans:codigoProcedimento');
                                            $this->xml->text('60034475');
                                        $this->xml->endElement();#ans:codigoProcedimento
                                        $this->xml->startElement('ans:descricaoProcedimento');
                                            $this->xml->text('TAXA DE AUXILIAR/TECNICO DE ENFERMAGEM NO DOMICILIO ATE 24 HORAS');
                                        $this->xml->endElement();#ans:descricaoProcedimento
                                    $this->xml->endElement();#ans:procedimento
                                    $this->xml->startElement('ans:quantidadeExecutada');
                                        $this->xml->text('31');
                                    $this->xml->endElement();#ans:quantidadeExecutada
                                    $this->xml->startElement('ans:reducaoAcrescimo');
                                        $this->xml->text('1.00');
                                    $this->xml->endElement();#ans:reducaoAcrescimo
                                    $this->xml->startElement('ans:valorUnitario');
                                        $this->xml->text('415.39');
                                    $this->xml->endElement();#ans:valorUnitario
                                    $this->xml->startElement('ans:valorTotal');
                                        $this->xml->text('12877.09');
                                    $this->xml->endElement();#ans:valorTotal
                                $this->xml->endElement();#ans:procedimentoExecutado
                            $this->xml->endElement();#ans:procedimentosExecutados
                            $this->xml->startElement('ans:outrasDespesas'); // Foreach Produtos
                                $this->xml->startElement('ans:despesa');
                                $this->xml->startElement('ans:codigoDespesa');
                                    $this->xml->text('02');
                                $this->xml->endElement();#ans:codigoDespesa
                                $this->xml->startElement('ans:servicosExecutados');
                                    $this->xml->startElement('ans:dataExecucao');
                                        $this->xml->text('2021-06-03');
                                    $this->xml->endElement();#ans:dataExecucao
                                    $this->xml->startElement('ans:horaInicial');
                                        $this->xml->text('10:00:00');
                                    $this->xml->endElement();#ans:horaInicial
                                    $this->xml->startElement('ans:horaFinal');
                                        $this->xml->text('11:00:00');
                                    $this->xml->endElement();#ans:horaFinal
                                    $this->xml->startElement('ans:codigoTabela');
                                        $this->xml->text('19');
                                    $this->xml->endElement();#ans:codigoTabela
                                    $this->xml->startElement('ans:codigoProcedimento');
                                        $this->xml->text('70959870');
                                    $this->xml->endElement();#ans:codigoProcedimento
                                    $this->xml->startElement('ans:quantidadeExecutada');
                                        $this->xml->text('280.00');
                                    $this->xml->endElement();#ans:quantidadeExecutada
                                    $this->xml->startElement('ans:unidadeMedida');
                                        $this->xml->text('036');
                                    $this->xml->endElement();#ans:unidadeMedida
                                    $this->xml->startElement('ans:reducaoAcrescimo');
                                        $this->xml->text('1.00');
                                    $this->xml->endElement();#ans:reducaoAcrescimo
                                    $this->xml->startElement('ans:valorUnitario');
                                        $this->xml->text('3.60');
                                    $this->xml->endElement();#ans:valorUnitario
                                    $this->xml->startElement('ans:valorTotal');
                                        $this->xml->text('1008.00');
                                    $this->xml->endElement();#ans:valorTotal
                                    $this->xml->startElement('ans:descricaoProcedimento');
                                        $this->xml->text('FRALDA G');
                                    $this->xml->endElement();#ans:descricaoProcedimento
                                $this->xml->endElement();#ans:servicosExecutados
                                $this->xml->endElement();#ans:despesa
                            $this->xml->endElement();#ans:outrasDespesas
                            $this->xml->startElement('ans:valorTotal');
                                $this->xml->startElement('ans:valorProcedimentos');
                                    $this->xml->text('13547.09');
                                $this->xml->endElement();#ans:valorProcedimentos
                                $this->xml->startElement('ans:valorDiarias');
                                    $this->xml->text('0.00');
                                $this->xml->endElement();#ans:valorDiarias
                                $this->xml->startElement('ans:valorTaxasAlugueis');
                                    $this->xml->text('0');
                                $this->xml->endElement();#ans:valorTaxasAlugueis
                                $this->xml->startElement('ans:valorMateriais');
                                    $this->xml->text('890.18');
                                $this->xml->endElement();#ans:valorMateriais
                                $this->xml->startElement('ans:valorMedicamentos');
                                    $this->xml->text('6786.47');
                                $this->xml->endElement();#ans:valorMedicamentos
                                $this->xml->startElement('ans:valorOPME');
                                    $this->xml->text('0');
                                $this->xml->endElement();#ans:valorOPME
                                $this->xml->startElement('ans:valorGasesMedicinais');
                                    $this->xml->text('986.24');
                                $this->xml->endElement();#ans:valorGasesMedicinais
                                $this->xml->startElement('ans:valorTotalGeral');
                                    $this->xml->text('22209.98');
                                $this->xml->endElement();#ans:valorTotalGeral
                            $this->xml->endElement();#ans:valorTotal>
                        $this->xml->endElement();#ans:guiaSP-SADT>
                    $this->xml->endElement();#ans:guiasTISS>
                $this->xml->endElement();#ans:loteGuias>
            $this->xml->endElement();#ans:prestadorParaOperadora>
            $this->xml->startElement('ans:epilogo');
                $this->xml->startElement('ans:hash');
                    $this->xml->text('c8e269adb42f80a8feb0afafde6b2fe4');
                $this->xml->endElement();#ans:hash');
            $this->xml->endElement();#ans:epilogo>
        $this->xml->endElement();#ans:mensagemTISS>
        $this->finalizarArquivo();

        return base64_encode($this->xml->outputMemory());
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
                    $this->xml->endElement();#ans:tipoTransacao
                    $this->xml->startElement('ans:sequencialTransacao');
                        $this->xml->text($this->dados['sequencialTransacao']);
                    $this->xml->endElement();#ans:sequencialTransacao
                    $this->xml->startElement('ans:dataRegistroTransacao');
                        $this->xml->text($this->dados['dataRegistroTransacao']);
                    $this->xml->endElement();#ans:dataRegistroTransacao
                    $this->xml->startElement('ans:horaRegistroTransacao');
                        $this->xml->text($this->dados['horaRegistroTransacao']);
                    $this->xml->endElement();#ans:horaRegistroTransacao
                $this->xml->endElement();#ans:identificacaoTransacao
                $this->xml->startElement('ans:origem');
                    $this->xml->startElement('ans:identificacaoPrestador');
                        $this->xml->startElement('ans:CNPJ');
                            $this->xml->text($this->dados['CNPJ']);
                        $this->xml->endElement();#ans:CNPJ
                    $this->xml->endElement();#ans:identificacaoPrestador
                $this->xml->endElement();#ans:origem
                $this->xml->startElement('ans:destino');
                    $this->xml->startElement('ans:registroANS');
                        $this->xml->text($this->dados['registroANS']);
                    $this->xml->endElement();#ans:registroANS
                $this->xml->endElement();#ans:destino
                $this->xml->startElement('ans:Padrao');
                    $this->xml->text($this->dados['Padrao']);
                $this->xml->endElement();#ans:Padrao
            $this->xml->endElement();#ans:cabecalho

            $this->xml->startElement('ans:prestadorParaOperadora');
                $this->xml->startElement('ans:loteGuias');
                    $this->xml->startElement('ans:numeroLote');
                        $this->xml->text($this->dados['numeroLote']);
                    $this->xml->endElement();#ans:numeroLote
                    $this->xml->startElement('ans:guiasTISS');
                        $this->xml->startElement('ans:guiaSP-SADT');
                            $this->xml->startElement('ans:cabecalhoGuia');
                                $this->xml->startElement('ans:registroANS');
                                    $this->xml->text($this->dados['registroANS']);
                                $this->xml->endElement();#ans:registroANS
                                $this->xml->startElement('ans:numeroGuiaPrestador');
                                    $this->xml->text($this->dados['numeroGuiaPrestador']);
                                $this->xml->endElement();#ans:numeroGuiaPrestador
                            $this->xml->endElement();#ans:cabecalhoGuia
                            $this->xml->startElement('ans:dadosBeneficiario');
                                $this->xml->startElement('ans:numeroCarteira');
                                    $this->xml->text($this->dados['numeroCarteira']);
                                $this->xml->endElement();#ans:numeroCarteira
                                $this->xml->startElement('ans:atendimentoRN');
                                    $this->xml->text($this->dados['atendimentoRN']);
                                $this->xml->endElement();#ans:atendimentoRN
                                $this->xml->startElement('ans:nomeBeneficiario');
                                    $this->xml->text($this->dados['nomeBeneficiario']);
                                $this->xml->endElement();#ans:nomeBeneficiario
                            $this->xml->endElement();#ans:dadosBeneficiario
                            $this->xml->startElement('ans:dadosSolicitante');
                                $this->xml->startElement('ans:contratadoSolicitante');
                                    $this->xml->startElement('ans:cnpjContratado');
                                        $this->xml->text($this->dados['cnpjContratado']);
                                    $this->xml->endElement();#ans:cnpjContratado
                                    $this->xml->startElement('ans:nomeContratado');
                                        $this->xml->text($this->dados['nomeContratado']);
                                    $this->xml->endElement();#ans:nomeContratado
                                $this->xml->endElement();#ans:contratadoSolicitante
                                $this->xml->startElement('ans:profissionalSolicitante');
                                    $this->xml->startElement('ans:nomeProfissional');
                                        $this->xml->text($this->dados['nomeProfissional']);
                                    $this->xml->endElement();#ans:nomeProfissional
                                    $this->xml->startElement('ans:conselhoProfissional');
                                        $this->xml->text($this->dados['conselhoProfissional']);
                                    $this->xml->endElement();#ans:conselhoProfissional
                                    $this->xml->startElement('ans:numeroConselhoProfissional');
                                        $this->xml->text($this->dados['numeroConselhoProfissional']);
                                    $this->xml->endElement();#ans:numeroConselhoProfissional
                                    $this->xml->startElement('ans:UF');
                                        $this->xml->text($this->dados['UF']);
                                    $this->xml->endElement();#ans:UF
                                    $this->xml->startElement('ans:CBOS');
                                        $this->xml->text($this->dados['CBOS']);
                                    $this->xml->endElement();#ans:CBOS
                                $this->xml->endElement();#ans:profissionalSolicitante
                            $this->xml->endElement();#ans:dadosSolicitante
                            $this->xml->startElement('ans:dadosSolicitacao');
                                $this->xml->startElement('ans:dataSolicitacao');
                                    $this->xml->text($this->dados['dataSolicitacao']);
                                $this->xml->endElement();#ans:dataSolicitacao
                                $this->xml->startElement('ans:caraterAtendimento');
                                    $this->xml->text($this->dados['caraterAtendimento']);
                                $this->xml->endElement();#ans:caraterAtendimento
                                $this->xml->startElement('ans:indicacaoClinica');
                                    $this->xml->text($this->dados['indicacaoClinica']);
                                $this->xml->endElement();#ans:indicacaoClinica
                            $this->xml->endElement();#ans:dadosSolicitacao
                            $this->xml->startElement('ans:dadosExecutante');
                                $this->xml->startElement('ans:contratadoExecutante');
                                    $this->xml->startElement('ans:cnpjContratado');
                                        $this->xml->text($this->dados['cnpjContratado']);
                                    $this->xml->endElement();#ans:cnpjContratado
                                    $this->xml->startElement('ans:nomeContratado');
                                        $this->xml->text($this->dados['nomeContratado']);
                                    $this->xml->endElement();#ans:nomeContratado
                                $this->xml->endElement();#ans:contratadoExecutante
                                $this->xml->startElement('ans:CNES');
                                    $this->xml->text($this->dados['CNES']);
                                $this->xml->endElement();#ans:CNES
                            $this->xml->endElement();#ans:dadosExecutante
                            $this->xml->startElement('ans:dadosAtendimento');
                                $this->xml->startElement('ans:tipoAtendimento');
                                    $this->xml->text($this->dados['tipoAtendimento']);
                                $this->xml->endElement();#ans:tipoAtendimento
                                $this->xml->startElement('ans:indicacaoAcidente');
                                    $this->xml->text($this->dados['indicacaoAcidente']);
                                $this->xml->endElement();#ans:indicacaoAcidente
                            $this->xml->endElement();#ans:dadosAtendimento


                            $this->xml->startElement('ans:procedimentosExecutados');
                                foreach ($this->dados['procedimentosExecutados'] as $key => $value) {
                                    $this->xml->startElement('ans:procedimentoExecutado');
                                        $this->xml->startElement('ans:sequencialItem');
                                            $this->xml->text($this->sequencialItem);
                                        $this->xml->endElement();#ans:sequencialItem
                                        $this->xml->startElement('ans:dataExecucao');
                                            $this->xml->text($value['dataExecucao']);
                                        $this->xml->endElement();#ans:dataExecucao
                                        $this->xml->startElement('ans:horaInicial');
                                            $this->xml->text($value['horaInicial']);
                                        $this->xml->endElement();#ans:horaInicial
                                        $this->xml->startElement('ans:horaFinal');
                                            $this->xml->text($value['horaFinal']);
                                        $this->xml->endElement();#ans:horaFinal
                                        $this->xml->startElement('ans:procedimento');
                                            $this->xml->startElement('ans:codigoTabela');
                                                $this->xml->text($value['codigoTabela']);
                                            $this->xml->endElement();#ans:codigoTabela
                                            $this->xml->startElement('ans:codigoProcedimento');
                                                $this->xml->text($value['codigoProcedimento']);
                                            $this->xml->endElement();#ans:codigoProcedimento
                                            $this->xml->startElement('ans:descricaoProcedimento');
                                                $this->xml->text($value['descricaoProcedimento']);
                                            $this->xml->endElement();#ans:descricaoProcedimento
                                        $this->xml->endElement();#ans:procedimento
                                        $this->xml->startElement('ans:quantidadeExecutada');
                                            $this->xml->text($value['quantidadeExecutada']);
                                        $this->xml->endElement();#ans:quantidadeExecutada
                                        $this->xml->startElement('ans:reducaoAcrescimo');
                                            $this->xml->text($value['reducaoAcrescimo']);
                                        $this->xml->endElement();#ans:reducaoAcrescimo
                                        $this->xml->startElement('ans:valorUnitario');
                                            $this->xml->text($value['valorUnitario']);
                                        $this->xml->endElement();#ans:valorUnitario
                                        $this->xml->startElement('ans:valorTotal');
                                            $this->xml->text($value['valorTotal']);
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
                                    $this->xml->endElement();#ans:sequencialItem
                                    $this->xml->startElement('ans:codigoDespesa');
                                        $this->xml->text($value['codigoDespesa']);
                                    $this->xml->endElement();#ans:codigoDespesa
                                    $this->xml->startElement('ans:servicosExecutados');
                                        $this->xml->startElement('ans:dataExecucao');
                                            $this->xml->text($value['dataExecucao']);
                                        $this->xml->endElement();#ans:dataExecucao
                                        $this->xml->startElement('ans:horaInicial');
                                            $this->xml->text($value['horaInicial']);
                                        $this->xml->endElement();#ans:horaInicial
                                        $this->xml->startElement('ans:horaFinal');
                                            $this->xml->text($value['horaFinal']);
                                        $this->xml->endElement();#ans:horaFinal
                                        $this->xml->startElement('ans:codigoTabela');
                                            $this->xml->text($value['codigoTabela']);
                                        $this->xml->endElement();#ans:codigoTabela
                                        $this->xml->startElement('ans:codigoProcedimento');
                                            $this->xml->text($value['codigoProcedimento']);
                                        $this->xml->endElement();#ans:codigoProcedimento
                                        $this->xml->startElement('ans:quantidadeExecutada');
                                            $this->xml->text($value['quantidadeExecutada']);
                                        $this->xml->endElement();#ans:quantidadeExecutada
                                        $this->xml->startElement('ans:unidadeMedida');
                                            $this->xml->text($value['unidadeMedida']);
                                        $this->xml->endElement();#ans:unidadeMedida
                                        $this->xml->startElement('ans:reducaoAcrescimo');
                                            $this->xml->text($value['reducaoAcrescimo']);
                                        $this->xml->endElement();#ans:reducaoAcrescimo
                                        $this->xml->startElement('ans:valorUnitario');
                                            $this->xml->text($value['valorUnitario']);
                                        $this->xml->endElement();#ans:valorUnitario
                                        $this->xml->startElement('ans:valorTotal');
                                            $this->xml->text($value['valorTotal']);
                                        $this->xml->endElement();#ans:valorTotal
                                        $this->xml->startElement('ans:descricaoProcedimento');
                                            $this->xml->text($value['descricaoProcedimento']);
                                        $this->xml->endElement();#ans:descricaoProcedimento
                                    $this->xml->endElement();#ans:servicosExecutados
                                $this->xml->endElement();#ans:despesa
                                $this->sequencialItem += 1;
                            }
                            $this->xml->endElement();#ans:outrasDespesas>


                            $this->xml->startElement('ans:valorTotal');
            //                     $this->xml->startElement('ans:valorProcedimentos');
            //                         $this->xml->text('560');
            //                     $this->xml->endElement();#ans:valorProcedimentos
            //                     $this->xml->startElement('ans:valorDiarias');
            //                         $this->xml->text('0.00');
            //                     $this->xml->endElement();#ans:valorDiarias
            //                     $this->xml->startElement('ans:valorTaxasAlugueis');
            //                         $this->xml->text('0');
            //                     $this->xml->endElement();#ans:valorTaxasAlugueis
            //                     $this->xml->startElement('ans:valorMateriais');
            //                         $this->xml->text('0');
            //                     $this->xml->endElement();#ans:valorMateriais
            //                     $this->xml->startElement('ans:valorMedicamentos');
            //                         $this->xml->text('0');
            //                     $this->xml->endElement();#ans:valorMedicamentos
            //                     $this->xml->startElement('ans:valorOPME');
            //                         $this->xml->text('0');
            //                     $this->xml->endElement();#ans:valorOPME
            //                     $this->xml->startElement('ans:valorGasesMedicinais');
            //                         $this->xml->text('0');
            //                     $this->xml->endElement();#ans:valorGasesMedicinais
            //                     $this->xml->startElement('ans:valorTotalGeral');
            //                         $this->xml->text('560');
            //                     $this->xml->endElement();#ans:valorTotalGeral
                            $this->xml->endElement();#ans:valorTotal


                        $this->xml->endElement();#ans:guiaSP-SADT
                    $this->xml->endElement();#ans:guiasTISS
                $this->xml->endElement();#ans:loteGuias
            $this->xml->endElement();#ans:prestadorParaOperadora
            // $this->xml->startElement('ans:epilogo');
            //     $this->xml->startElement('ans:hash');
            //         $this->xml->text('055879ec70a4ba5bc22fab72cafd3a94');
            //     $this->xml->endElement();#ans:hash
            // $this->xml->endElement();#ans:epilogo
        $this->xml->endElement();#ans:mensagemTISS
        $this->finalizarArquivo();

        // return null;
        return base64_encode($this->xml->outputMemory());
    }
}
