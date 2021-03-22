<?php

namespace App\Services;

use App\Models\CnabPessoa;
use App\Models\Pagamentopessoa;
use App\Models\RegistroCnab;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CnabService
{
    protected $sicred = [
        'codigo' => '748',
        'agencia' => '00703',
        'conta' => '74437',
        'digito_conta' => '9',
        'convenio' => '00000000000000074437',
        'nome' => 'SICREDI'
    ];

    protected $santander = [
        'codigo' => '033',
        'agencia' => '03800',
        'conta' => '13001140',
        'digito_conta' => '9',

        'convenio' => '00333800008302521456',
        'nome' => 'BANCO SANTANDER'
    ];

    protected $banco = null;
    protected $dados = [];
    protected $mes = [];
    protected $data = [];
    protected $user = null;
    protected $observacao = null;



    public function __construct($banco, $dados, $mes, $observacao, $data, User $user)
    {
        $this->banco = $banco;
        $this->dados = $dados;
        $this->mes = $mes;
        $this->data = $data;
        $this->user = $user;
        $this->observacao = $observacao;
    }




    public function criar_cnab()
    {

        //header arquivo
        $empresa_id = $this->user->pessoa->profissional->empresa_id;

        if ($this->banco == '033') {
            $cnabs = [];
            $cnab = '';
            $cnab .= $this->header_arquivo_santander();
            $quantidade_lotes = 0;
            //lote apenas usuários com conta santander
            $lote_1 = "\n" . $this->header_lote_santander(1, '033');
            $soma_valor_lote_1 = 0;
            $quantidade_moeda_lote_1 = 0;
            $dados_pagamento = [];
            $quantidade_registros=0;
            foreach ($this->dados as $dado) {
                $pagamentos = Pagamentopessoa::with(['pessoa.dadosbancario.banco'])
                    ->whereHas('pessoa.dadosbancario.banco', function ($q) {
                        $q->where('codigo', '=', '033');
                    })
                    ->where('empresa_id', $empresa_id)
                    ->where('pessoa_id', '=', $dado['profissional_id'])
                    ->where('status', false)
                    ->where(DB::raw("date_format(str_to_date(pagamentopessoas.periodo1, '%Y-%m-%d'), '%Y-%m')"), "=", $this->mes)
                    ->get()->sum('valor');

                if ($pagamentos > 0) {

                    array_push($dados_pagamento, [
                        'user_id' => $dado['profissional_id'],
                        'valor' => $pagamentos,
                        'conta' => $dado['conta'],
                        'digito' => $dado['digito'],
                        'agencia' => $dado['agencia'],
                        'codigo' => $dado['codigo'],
                    ]);
                    $quantidade_registros+=1;
                    Log::info($dado['profissional_id']);

                    Log::info($pagamentos);
                    $soma_valor_lote_1 += $pagamentos;
                    $lote_1 .= "\n" . $this->registro_detalhes_a_santander($dado, 1, 0, number_format($pagamentos, 2, '', ''));
                    $lote_1 .= "\n" . $this->registro_detalhes_b_santander($dado, 1, 1, number_format($pagamentos, 2, '', ''));
                }
            }

            $lote_1 .= "\n" . $this->trailer_lote_santander($quantidade_registros + 2, number_format($soma_valor_lote_1, 2, '', ''), $quantidade_moeda_lote_1, '033'); //quantidade contando header do lote, registro a e b, trailer do lote

            if ($soma_valor_lote_1 > 0) {
                $cnab .= $lote_1;
                $quantidade_lotes += 1;
                $cnab .= "\n" . $this->trailer_arquivo_santander(1);
                $name = 'cnabs/cnab_santander_folha_' . Carbon::now()->format('Y-m-d_H-i-s') . '.txt';

                Storage::disk('public')->put($name, $cnab);

                $registro_cnab = new RegistroCnab();
                $registro_cnab->fill([
                    'empresa_id' => $empresa_id,
                    'arquivo' => 'storage/' . $name,
                    'mes' => $this->mes,
                    'codigo_banco' => $this->santander['codigo'],
                    'data' => $this->data,
                    'observacao' => $this->observacao,
                    'situacao' => 'Aguardando',
                ])->save();
                foreach ($dados_pagamento as $dado) {
                    $cnab_pessoa = new CnabPessoa();
                    $cnab_pessoa->fill([
                        'cnab_id' => $registro_cnab->id,
                        'pessoa_id' => $dado['user_id'],
                        'valor' => $dado['valor'],
                        'conta' => $dado['conta'],
                        'agencia' => $dado['agencia'],
                        'banco' => $dado['codigo'],
                        'digito' => $dado['digito'],
                    ])->save();
                }
                $name = explode('/', $registro_cnab->arquivo)[count(explode('/', $registro_cnab->arquivo)) - 1];

                array_push($cnabs, ['cnab' => $registro_cnab->id, 'name' => $name]);
            }

            $cnab = '';
            $cnab .= $this->header_arquivo_santander();
            //lote apenas usuários sem conta santander
            $lote_2 = "\n" . $this->header_lote_santander(1, '000');
            $soma_valor_lote_2 = 0;
            $quantidade_moeda_lote_2 = 0;
            $dados_pagamento = [];
            $quantidade_registros=0;

            foreach ($this->dados as $dado) {
                $pagamentos = Pagamentopessoa::with(['pessoa.dadosbancario.banco'])
                    ->whereHas('pessoa.dadosbancario.banco', function ($q) {
                        $q->where('codigo', '<>', '033');
                    })
                    ->where('empresa_id', $empresa_id)
                    ->where('pessoa_id', '=', $dado['profissional_id'])
                    ->where('status', false)
                    ->where(DB::raw("date_format(str_to_date(pagamentopessoas.periodo1, '%Y-%m-%d'), '%Y-%m')"), "=", $this->mes)
                    ->get()->sum('valor');

                if ($pagamentos > 0) {
                    $quantidade_registros+=1;

                    array_push($dados_pagamento, [
                        'user_id' => $dado['profissional_id'],
                        'valor' => $pagamentos,
                        'conta' => $dado['conta'],
                        'digito' => $dado['digito'],
                        'agencia' => $dado['agencia'],
                        'codigo' => $dado['codigo'],
                    ]);

                    Log::info($dado['profissional_id']);

                    Log::info($pagamentos);
                    $soma_valor_lote_2 += $pagamentos;
                    $lote_2 .= "\n" . $this->registro_detalhes_a_santander($dado, 1, 0, number_format($pagamentos, 2, '', ''));
                    $lote_2 .= "\n" . $this->registro_detalhes_b_santander($dado, 1, 2, number_format($pagamentos, 2, '', ''));
                }
            }

            $lote_2 .= "\n" . $this->trailer_lote_santander($quantidade_registros + 2, number_format($soma_valor_lote_2, 2, '', ''), $quantidade_moeda_lote_2, '000'); //quantidade contando header do lote, registro a e b, trailer do lote

            if ($soma_valor_lote_2 > 0) {
                $cnab .= $lote_2;
                $quantidade_lotes += 1;
                $cnab .= "\n" . $this->trailer_arquivo_santander(1);
                $name = 'cnabs/cnab_santander_fornecedor_' . Carbon::now()->format('Y-m-d_H-i-s') . '.txt';

                Storage::disk('public')->put($name, $cnab);

                $registro_cnab = new RegistroCnab();
                $registro_cnab->fill([
                    'empresa_id' => $empresa_id,
                    'arquivo' => 'storage/' . $name,
                    'mes' => $this->mes,
                    'codigo_banco' => $this->santander['codigo'],
                    'data' => $this->data,
                    'observacao' => $this->observacao,
                    'situacao' => 'Aguardando',
                ])->save();
                foreach ($dados_pagamento as $dado) {
                    $cnab_pessoa = new CnabPessoa();
                    $cnab_pessoa->fill([
                        'cnab_id' => $registro_cnab->id,
                        'pessoa_id' => $dado['user_id'],
                        'valor' => $dado['valor'],
                        'conta' => $dado['conta'],
                        'agencia' => $dado['agencia'],
                        'banco' => $dado['codigo'],
                        'digito' => $dado['digito'],
                    ])->save();
                }
                $name = explode('/', $registro_cnab->arquivo)[count(explode('/', $registro_cnab->arquivo)) - 1];

                array_push($cnabs, ['cnab' => $registro_cnab->id, 'name' => $name]);
            }

            return ['status' => true, 'cnabs' => $cnabs];
        } elseif ($this->banco == '748') {
            $cnab = '';
            $cnabs = [];
            $cnab .= $this->header_arquivo_sicredi();

            $cnab .= "\n" . $this->header_lote_sicredi(1, '748');
            $soma_valor = 0;
            $quantidade_moeda = 0;
            $dados_pagamento = [];
            $quantidade_registros=0;
            foreach ($this->dados as $dado) {
                $pagamentos = Pagamentopessoa::with(['pessoa.dadosbancario.banco'])
                    ->whereHas('pessoa.dadosbancario.banco', function ($q) {
                        $q->where('codigo', '=', '748');
                    })
                    ->where('empresa_id', $empresa_id)
                    ->where('pessoa_id', '=', $dado['profissional_id'])
                    ->where('status', false)
                    ->where(DB::raw("date_format(str_to_date(pagamentopessoas.periodo1, '%Y-%m-%d'), '%Y-%m')"), "=", $this->mes)
                    ->get()->sum('valor');
                if ($pagamentos > 0) {
                    Log::info($dado['profissional_id']);
                    $quantidade_registros+=1;
                    Log::info($pagamentos);
                    array_push($dados_pagamento, [
                        'user_id' => $dado['profissional_id'],
                        'valor' => $pagamentos,
                        'conta' => $dado['conta'],
                        'digito' => $dado['digito'],
                        'agencia' => $dado['agencia'],
                        'codigo' => $dado['codigo'],
                    ]);
                    $soma_valor += $pagamentos;
                    $cnab .= "\n" .  $this->registro_detalhes_a_sicred($dado, 1, 0, number_format($pagamentos, 2, '', ''));
                    $cnab .= "\n" . $this->registro_detalhes_b_sicred($dado, 1, 1, number_format($pagamentos, 2, '', ''));
                }
            }

            if ($soma_valor > 0) {

                $cnab .= "\n" . $this->trailer_lote_sicred($quantidade_registros + 2, number_format($soma_valor, 2, '', ''), $quantidade_moeda, '748'); //quantidade contando header do lote, registro a e b, trailer do lote
                $cnab .= "\n" . $this->trailer_arquivo_sicred(1);

                $name = 'cnabs/cnab_sicredi_folha_' . Carbon::now()->format('Y-m-d_H-i-s') . '.txt';

                Storage::disk('public')->put($name, $cnab);

                $registro_cnab = new RegistroCnab();
                $registro_cnab->fill([
                    'empresa_id' => $empresa_id,
                    'arquivo' => 'storage/' . $name,
                    'mes' => $this->mes,
                    'codigo_banco' => $this->sicred['codigo'],
                    'data' => $this->data,
                    'observacao' => $this->observacao,
                    'situacao' => 'Aguardando',
                ])->save();
                foreach ($dados_pagamento as $dado) {
                    $cnab_pessoa = new CnabPessoa();
                    $cnab_pessoa->fill([
                        'cnab_id' => $registro_cnab->id,
                        'pessoa_id' => $dado['user_id'],
                        'valor' => $dado['valor'],
                        'conta' => $dado['conta'],
                        'agencia' => $dado['agencia'],
                        'banco' => $dado['codigo'],
                        'digito' => $dado['digito'],
                    ])->save();
                }
                $name = explode('/', $registro_cnab->arquivo)[count(explode('/', $registro_cnab->arquivo)) - 1];

                array_push($cnabs, ['cnab' => $registro_cnab->id, 'name' => $name]);
            }




            //outros bancos sem ser sicredi
            $cnab = '';
            $cnabs = [];
            $cnab .= $this->header_arquivo_sicredi();

            $cnab .= "\n" . $this->header_lote_sicredi(1, '000');
            $soma_valor = 0;
            $quantidade_moeda = 0;
            $dados_pagamento = [];
            $quantidade_registros=0;

            foreach ($this->dados as $dado) {
                $pagamentos = Pagamentopessoa::with(['pessoa.dadosbancario.banco'])
                    ->whereHas('pessoa.dadosbancario.banco', function ($q) {
                        $q->where('codigo', '<>', '748');
                    })
                    ->where('empresa_id', $empresa_id)
                    ->where('pessoa_id', '=', $dado['profissional_id'])
                    ->where('status', false)
                    ->where(DB::raw("date_format(str_to_date(pagamentopessoas.periodo1, '%Y-%m-%d'), '%Y-%m')"), "=", $this->mes)
                    ->get()->sum('valor');
                if ($pagamentos > 0) {
                    Log::info($dado['profissional_id']);
                    $quantidade_registros+=1;

                    Log::info($pagamentos);
                    array_push($dados_pagamento, [
                        'user_id' => $dado['profissional_id'],
                        'valor' => $pagamentos,
                        'conta' => $dado['conta'],
                        'digito' => $dado['digito'],
                        'agencia' => $dado['agencia'],
                        'codigo' => $dado['codigo'],
                    ]);
                    $soma_valor += $pagamentos;
                    $cnab .= "\n" .  $this->registro_detalhes_a_sicred($dado, 1, 0, number_format($pagamentos, 2, '', ''));
                    $cnab .= "\n" . $this->registro_detalhes_b_sicred($dado, 1, 1, number_format($pagamentos, 2, '', ''));
                }
            }

            if ($soma_valor > 0) {

                $cnab .= "\n" . $this->trailer_lote_sicred($quantidade_registros + 2, number_format($soma_valor, 2, '', ''), $quantidade_moeda, '000'); //quantidade contando header do lote, registro a e b, trailer do lote
                $cnab .= "\n" . $this->trailer_arquivo_sicred(1);

                $name = 'cnabs/cnab_sicredi_fornecedor_' . Carbon::now()->format('Y-m-d_H-i-s') . '.txt';

                Storage::disk('public')->put($name, $cnab);

                $registro_cnab = new RegistroCnab();
                $registro_cnab->fill([
                    'empresa_id' => $empresa_id,
                    'arquivo' => 'storage/' . $name,
                    'mes' => $this->mes,
                    'codigo_banco' => $this->sicred['codigo'],
                    'data' => $this->data,
                    'observacao' => $this->observacao,
                    'situacao' => 'Aguardando',
                ])->save();
                foreach ($dados_pagamento as $dado) {
                    $cnab_pessoa = new CnabPessoa();
                    $cnab_pessoa->fill([
                        'cnab_id' => $registro_cnab->id,
                        'pessoa_id' => $dado['user_id'],
                        'valor' => $dado['valor'],
                        'conta' => $dado['conta'],
                        'agencia' => $dado['agencia'],
                        'banco' => $dado['codigo'],
                        'digito' => $dado['digito'],
                    ])->save();
                }
                $name = explode('/', $registro_cnab->arquivo)[count(explode('/', $registro_cnab->arquivo)) - 1];

                array_push($cnabs, ['cnab' => $registro_cnab->id, 'name' => $name]);
            }
            return ['status' => true, 'cnabs' => $cnabs];
        }
        return ['status' => false];
        //trailer de arquivo
    }

    public function header_arquivo_santander()
    {
        $cod_banco = $this->santander['codigo']; //1 a 3
        $lote = "0000"; //4 a 7
        $tipo_registro = "0"; //tipo header 8 a 8
        $uso_exclusivo = ""; //9 a 17
        for ($i = 9; $i <= 17; $i++) {
            $uso_exclusivo .= " ";
        }
        $tipo_inscricao_empresa = "2"; //tipo cpf 1 ou cnpj 2  18
        $numero_inscricao = "12316361000120"; //valor cnpj ou cpf   19 a 32
        $num = Str::length($numero_inscricao);
        for ($i = 19 + $num; $i <= 32; $i++) {
            $numero_inscricao = "0" . $numero_inscricao;
        }
        $convenio = $this->santander['convenio']; //convenio do banco (numero do contrato com o banco) 33 a 52
        $num = Str::length($convenio);

        for ($i = 33 + $num; $i <= 52; $i++) {
            $convenio .= " ";
        }

        $agencia_mantenedora = $this->santander['agencia']; //53 a 57
        $digito_verificador_agencia = " "; //58 a 58

        $numero_conta_corrente = $this->santander['conta']; //59 a 70
        $num = Str::length($numero_conta_corrente);

        for ($i = 59 + $num; $i <= 70; $i++) {
            $numero_conta_corrente = "0" . $numero_conta_corrente;
        }
        $digito_verificador_conta = $this->santander['digito_conta']; //71 a 71

        $digito_verificador_agencia_conta = " "; //72 a 72


        $nome_empresa = "HOME CARE ENFERLIFE HOSPITALAR"; //73 a 102
        $num = Str::length($nome_empresa);

        for ($i = 73 + $num; $i <= 102; $i++) {
            $nome_empresa .= " ";
        }

        $nome_banco = $this->santander['nome']; //103 a 132
        $num = Str::length($nome_banco);

        for ($i = 103 + $num; $i <= 132; $i++) {
            $nome_banco .= " ";
        }

        $uso_exclusivo_2 = ""; //133 a 142

        for ($i = 133; $i <= 142; $i++) {
            $uso_exclusivo_2 .= " ";
        }

        $cod_remessa_retorno = "1"; //143 a 143

        $data_geracao_arquivo = Carbon::now()->format('dmY'); //144 a 151 /DDMMAAAA

        $hora_geracao_arquivo = Carbon::now()->format('His'); //152 a 157 //HHMMSS

        $num_sequencial_arquivo = "000001"; //158 a 163

        $num_versao_arquivo = "060"; //164 a 166

        $densidade_gravacao_arquivo = "00000"; //167 a 171

        $reservado_banco = ""; //172 a 191

        $num = Str::length($reservado_banco);

        for ($i = 172 + $num; $i <= 191; $i++) {
            $reservado_banco .= " ";
        }


        $reservado_empresa = ""; //192 a 211

        $num = Str::length($reservado_empresa);

        for ($i = 192 + $num; $i <= 211; $i++) {
            $reservado_empresa .= " ";
        }

        $uso_exclusivo_3 = ""; //212 a 240

        for ($i = 212; $i <= 240; $i++) {
            $uso_exclusivo_3 .= " ";
        }


        $header_arquivo = $cod_banco . $lote . $tipo_registro . $uso_exclusivo . $tipo_inscricao_empresa . $numero_inscricao . $convenio . $agencia_mantenedora .
            $digito_verificador_agencia . $numero_conta_corrente . $digito_verificador_conta . $digito_verificador_agencia_conta . $nome_empresa . $nome_banco .
            $uso_exclusivo_2 . $cod_remessa_retorno . $data_geracao_arquivo . $hora_geracao_arquivo . $num_sequencial_arquivo . $num_versao_arquivo . $densidade_gravacao_arquivo .
            $reservado_banco . $reservado_empresa . $uso_exclusivo_3;

        return $header_arquivo;
    }

    public function header_arquivo_sicredi()
    {
        $cod_banco = $this->sicred['codigo']; //1 a 3
        $lote = "0000"; //4 a 7
        $tipo_registro = "0"; //tipo header 8 a 8
        $uso_exclusivo = ""; //9 a 17
        for ($i = 9; $i <= 17; $i++) {
            $uso_exclusivo .= " ";
        }
        $tipo_inscricao_empresa = "2"; //tipo cpf 1 ou cnpj 2  18
        $numero_inscricao = "12316361000120"; //valor cnpj ou cpf   19 a 32
        $num = Str::length($numero_inscricao);
        for ($i = 19 + $num; $i <= 32; $i++) {
            $numero_inscricao = "0" . $numero_inscricao;
        }
        $convenio = $this->sicred['convenio']; //convenio do banco (numero do contrato com o banco) 33 a 52
        $num = Str::length($convenio);

        for ($i = 33 + $num; $i <= 52; $i++) {
            $convenio .= " ";
        }

        $agencia_mantenedora = $this->sicred['agencia']; //53 a 57
        $digito_verificador_agencia = " "; //58 a 58

        $numero_conta_corrente = $this->sicred['conta']; //59 a 70
        $num = Str::length($numero_conta_corrente);

        for ($i = 59 + $num; $i <= 70; $i++) {
            $numero_conta_corrente = "0" . $numero_conta_corrente;
        }
        $digito_verificador_conta = $this->sicred['digito_conta']; //71 a 71

        $digito_verificador_agencia_conta = " "; //72 a 72


        $nome_empresa = "HOME CARE ENFERLIFE HOSPITALAR"; //73 a 102
        $num = Str::length($nome_empresa);

        for ($i = 73 + $num; $i <= 102; $i++) {
            $nome_empresa .= " ";
        }

        $nome_banco = $this->sicred['nome']; //103 a 132
        $num = Str::length($nome_banco);

        for ($i = 103 + $num; $i <= 132; $i++) {
            $nome_banco .= " ";
        }

        $uso_exclusivo_2 = ""; //133 a 142

        for ($i = 133; $i <= 142; $i++) {
            $uso_exclusivo_2 .= " ";
        }

        $cod_remessa_retorno = "1"; //143 a 143

        $data_geracao_arquivo = Carbon::now()->format('dmY');; //144 a 151 /DDMMAAAA

        $hora_geracao_arquivo = Carbon::now()->format('His'); //152 a 157 //HHMMSS

        $num_sequencial_arquivo = "000001"; //158 a 163

        $num_versao_arquivo = "082"; //164 a 166

        $densidade_gravacao_arquivo = "01600"; //167 a 171

        $reservado_banco = ""; //172 a 191

        $num = Str::length($reservado_banco);

        for ($i = 172 + $num; $i <= 191; $i++) {
            $reservado_banco .= " ";
        }


        $reservado_empresa = ""; //192 a 211

        $num = Str::length($reservado_empresa);

        for ($i = 192 + $num; $i <= 211; $i++) {
            $reservado_empresa .= " ";
        }

        $uso_exclusivo_3 = ""; //212 a 240

        for ($i = 212; $i <= 240; $i++) {
            $uso_exclusivo_3 .= " ";
        }


        $header_arquivo = $cod_banco . $lote . $tipo_registro . $uso_exclusivo . $tipo_inscricao_empresa . $numero_inscricao . $convenio . $agencia_mantenedora .
            $digito_verificador_agencia . $numero_conta_corrente . $digito_verificador_conta . $digito_verificador_agencia_conta . $nome_empresa . $nome_banco .
            $uso_exclusivo_2 . $cod_remessa_retorno . $data_geracao_arquivo . $hora_geracao_arquivo . $num_sequencial_arquivo . $num_versao_arquivo . $densidade_gravacao_arquivo .
            $reservado_banco . $reservado_empresa . $uso_exclusivo_3;

        return $header_arquivo;
    }

    public function trailer_arquivo_sicred($quantidade_lotes)
    {
        $banco_t = $this->sicred['codigo'];
        $lote_t = "9999";
        $tipo_registro_t = "9";
        $uso_exclusivo_t = "";

        for ($i = 9; $i <= 17; $i++) {
            $uso_exclusivo_t .= " ";
        }

        $quant_lotes_t = $quantidade_lotes . '';

        $num = Str::length($quant_lotes_t);

        for ($i = 18 + $num; $i <= 23; $i++) {
            $quant_lotes_t = "0" . $quant_lotes_t;
        }

        $registros = $quantidade_lotes * 4 + 2; //numero total de registros (header arquivo, header lote, regi detalhe, trailer lote, trailer arquivo)
        //verificar


        $num = Str::length($registros . '');

        for ($i = 24 + $num; $i <= 29; $i++) {
            $registros = "0" . $registros;
        }

        $quant_contas_conciliadas = "0"; //numero de registros de arquivos do tipo 1 e tipo de operação 'E'
        for ($i = 30 + $num; $i <= 35; $i++) {
            $quant_contas_conciliadas = "0" . $quant_contas_conciliadas;
        }

        $uso_exclusivo_t_2 = "";

        for ($i = 36; $i <= 240; $i++) {
            $uso_exclusivo_t_2 .= " ";
        }

        $trailer_arquivo = $banco_t . $lote_t . $tipo_registro_t . $uso_exclusivo_t . $quant_lotes_t . $registros . $quant_contas_conciliadas . $uso_exclusivo_t_2;
        return $trailer_arquivo;
    }


    public function trailer_arquivo_santander($quantidade_lotes)
    {
        $banco_t = $this->santander['codigo'];
        $lote_t = "9999";
        $tipo_registro_t = "9";
        $uso_exclusivo_t = "";

        for ($i = 9; $i <= 17; $i++) {
            $uso_exclusivo_t .= " ";
        }

        $quant_lotes_t = $quantidade_lotes . '';

        $num = Str::length($quant_lotes_t);

        for ($i = 18 + $num; $i <= 23; $i++) {
            $quant_lotes_t = "0" . $quant_lotes_t;
        }

        $registros = $quantidade_lotes * 4 + 2; //numero total de registros (header arquivo, header lote, regi detalhe, trailer lote, trailer arquivo)
        //verificar


        $num = Str::length($registros . '');

        for ($i = 24 + $num; $i <= 29; $i++) {
            $registros = "0" . $registros;
        }



        $uso_exclusivo_t_2 = "";

        for ($i = 30; $i <= 240; $i++) {
            $uso_exclusivo_t_2 .= " ";
        }

        $trailer_arquivo = $banco_t . $lote_t . $tipo_registro_t . $uso_exclusivo_t . $quant_lotes_t . $registros  . $uso_exclusivo_t_2;
        return $trailer_arquivo;
    }

    public function header_lote_santander($num_lote, $banco_codigo)
    {
        $banco = $this->santander['codigo'];
        $lote_servico = $num_lote . '';
        $num = Str::length($lote_servico . '');

        for ($i = 4 + $num; $i <= 7; $i++) {
            $lote_servico = "0" . $lote_servico;
        }

        $tipo_registro = "1";

        $tipo_operacao = "C";
        /*‘03’ = Bloqueto Eletrônico
        '20' = Pagamento Fornecedor
        ‘22’ = Pagamento de Contas,
        '30' = Pagamento Salários*/
        $tipo_servico = $banco_codigo == "033" ? "30" : "20";
        /*
        ‘01’ = Crédito em Conta Corrente
        ‘03’ = DOC
        ‘10’ = OP à Disposição
        ‘11’ = Pagamento de Contas e Tributos com Código de Barras
        ‘16’ = Tributo – DARF Normal
        ‘17’ = Tributo – GPS (Guia da Previdência Social)
        ‘18’ = Tributo – DARF Simples
        ‘30’ = Liquidação de Títulos do Próprio Banco
        ‘31’ = Pagamento de Títulos de Outros Bancos
        ‘41’ = TED – Transferência entre Clientes

        */
        $forma_lancamento = $banco_codigo == "033" ? "01" : "03";

        $layout_lote = "031";

        $uso_exclusivo = " "; //17



        $tipo_inscricao_empresa = "2"; //tipo cpf 1 ou cnpj 2  18
        $numero_inscricao = "12316361000120"; //valor cnpj ou cpf   19 a 32
        $num = Str::length($numero_inscricao);
        for ($i = 19 + $num; $i <= 32; $i++) {
            $numero_inscricao = "0" . $numero_inscricao;
        }

        //verificar numero de convenio pra sicred
        $convenio = $this->santander['convenio']; //convenio do banco (numero do contrato com o banco) 33 a 52
        $num = Str::length($convenio);

        for ($i = 33 + $num; $i <= 52; $i++) {
            $convenio .= " ";
        }

        $agencia_mantenedora = $this->santander['agencia']; //53 a 57
        $digito_verificador_agencia = " "; //58 a 58

        $numero_conta_corrente = $this->santander['conta']; //59 a 70
        $num = Str::length($numero_conta_corrente);

        for ($i = 59 + $num; $i <= 70; $i++) {
            $numero_conta_corrente = "0" . $numero_conta_corrente;
        }
        $digito_verificador_conta = $this->santander['digito_conta']; //71 a 71

        $digito_verificador_agencia_conta = " "; //72 a 72


        $nome_empresa = "HOME CARE ENFERLIFE HOSPITALAR"; //73 a 102
        $num = Str::length($nome_empresa);

        for ($i = 73 + $num; $i <= 102; $i++) {
            $nome_empresa .= " ";
        }


        $mensagem = "";
        $num = Str::length($mensagem);

        for ($i = 103 + $num; $i <= 142; $i++) {
            $mensagem .= " ";
        }

        //verificar se coloca endereco
        $rua = "";
        $num = Str::length($rua);

        for ($i = 143 + $num; $i <= 172; $i++) {
            $rua .= " ";
        }


        $numero = "";
        $num = Str::length($numero);

        for ($i = 173 + $num; $i <= 177; $i++) {
            // $numero = "0".$numero;
            $numero = " " . $numero;
        }


        $complemento = "";
        $num = Str::length($complemento);

        for ($i = 178 + $num; $i <= 192; $i++) {
            $complemento .= " ";
        }


        $cidade = "";
        $num = Str::length($cidade);

        for ($i = 193 + $num; $i <= 212; $i++) {
            $cidade .= " ";
        }


        // $cep = "15040";
        $cep = "     ";


        // $complemento_cep = "644";
        $complemento_cep = "   ";


        // $estado="SP";
        $estado = "  ";


        $uso_exclusivo2 = ""; //9 a 17
        for ($i = 223; $i <= 230; $i++) {
            $uso_exclusivo2 .= " ";
        }

        // $ocorrencias = "00010203AA";
        $ocorrencias = "          ";


        $header_lote = $banco . $lote_servico . $tipo_registro . $tipo_operacao . $tipo_servico . $forma_lancamento . $layout_lote . $uso_exclusivo . $tipo_inscricao_empresa .
            $numero_inscricao . $convenio . $agencia_mantenedora . $digito_verificador_agencia . $numero_conta_corrente . $digito_verificador_conta . $digito_verificador_agencia_conta .
            $nome_empresa . $mensagem . $rua . $numero . $complemento . $cidade . $cep . $complemento_cep . $estado . $uso_exclusivo2 . $ocorrencias;

        return $header_lote;
    }

    public function header_lote_sicredi($num_lote, $codigo)
    {
        $banco = $this->sicred['codigo'];
        $lote_servico = $num_lote . '';
        $num = Str::length($lote_servico . '');

        for ($i = 4 + $num; $i <= 7; $i++) {
            $lote_servico = "0" . $lote_servico;
        }

        $tipo_registro = "1";

        $tipo_operacao = "C";
        /*‘03’ = Bloqueto Eletrônico
        '20' = Pagamento Fornecedor
        ‘22’ = Pagamento de Contas,
        '30' = Pagamento Salários*/
        $tipo_servico = $codigo == "748" ? "30" : "20";
        /*
        ‘01’ = Crédito em Conta Corrente
        ‘03’ = DOC
        ‘10’ = OP à Disposição
        ‘11’ = Pagamento de Contas e Tributos com Código de Barras
        ‘16’ = Tributo – DARF Normal
        ‘17’ = Tributo – GPS (Guia da Previdência Social)
        ‘18’ = Tributo – DARF Simples
        ‘30’ = Liquidação de Títulos do Próprio Banco
        ‘31’ = Pagamento de Títulos de Outros Bancos
        ‘41’ = TED – Transferência entre Clientes

        */
        $forma_lancamento = $codigo == "748" ? "01" : "41";

        $layout_lote = "042";

        $uso_exclusivo = " "; //17



        $tipo_inscricao_empresa = "2"; //tipo cpf 1 ou cnpj 2  18
        $numero_inscricao = "12316361000120"; //valor cnpj ou cpf   19 a 32
        $num = Str::length($numero_inscricao);
        for ($i = 19 + $num; $i <= 32; $i++) {
            $numero_inscricao = "0" . $numero_inscricao;
        }

        //verificar numero de convenio pra sicred
        $convenio = $this->sicred['convenio']; //convenio do banco (numero do contrato com o banco) 33 a 52
        $num = Str::length($convenio);

        for ($i = 33 + $num; $i <= 52; $i++) {
            $convenio .= " ";
        }

        $agencia_mantenedora = $this->sicred['agencia']; //53 a 57
        $digito_verificador_agencia = " "; //58 a 58

        $numero_conta_corrente = $this->sicred['conta']; //59 a 70
        $num = Str::length($numero_conta_corrente);

        for ($i = 59 + $num; $i <= 70; $i++) {
            $numero_conta_corrente = "0" . $numero_conta_corrente;
        }
        $digito_verificador_conta = $this->sicred['digito_conta']; //71 a 71

        $digito_verificador_agencia_conta = " "; //72 a 72


        $nome_empresa = "HOME CARE ENFERLIFE HOSPITALAR"; //73 a 102
        $num = Str::length($nome_empresa);

        for ($i = 73 + $num; $i <= 102; $i++) {
            $nome_empresa .= " ";
        }


        $mensagem = "";
        $num = Str::length($mensagem);

        for ($i = 103 + $num; $i <= 142; $i++) {
            $mensagem .= " ";
        }

        //verificar se coloca endereco
        $rua = "";
        $num = Str::length($rua);

        for ($i = 143 + $num; $i <= 172; $i++) {
            $rua .= " ";
        }


        $numero = "";
        $num = Str::length($numero);

        for ($i = 173 + $num; $i <= 177; $i++) {
            // $numero = "0".$numero;
            $numero = " " . $numero;
        }


        $complemento = "";
        $num = Str::length($complemento);

        for ($i = 178 + $num; $i <= 192; $i++) {
            $complemento .= " ";
        }


        $cidade = "";
        $num = Str::length($cidade);

        for ($i = 193 + $num; $i <= 212; $i++) {
            $cidade .= " ";
        }


        // $cep = "15040";
        $cep = "     ";


        // $complemento_cep = "644";
        $complemento_cep = "   ";


        // $estado="SP";
        $estado = "  ";


        $uso_exclusivo2 = ""; //9 a 17
        for ($i = 223; $i <= 230; $i++) {
            $uso_exclusivo2 .= " ";
        }

        // $ocorrencias = "00010203AA";
        $ocorrencias = "          ";


        $header_lote = $banco . $lote_servico . $tipo_registro . $tipo_operacao . $tipo_servico . $forma_lancamento . $layout_lote . $uso_exclusivo . $tipo_inscricao_empresa .
            $numero_inscricao . $convenio . $agencia_mantenedora . $digito_verificador_agencia . $numero_conta_corrente . $digito_verificador_conta . $digito_verificador_agencia_conta .
            $nome_empresa . $mensagem . $rua . $numero . $complemento . $cidade . $cep . $complemento_cep . $estado . $uso_exclusivo2 . $ocorrencias;

        return $header_lote;
    }


    public function registro_detalhes_a_sicred($user_data, $num_lote, $quantidade_moeda, $valor_pagamento)
    {
        $user = User::where('pessoa_id', '=', $user_data['profissional_id'])->first();
        // Log::info($user->pessoa()->first()->dadosbancario()->first()->agencia);
        $pessoa = $user->pessoa()->first();
        // $dados=$pessoa->dadosbancario()->first();
        // $banco_favorecido=$dados->banco()->first();


        $banco = $this->sicred['codigo'];
        $lote_servico = $num_lote . '';
        $num = Str::length($lote_servico . '');

        for ($i = 4 + $num; $i <= 7; $i++) {
            $lote_servico = "0" . $lote_servico;
        }

        $tipo_registro = "3";

        $num_registro = $num_lote;
        $num = Str::length($num_registro . '');

        for ($i = 9 + $num; $i <= 13; $i++) {
            $num_registro = "0" . $num_registro;
        }

        $segmento = 'A';

        $tipo_movimentacao = '0';
        $codigo_instrucao_movimento = '00';

        $codigo_camera_centralizadora = $user_data['codigo'] == "748" ? '000' : "018"; //usar tudo zero ou 700 (DOC)


        $codigo_banco_favorecido = $user_data['codigo'];
        Log::info($codigo_banco_favorecido);

        $num = Str::length($codigo_banco_favorecido . '');

        for ($i = 21 + $num; $i <= 23; $i++) {
            $codigo_banco_favorecido = "0" . $codigo_banco_favorecido;
        }


        $agencia_mantedora = Str::replaceArray('-', [''], $user_data['agencia']);
        $num = Str::length($agencia_mantedora . '');
        Log::info($agencia_mantedora);
        for ($i = 24 + $num; $i <= 28; $i++) {
            $agencia_mantedora = "0" . $agencia_mantedora;
        }

        $digito_verificador_agencia = ' ';

        $numero_conta_corrente = Str::replaceArray('-', [''], $user_data['conta']);
        $num = Str::length($numero_conta_corrente . '');

        for ($i = 30 + $num; $i <= 41; $i++) {
            $numero_conta_corrente = "0" . $numero_conta_corrente;
        }

        $digito_verificador_conta = $user_data['digito'];
        $digito_verificador_agencia_conta = ' ';
        Log::info($pessoa->nome);
        $nome_favorecido = Str::limit($pessoa->nome, 30, '');
        $num = Str::length($nome_favorecido . '');

        for ($i = 44 + $num; $i <= 73; $i++) {
            $nome_favorecido = $nome_favorecido . ' ';
        }

        $seu_numero = $pessoa->cpfcnpj . Carbon::now()->format('dmY');

        $num = Str::length($seu_numero . '');

        for ($i = 74 + $num; $i <= 93; $i++) {
            $seu_numero = $seu_numero . ' ';
        }

        $data_pagamento = Carbon::createFromFormat('Y-m-d', $this->data)->format('dmY');

        $tipo_moeda = 'BRL';

        $quantidade_moeda = $quantidade_moeda;

        $num = Str::length($quantidade_moeda . '');

        for ($i = 105 + $num; $i <= 119; $i++) {
            $quantidade_moeda = "0" . $quantidade_moeda;
        }

        $valor_pagamento = $valor_pagamento;

        $num = Str::length($valor_pagamento . '');

        for ($i = 120 + $num; $i <= 134; $i++) {
            $valor_pagamento = "0" . $valor_pagamento;
        }

        $nosso_numero = '';
        $num = Str::length($nosso_numero . '');

        for ($i = 135 + $num; $i <= 154; $i++) {
            $nosso_numero = " " . $nosso_numero;
        }

        $data_real_pagamento = Carbon::now()->format('dmY');
        $num = Str::length($data_real_pagamento . '');

        // $data_real_pagamento = Carbon::now()->addDays(2)->format('dmY');
        //verificar
        for ($i = 155+$num; $i <= 162; $i++) {
            $data_real_pagamento = "0" . $data_real_pagamento;
        }

        $valor_real = $user_data['codigo'] == "748" ? "" : $valor_pagamento;
        //verificar
        $num = Str::length($valor_real . '');

        for ($i = 163+$num; $i <= 177; $i++) {
            $valor_real = "0" . $valor_real;
            // $valor_real = " " . $valor_real;
        }


        // Log::info(Str::length($valor_real.$data_real_pagamento));
        $informacao_2 = '';
        $num = Str::length($informacao_2 . '');

        for ($i = 178 + $num; $i <= 217; $i++) {
            $informacao_2 = " " . $informacao_2;
        }

        $codigo_finalidade_doc = $user_data['codigo'] == "748" ? '06' : '07';
        $codigo_finalidade_ted = $user_data['codigo'] == "748" ? '     ' : '00005';

        $codigo_finalidade_complementar = '  ';

        $uso_exclusivo = '   ';
        $aviso = '0';
        $ocorrencias = '          ';

        $registro_a = $banco . $lote_servico . $tipo_registro . $num_registro . $segmento . $tipo_movimentacao . $codigo_instrucao_movimento .
            $codigo_camera_centralizadora . $codigo_banco_favorecido . $agencia_mantedora . $digito_verificador_agencia . $numero_conta_corrente .
            $digito_verificador_conta . $digito_verificador_agencia_conta . $nome_favorecido . $seu_numero . $data_pagamento . $tipo_moeda .
            $quantidade_moeda . $valor_pagamento . $nosso_numero . $data_real_pagamento . $valor_real . $informacao_2 . $codigo_finalidade_doc .
            $codigo_finalidade_ted . $codigo_finalidade_complementar . $uso_exclusivo . $aviso . $ocorrencias;

        return $registro_a;
    }

    public function registro_detalhes_a_santander($user_data, $num_lote, $quantidade_moeda, $valor_pagamento)
    {
        $user = User::where('pessoa_id', '=', $user_data['profissional_id'])->first();
        // Log::info($user->pessoa()->first()->dadosbancario()->first()->agencia);
        $pessoa = $user->pessoa()->first();
        // $dados=$pessoa->dadosbancario()->first();
        // $banco_favorecido=$dados->banco()->first();


        $banco = $this->santander['codigo'];
        $lote_servico = $num_lote . '';
        $num = Str::length($lote_servico . '');

        for ($i = 4 + $num; $i <= 7; $i++) {
            $lote_servico = "0" . $lote_servico;
        }

        $tipo_registro = "3";

        $num_registro = $num_lote;
        $num = Str::length($num_registro . '');

        for ($i = 9 + $num; $i <= 13; $i++) {
            $num_registro = "0" . $num_registro;
        }

        $segmento = 'A';

        $tipo_movimentacao = '0';
        $codigo_instrucao_movimento = '00';

        $codigo_camera_centralizadora = $user_data['codigo'] == "033" ? "000" : '018'; //usar tudo zero ou 700 (DOC)


        $codigo_banco_favorecido = $user_data['codigo'];
        Log::info($codigo_banco_favorecido);

        $num = Str::length($codigo_banco_favorecido . '');

        for ($i = 21 + $num; $i <= 23; $i++) {
            $codigo_banco_favorecido = "0" . $codigo_banco_favorecido;
        }


        $agencia_mantedora = Str::replaceArray('-', [''], $user_data['agencia']);
        $num = Str::length($agencia_mantedora . '');
        Log::info($agencia_mantedora);
        for ($i = 24 + $num; $i <= 28; $i++) {
            $agencia_mantedora = "0" . $agencia_mantedora;
        }

        $digito_verificador_agencia = ' ';

        $numero_conta_corrente = Str::replaceArray('-', [''], $user_data['conta']);
        $num = Str::length($numero_conta_corrente . '');

        for ($i = 30 + $num; $i <= 41; $i++) {
            $numero_conta_corrente = "0" . $numero_conta_corrente;
        }

        $digito_verificador_conta = $user_data['digito'];
        $digito_verificador_agencia_conta = ' ';
        Log::info($pessoa->nome);
        $nome_favorecido = Str::limit($pessoa->nome, 30, '');
        $num = Str::length($nome_favorecido . '');

        for ($i = 44 + $num; $i <= 73; $i++) {
            $nome_favorecido = $nome_favorecido . ' ';
        }

        $seu_numero = $pessoa->cpfcnpj . Carbon::now()->format('dmY');
        // if (Str::length($seu_numero . '') == 11) {
        //     $seu_numero .= rand(0,99999);
        // }

        $num = Str::length($seu_numero . '');

        for ($i = 74 + $num; $i <= 93; $i++) {
            $seu_numero = $seu_numero . ' ';
        }

        $data_pagamento = Carbon::createFromFormat('Y-m-d', $this->data)->format('dmY');

        $tipo_moeda = 'BRL';

        $quantidade_moeda = $quantidade_moeda;

        $num = Str::length($quantidade_moeda . '');

        for ($i = 105 + $num; $i <= 119; $i++) {
            $quantidade_moeda = "0" . $quantidade_moeda;
        }

        $valor_pagamento = $valor_pagamento;

        $num = Str::length($valor_pagamento . '');

        for ($i = 120 + $num; $i <= 134; $i++) {
            $valor_pagamento = "0" . $valor_pagamento;
        }

        $nosso_numero = '';
        $num = Str::length($nosso_numero . '');

        for ($i = 135 + $num; $i <= 154; $i++) {
            $nosso_numero = " " . $nosso_numero;
        }

        $data_real_pagamento = Carbon::now()->format('dmY') . '';
        //verificar
        $num = Str::length($data_real_pagamento . '');

        for ($i = 155 + $num; $i <= 162; $i++) {
            $data_real_pagamento = "0" . $data_real_pagamento;
        }

        if ($user_data['codigo'] == "033") {
            $valor_real = "";
            //verificar

            for ($i = 163; $i <= 177; $i++) {
                // $valor_real = "0" . $valor_real;
                $valor_real = " " . $valor_real;
            }
        } else {
            $valor_real = $valor_pagamento;
            //verificar
            $num = Str::length($valor_real . '');

            for ($i = 163 + $num; $i <= 177; $i++) {
                // $valor_real = "0" . $valor_real;
                $valor_real = "0" . $valor_real;
            }
        }



        // Log::info(Str::length($valor_real.$data_real_pagamento));
        $informacao_2 = '';
        $num = Str::length($informacao_2 . '');

        for ($i = 178 + $num; $i <= 217; $i++) {
            $informacao_2 = " " . $informacao_2;
        }

        $codigo_finalidade_doc = $user_data['codigo'] == "033" ? '00' : '07';
        $codigo_finalidade_ted = $user_data['codigo'] == "033" ? '     ' : "00005";

        $codigo_finalidade_complementar = $user_data['codigo'] == "033" ? '  ' : 'CC';

        $uso_exclusivo = '   ';
        $aviso = '0';
        $ocorrencias = '          ';

        $registro_a = $banco . $lote_servico . $tipo_registro . $num_registro . $segmento . $tipo_movimentacao . $codigo_instrucao_movimento .
            $codigo_camera_centralizadora . $codigo_banco_favorecido . $agencia_mantedora . $digito_verificador_agencia . $numero_conta_corrente .
            $digito_verificador_conta . $digito_verificador_agencia_conta . $nome_favorecido . $seu_numero . $data_pagamento . $tipo_moeda .
            $quantidade_moeda . $valor_pagamento . $nosso_numero . $data_real_pagamento . $valor_real . $informacao_2 . $codigo_finalidade_doc .
            $codigo_finalidade_ted . $codigo_finalidade_complementar . $uso_exclusivo . $aviso . $ocorrencias;

        return $registro_a;
    }


    public function registro_detalhes_b_santander($user_data, $num_lote, $num_registro, $valor_pagamento)
    {
        $user = User::where('pessoa_id', '=', $user_data['profissional_id'])->first();
        // Log::info($user->pessoa()->first()->dadosbancario()->first()->agencia);
        $pessoa = $user->pessoa()->first();

        $banco = $this->santander['codigo'];
        $lote_servico = $num_lote . '';
        $num = Str::length($lote_servico . '');

        for ($i = 4 + $num; $i <= 7; $i++) {
            $lote_servico = "0" . $lote_servico;
        }

        $tipo_registro = "3";

        $num_registro = $num_registro;
        $num = Str::length($num_registro . '');

        for ($i = 9 + $num; $i <= 13; $i++) {
            $num_registro = "0" . $num_registro;
        }

        $segmento = 'B';

        $uso_exclusivo = '   ';

        $tipo_inscricao = '';
        if (Str::length($pessoa->cpfcnpj) == 11) {
            $tipo_inscricao = '1';
        } elseif (Str::length($pessoa->cpfcnpj) == 14) {
            $tipo_inscricao = '2';
        }

        $numero_inscricao = $pessoa->cpfcnpj;
        $num = Str::length($numero_inscricao . '');

        for ($i = 19 + $num; $i <= 32; $i++) {
            $numero_inscricao = "0" . $numero_inscricao;
        }


        $rua = '';
        $num = Str::length($rua . '');

        for ($i = 33 + $num; $i <= 62; $i++) {
            $rua = $rua . ' ';
        }

        $numero = '';
        $num = Str::length($numero . '');

        for ($i = 63 + $num; $i <= 67; $i++) {
            // $numero = "0" . $numero;
            $numero = " " . $numero;
        }

        $complemento = '';
        $num = Str::length($complemento . '');

        for ($i = 68 + $num; $i <= 82; $i++) {
            $complemento = $complemento . ' ';
        }


        $bairro = '';
        $num = Str::length($bairro . '');

        for ($i = 83 + $num; $i <= 97; $i++) {
            $bairro = $bairro . ' ';
        }


        $cidade = '';
        $num = Str::length($cidade . '');

        for ($i = 98 + $num; $i <= 117; $i++) {
            $cidade = $cidade . ' ';
        }


        $cep = '     ';
        $complemento_cep = '   ';

        $estado = "  ";
        $vencimento = Carbon::now()->format('dmY');
        $valor_documento = $valor_pagamento . '';
        $num = Str::length($valor_documento . '');

        for ($i = 136 + $num; $i <= 150; $i++) {
            $valor_documento = "0" . $valor_documento;
        }

        $valor_abatimento = '';
        $num = Str::length($valor_abatimento . '');

        for ($i = 151 + $num; $i <= 165; $i++) {
            $valor_abatimento = " " . $valor_abatimento;
        }


        $valor_desconto = '';
        $num = Str::length($valor_desconto . '');
        if ($user_data['codigo'] == "033") {

            for ($i = 166 + $num; $i <= 180; $i++) {
                $valor_desconto = "0" . $valor_desconto;
            }
        } else {
            for ($i = 166 + $num; $i <= 180; $i++) {
                $valor_desconto = " " . $valor_desconto;
            }
        }


        $valor_mora = '';
        $num = Str::length($valor_mora . '');

        for ($i = 181 + $num; $i <= 195; $i++) {
            $valor_mora = " " . $valor_mora;
        }

        $valor_multa = '';
        $num = Str::length($valor_multa . '');

        if ($user_data['codigo'] == "033") {

            for ($i = 196 + $num; $i <= 210; $i++) {
                $valor_multa = "0" . $valor_multa;
            }
        } else {
            for ($i = 196 + $num; $i <= 210; $i++) {
                $valor_multa = " " . $valor_multa;
            }
        }

        $cod_documento_favorecido = $user_data['codigo'] == "033" ? '' : "0000";
        $num = Str::length($cod_documento_favorecido . '');

        for ($i = 211 + $num; $i <= 225; $i++) {
            $cod_documento_favorecido = $cod_documento_favorecido . ' ';
        }

        $historico_credito = $user_data['codigo'] == "033" ? '0200' : "0183";
        // $num = Str::length($historico_credito . '');

        // for ($i = 226 + $num; $i <= 225; $i++) {
        //     $cod_documento_favorecido = $cod_documento_favorecido.' ';
        // }

        $aviso = $user_data['codigo'] == "033" ? '7' : "0";

        $uso_excluviso_siagep = $user_data['codigo'] == "033" ? '' : ' N';
        $num = Str::length($uso_excluviso_siagep . '');

        for ($i = 231 + $num; $i <= 231; $i++) {
            $uso_excluviso_siagep = ' ' . $uso_excluviso_siagep;
        }


        $codigo_isbp = '';
        $num = Str::length($codigo_isbp . '');

        for ($i = 232 + $num; $i <= 240; $i++) {
            $codigo_isbp = ' ' . $codigo_isbp;
        }

        $registro_b = $banco . $lote_servico . $tipo_registro . $num_registro . $segmento . $uso_exclusivo . $tipo_inscricao . $numero_inscricao . $rua . $numero . $complemento .
            $bairro . $cidade . $cep . $complemento_cep . $estado . $vencimento . $valor_documento . $valor_abatimento . $valor_desconto . $valor_mora . $valor_multa . $cod_documento_favorecido . $historico_credito . $aviso . $uso_excluviso_siagep . $codigo_isbp;

        return $registro_b;
    }

    public function registro_detalhes_b_sicred($user_data, $num_lote, $num_registro, $valor_documento)
    {
        $user = User::where('pessoa_id', '=', $user_data['profissional_id'])->first();
        // Log::info($user->pessoa()->first()->dadosbancario()->first()->agencia);
        $pessoa = $user->pessoa()->first();

        $banco = $this->sicred['codigo'];
        $lote_servico = $num_lote . '';
        $num = Str::length($lote_servico . '');

        for ($i = 4 + $num; $i <= 7; $i++) {
            $lote_servico = "0" . $lote_servico;
        }

        $tipo_registro = "3";

        $num_registro = $num_registro;
        $num = Str::length($num_registro . '');

        for ($i = 9 + $num; $i <= 13; $i++) {
            $num_registro = "0" . $num_registro;
        }

        $segmento = 'B';

        $uso_exclusivo = '   ';

        $tipo_inscricao = '';
        if (Str::length($pessoa->cpfcnpj) == 11) {
            $tipo_inscricao = '1';
        } elseif (Str::length($pessoa->cpfcnpj) == 14) {
            $tipo_inscricao = '2';
        }

        $numero_inscricao = $pessoa->cpfcnpj;
        $num = Str::length($numero_inscricao . '');

        for ($i = 19 + $num; $i <= 32; $i++) {
            $numero_inscricao = "0" . $numero_inscricao;
        }


        $rua = '';
        $num = Str::length($rua . '');

        for ($i = 33 + $num; $i <= 62; $i++) {
            $rua = $rua . ' ';
        }

        $numero = '';
        $num = Str::length($numero . '');

        for ($i = 63 + $num; $i <= 67; $i++) {
            $numero = "0" . $numero;
            // $numero = " " . $numero;
        }

        $complemento = '';
        $num = Str::length($complemento . '');

        for ($i = 68 + $num; $i <= 82; $i++) {
            $complemento = $complemento . ' ';
        }


        $bairro = '';
        $num = Str::length($bairro . '');

        for ($i = 83 + $num; $i <= 97; $i++) {
            $bairro = $bairro . ' ';
        }


        $cidade = '';
        $num = Str::length($cidade . '');

        for ($i = 98 + $num; $i <= 117; $i++) {
            $cidade = $cidade . ' ';
        }


        $cep = '     ';
        $complemento_cep = '   ';

        $estado = "  ";
        // $vencimento=Carbon::now()->addDays(3)->format('dmY');
        $pagamento = $user_data['codigo'] == '748' ? '' : $valor_documento;
        $num = Str::length($pagamento . '');

        for ($i = 128 + $num; $i <= 210; $i++) {
            $pagamento = '0' . $pagamento;
        }


        $cod_documento_favorecido = $user_data['codigo'] == "748" ? '' : "0000";;
        $num = Str::length($cod_documento_favorecido . '');

        for ($i = 211 + $num; $i <= 225; $i++) {
            $cod_documento_favorecido = $cod_documento_favorecido . ' ';
        }

        $aviso = '6';

        $uso_excluviso_siagep = '';
        $num = Str::length($uso_excluviso_siagep . '');

        for ($i = 227 + $num; $i <= 232; $i++) {
            $uso_excluviso_siagep = '0' . $uso_excluviso_siagep;
        }


        $codigo_isbp = '';
        $num = Str::length($codigo_isbp . '');

        for ($i = 233 + $num; $i <= 240; $i++) {
            $codigo_isbp = '0' . $codigo_isbp;
        }

        $registro_b = $banco . $lote_servico . $tipo_registro . $num_registro . $segmento . $uso_exclusivo . $tipo_inscricao . $numero_inscricao . $rua . $numero . $complemento .
            $bairro . $cidade . $cep . $complemento_cep . $estado . $pagamento . $cod_documento_favorecido . $aviso . $uso_excluviso_siagep . $codigo_isbp;

        return $registro_b;
    }






    public function trailer_lote_sicred($quantidade_registros, $soma_valor, $soma_quantidade_moeda, $codigo)
    {
        $banco = $this->sicred['codigo'];
        $lote = "0001";

        $tipo_registro = "5";

        $uso_exclusivo = "";

        for ($i = 9; $i <= 17; $i++) {
            $uso_exclusivo .= " ";
        }



        $quant_registros_t = $quantidade_registros . '';

        $num = Str::length($quant_registros_t);

        for ($i = 18 + $num; $i <= 23; $i++) {
            $quant_registros_t = "0" . $quant_registros_t;
        }

        $soma_valor = $soma_valor . '';

        $num = Str::length($soma_valor);

        for ($i = 24 + $num; $i <= 41; $i++) {
            $soma_valor = "0" . $soma_valor;
        }

        $soma_quantidade_moeda = $soma_quantidade_moeda . '';

        $num = Str::length($soma_quantidade_moeda);

        for ($i = 42 + $num; $i <= 59; $i++) {
            $soma_quantidade_moeda = "0" . $soma_quantidade_moeda;
        }


        $numero_aviso_debito = $codigo == "748" ? '' : '000000';

        $num = Str::length($numero_aviso_debito);

        for ($i = 60 + $num; $i <= 65; $i++) {
            $numero_aviso_debito = "0" . $numero_aviso_debito;
        }

        $uso_exclusivo_2 = "";

        for ($i = 66; $i <= 230; $i++) {
            $uso_exclusivo_2 .= " ";
        }

        $ocorrencias = '          ';



        $trailer_lote = $banco . $lote . $tipo_registro . $uso_exclusivo . $quant_registros_t . $soma_valor . $soma_quantidade_moeda .
            $numero_aviso_debito . $uso_exclusivo_2 . $ocorrencias;
        return $trailer_lote;
    }


    public function trailer_lote_santander($quantidade_registros, $soma_valor, $soma_quantidade_moeda, $codigo)
    {
        $banco = $this->santander['codigo'];
        $lote = "0001";

        $tipo_registro = "5";

        $uso_exclusivo = "";

        for ($i = 9; $i <= 17; $i++) {
            $uso_exclusivo .= " ";
        }



        $quant_registros_t = $quantidade_registros . '';

        $num = Str::length($quant_registros_t);

        for ($i = 18 + $num; $i <= 23; $i++) {
            $quant_registros_t = "0" . $quant_registros_t;
        }

        $soma_valor = $soma_valor . '';

        $num = Str::length($soma_valor);

        for ($i = 24 + $num; $i <= 41; $i++) {
            $soma_valor = "0" . $soma_valor;
        }

        $soma_quantidade_moeda = $soma_quantidade_moeda . '';

        $num = Str::length($soma_quantidade_moeda);

        for ($i = 42 + $num; $i <= 59; $i++) {
            $soma_quantidade_moeda = "0" . $soma_quantidade_moeda;
        }


        $numero_aviso_debito = $codigo == "033" ? '' : '000000';

        $num = Str::length($numero_aviso_debito);

        for ($i = 60 + $num; $i <= 65; $i++) {
            $numero_aviso_debito = " " . $numero_aviso_debito;
        }

        $uso_exclusivo_2 = "";

        for ($i = 66; $i <= 230; $i++) {
            $uso_exclusivo_2 .= " ";
        }

        $ocorrencias = '          ';



        $trailer_lote = $banco . $lote . $tipo_registro . $uso_exclusivo . $quant_registros_t . $soma_valor . $soma_quantidade_moeda .
            $numero_aviso_debito . $uso_exclusivo_2 . $ocorrencias;
        return $trailer_lote;
    }
}
