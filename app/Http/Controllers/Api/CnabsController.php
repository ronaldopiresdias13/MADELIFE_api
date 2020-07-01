<?php

namespace App\Http\Controllers\Api;

use App\Cnab;
use App\Cnabdetalhea;
use App\Cnabdetalheb;
use App\Cnabheaderarquivo;
use App\Cnabheaderarquivoheaderlote;
use App\Cnabheaderarquivotrailerlote;
use App\Cnabheaderlote;
use App\Cnabheaderlotedetalhea;
use App\Cnabheaderlotedetalheb;
use App\Cnabsantander;
use App\Cnabtrailerarquivo;
use App\Cnabtrailerlote;
use App\Http\Controllers\Controller;
use App\Pagamentopessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CnabsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itens = new Cnab();

        if ($request->commands) {
            $request = json_decode($request->commands, true);
        }

        if ($request['where']) {
            foreach ($request['where'] as $key => $where) {
                if ($key == 0) {
                    $itens = Cnab::where(
                        ($where['coluna']) ? $where['coluna'] : 'id',
                        ($where['expressao']) ? $where['expressao'] : 'like',
                        ($where['valor']) ? $where['valor'] : '%'
                    );
                } else {
                    $itens->where(
                        ($where['coluna']) ? $where['coluna'] : 'id',
                        ($where['expressao']) ? $where['expressao'] : 'like',
                        ($where['valor']) ? $where['valor'] : '%'
                    );
                }
            }
        } else {
            $itens = Cnab::where('id', 'like', '%');
        }

        if ($request['order']) {
            foreach ($request['order'] as $key => $order) {
                $itens->orderBy(
                    ($order['coluna']) ? $order['coluna'] : 'id',
                    ($order['tipo']) ? $order['tipo'] : 'asc'
                );
            }
        }

        $itens = $itens->get();

        if ($request['adicionais']) {
            foreach ($itens as $key => $iten) {
                foreach ($request['adicionais'] as $key => $adicional) {
                    if (is_string($adicional)) {
                        $iten[$adicional];
                    } else {
                        $iten2 = $iten;
                        foreach ($adicional as $key => $a) {
                            if ($key == 0) {
                                if ($iten[0] == null) {
                                    $iten2 = $iten[$a];
                                } else {
                                    foreach ($iten as $key => $i) {
                                        $i[$a];
                                    }
                                }
                            } else {
                                if ($iten2[0] == null) {
                                    $iten2 = $iten2[$a];
                                } else {
                                    foreach ($iten2 as $key => $i) {
                                        $i[$a];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $itens;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request = $request->getContent();
        $request = json_decode($request, true);

        DB::transaction(function () use ($request) {
            $cnab = Cnab::create([
                'empresa_id' => $request['empresa_id'],
                'data'       => $request['data'],
                'observacao' => $request['observacao'] ? $request['observacao'] : "",
                'status'     => 0
            ]);
            if ($request['cnabSantanderFolha240']) {
                $cnabsantander = Cnabsantander::create([
                    'cnab_id' => $cnab->id,
                    'tipo'    => $request['cnabSantanderFolha240']['tipo'],
                    'cnabheaderarquivo_id' => Cnabheaderarquivo::create([
                        'codigobanco'              => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['codigobanco'],
                        'loteservico'              => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['loteservico'],
                        'tiporegistro'             => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['tiporegistro'],
                        'filler'                   => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['filler'],
                        'tipoinscemp'              => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['tipoinscemp'],
                        'numinscemp'               => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['numinscemp'],
                        'codigoconvbanco'          => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['codigoconvbanco'],
                        'agenciaconta'             => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['agenciaconta'],
                        'digitoagencia'            => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['digitoagencia'],
                        'numcontacorrente'         => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['numcontacorrente'],
                        'digitoconta'              => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['digitoconta'],
                        'digitoagenciaconta'       => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['digitoagenciaconta'],
                        'nomeempresa'              => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['nomeempresa'],
                        'nomebanco'                => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['nomebanco'],
                        'filler2'                  => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['filler2'],
                        'codremessa'               => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['codremessa'],
                        'dataarquivo'              => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['dataarquivo'],
                        'horaarquivo'              => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['horaarquivo'],
                        'numseqarquivo'            => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['numseqarquivo'],
                        'numversaolayout'          => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['numversaolayout'],
                        'densidadegravacaoarquivo' => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['densidadegravacaoarquivo'],
                        'reservadobanco'           => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['reservadobanco'],
                        'usobanco'                 => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['usobanco'],
                        'usoempresa'               => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['usoempresa'],
                        'filler3'                  => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['filler3'],
                        'ocorrenciasretorno'       => $request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['ocorrenciasretorno'],
                    ])->id,
                    'cnabtrailerarquivo_id' => Cnabtrailerarquivo::create([
                        'codigobanco'            => $request['cnabSantanderFolha240']['cnabSantanderFolha240TrailerArquivo']['codigobanco'],
                        'loteservico'            => $request['cnabSantanderFolha240']['cnabSantanderFolha240TrailerArquivo']['loteservico'],
                        'tiporegistro'           => $request['cnabSantanderFolha240']['cnabSantanderFolha240TrailerArquivo']['tiporegistro'],
                        'filler'                 => $request['cnabSantanderFolha240']['cnabSantanderFolha240TrailerArquivo']['filler'],
                        'quantidadelotesarquivo' => $request['cnabSantanderFolha240']['cnabSantanderFolha240TrailerArquivo']['quantidadelotesarquivo'],
                        'quantidaderegarquivo'   => $request['cnabSantanderFolha240']['cnabSantanderFolha240TrailerArquivo']['quantidaderegarquivo'],
                        'filler2'                => $request['cnabSantanderFolha240']['cnabSantanderFolha240TrailerArquivo']['filler2']
                    ])->id
                ]);

                foreach ($request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['cnabSantanderFolha240HeaderLote'] as $key => $cnabHeaderLote) {
                    // dd($cnabHeaderLote['ocorrenciasretorno']);
                    $cnabheaderarquivoheaderlote = Cnabheaderarquivoheaderlote::create([
                        'cnabheaderarquivo_id' => $cnabsantander->cnabheaderarquivo_id,
                        'cnabheaderlote_id'    => Cnabheaderlote::create([
                            'codigobanco'        => $cnabHeaderLote['codigobanco'],
                            'loteservico'        => $cnabHeaderLote['loteservico'],
                            'tiporegistro'       => $cnabHeaderLote['tiporegistro'],
                            'tipooperacao'       => $cnabHeaderLote['tipooperacao'],
                            'tiposervico'        => $cnabHeaderLote['tiposervico'],
                            'formalancamento'    => $cnabHeaderLote['formalancamento'],
                            'numerolote'         => $cnabHeaderLote['numerolote'],
                            'filler'             => $cnabHeaderLote['filler'],
                            'tipoinscemp'        => $cnabHeaderLote['tipoinscemp'],
                            'numinscemp'         => $cnabHeaderLote['numinscemp'],
                            'codigoconvbanco'    => $cnabHeaderLote['codigoconvbanco'],
                            'agenciaconta'       => $cnabHeaderLote['agenciaconta'],
                            'digitoagencia'      => $cnabHeaderLote['digitoagencia'],
                            'numcontacorrente'   => $cnabHeaderLote['numcontacorrente'],
                            'digitoconta'        => $cnabHeaderLote['digitoconta'],
                            'digitoagenciaconta' => $cnabHeaderLote['digitoagenciaconta'],
                            'nomeempresa'        => $cnabHeaderLote['nomeempresa'],
                            'filler2'            => $cnabHeaderLote['filler2'],
                            'endereco'           => $cnabHeaderLote['endereco'],
                            'numero'             => $cnabHeaderLote['numero'],
                            'compendereco'       => $cnabHeaderLote['compendereco'],
                            'cidade'             => $cnabHeaderLote['cidade'],
                            'cep'                => $cnabHeaderLote['cep'],
                            'complcep'           => $cnabHeaderLote['complcep'],
                            'uf'                 => $cnabHeaderLote['uf'],
                            'filler3'            => $cnabHeaderLote['filler3'],
                            'ocorrenciasretorno' => $cnabHeaderLote['ocorrenciasretorno'],
                        ])->id,
                    ]);

                    foreach ($cnabHeaderLote['cnabSantanderFolha240DetalheA'] as $key => $cnabDetalheA) {
                        $cnabheaderlotedetalhea = Cnabheaderlotedetalhea::create([
                            'cnabheaderlote_id' => $cnabheaderarquivoheaderlote->cnabheaderlote_id,
                            'cnabdetalhea_id'   => Cnabdetalhea::create([
                                'codigobanco'              => $cnabDetalheA['codigobanco'],
                                'loteservico'              => $cnabDetalheA['loteservico'],
                                'tiporegistro'             => $cnabDetalheA['tiporegistro'],
                                'numeroseqregistrolote'    => $cnabDetalheA['numeroseqregistrolote'],
                                'codigosegregistrodetalhe' => $cnabDetalheA['codigosegregistrodetalhe'],
                                'tipomovimento'            => $cnabDetalheA['tipomovimento'],
                                'codigoinstmovimento'      => $cnabDetalheA['codigoinstmovimento'],
                                'codigocamaracomp'         => $cnabDetalheA['codigocamaracomp'],
                                'codigobancofavo'          => $cnabDetalheA['codigobancofavo'],
                                'codigoagenciafavo'        => $cnabDetalheA['codigoagenciafavo'],
                                'digitoagenciafavo'        => $cnabDetalheA['digitoagenciafavo'],
                                'ccfavorecido'             => $cnabDetalheA['ccfavorecido'],
                                'digitoconta'              => $cnabDetalheA['digitoconta'],
                                'digitoagenciaconta'       => $cnabDetalheA['digitoagenciaconta'],
                                'nome'                     => $cnabDetalheA['nome'],
                                'numerocliente'            => $cnabDetalheA['numerocliente'],
                                'datapagamento'            => $cnabDetalheA['datapagamento'],
                                'tipomoeda'                => $cnabDetalheA['tipomoeda'],
                                'quantidademoeda'          => $cnabDetalheA['quantidademoeda'],
                                'valorpagamento'           => $cnabDetalheA['valorpagamento'],
                                'numerodocbanco'           => $cnabDetalheA['numerodocbanco'],
                                'datarealpag'              => $cnabDetalheA['datarealpag'],
                                'valorrealpag'             => $cnabDetalheA['valorrealpag'],
                                'outrasinfo'               => $cnabDetalheA['outrasinfo'],
                                'finalidadedoc'            => $cnabDetalheA['finalidadedoc'],
                                'finalidadeted'            => $cnabDetalheA['finalidadeted'],
                                'codigocomplementar'       => $cnabDetalheA['codigocomplementar'],
                                'filler'                   => $cnabDetalheA['filler'],
                                'emissaofavorecido'        => $cnabDetalheA['emissaofavorecido'],
                                'ocorrenciasretorno'       => $cnabDetalheA['ocorrenciasretorno'],
                            ])->id
                        ]);
                    }
                    foreach ($cnabHeaderLote['cnabSantanderFolha240DetalheB'] as $key => $cnabDetalheB) {
                        $cnabheaderlotedetalheb = Cnabheaderlotedetalheb::create([
                            'cnabheaderlote_id' => $cnabheaderarquivoheaderlote->cnabheaderlote_id,
                            'cnabdetalheb_id'   => Cnabdetalheb::create([
                                'codigobanco'              => $cnabDetalheB['codigobanco'],
                                'loteservico'              => $cnabDetalheB['loteservico'],
                                'tiporegistro'             => $cnabDetalheB['tiporegistro'],
                                'numeroseqregistrolote'    => $cnabDetalheB['numeroseqregistrolote'],
                                'codigosegregistrodetalhe' => $cnabDetalheB['codigosegregistrodetalhe'],
                                'filler'                   => $cnabDetalheB['filler'],
                                'tipoinscfavorecido'       => $cnabDetalheB['tipoinscfavorecido'],
                                'cpfcnpjfavorecido'        => $cnabDetalheB['cpfcnpjfavorecido'],
                                'logradourofavorecido'     => $cnabDetalheB['logradourofavorecido'],
                                'numerolocalfavorecido'    => $cnabDetalheB['numerolocalfavorecido'],
                                'complocalfavorecido'      => $cnabDetalheB['complocalfavorecido'],
                                'bairrofavorecido'         => $cnabDetalheB['bairrofavorecido'],
                                'cidadefavorecido'         => $cnabDetalheB['cidadefavorecido'],
                                'cepfavorecido'            => $cnabDetalheB['cepfavorecido'],
                                'estadofavorecido'         => $cnabDetalheB['estadofavorecido'],
                                'datavencimento'           => $cnabDetalheB['datavencimento'],
                                'valordocumento'           => $cnabDetalheB['valordocumento'],
                                'valorabatimento'          => $cnabDetalheB['valorabatimento'],
                                'valordesconto'            => $cnabDetalheB['valordesconto'],
                                'valormora'                => $cnabDetalheB['valormora'],
                                'valormulta'               => $cnabDetalheB['valormulta'],
                                'horarioenvio'             => $cnabDetalheB['horarioenvio'],
                                'filler2'                  => $cnabDetalheB['filler2'],
                                'codigohistcredito'        => $cnabDetalheB['codigohistcredito'],
                                'ocorrenciasretorno'       => $cnabDetalheB['ocorrenciasretorno'],
                                'filler3'                  => $cnabDetalheB['filler3'],
                                'tedfinanceira'            => $cnabDetalheB['tedfinanceira'],
                                'identificacaospb'         => $cnabDetalheB['identificacaospb'],
                            ])->id
                        ]);
                    }
                }
                foreach ($request['cnabSantanderFolha240']['cnabSantanderFolha240HeaderArquivo']['cnabSantanderFolha240TrailerLote'] as $key => $cnabTrailerLote) {
                    $cnabheaderarquivotrailerlote = Cnabheaderarquivotrailerlote::create([
                        'cnabheaderarquivo_id' => $cnabsantander->cnabheaderarquivo_id,
                        'cnabtrailerlote_id'   => Cnabtrailerlote::create([
                            'codigobanco'         => $cnabTrailerLote['codigobanco'],
                            'loteservico'         => $cnabTrailerLote['loteservico'],
                            'tiporegistro'        => $cnabTrailerLote['tiporegistro'],
                            'filler'              => $cnabTrailerLote['filler'],
                            'quantidadereglote'   => $cnabTrailerLote['quantidadereglote'],
                            'somatoriavalores'    => $cnabTrailerLote['somatoriavalores'],
                            'somatoriaquantmoeda' => $cnabTrailerLote['somatoriaquantmoeda'],
                            'numeroavisodebito'   => $cnabTrailerLote['numeroavisodebito'],
                            'filler2'             => $cnabTrailerLote['filler2'],
                            'ocorrenciasretorno'  => $cnabTrailerLote['ocorrenciasretorno'],
                        ])->id
                    ]);
                }
            }
            if ($request['cnabSantanderFornecedores240']) {
                // dd('' . $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['filler']);
                // dd('' . $request->cnabSantanderFornecedores240->cnabSantanderFornecedores240HeaderArquivo->filler);
                $cnabsantander = Cnabsantander::create([
                    'cnab_id' => $cnab->id,
                    'tipo'    => $request['cnabSantanderFornecedores240']['tipo'],
                    'cnabheaderarquivo_id' => Cnabheaderarquivo::create([
                        'codigobanco'              => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['codigobanco'],
                        'loteservico'              => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['loteservico'],
                        'tiporegistro'             => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['tiporegistro'],
                        'filler'                   => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['filler'],
                        'tipoinscemp'              => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['tipoinscemp'],
                        'numinscemp'               => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['numinscemp'],
                        'codigoconvbanco'          => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['codigoconvbanco'],
                        'agenciaconta'             => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['agenciaconta'],
                        'digitoagencia'            => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['digitoagencia'],
                        'numcontacorrente'         => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['numcontacorrente'],
                        'digitoconta'              => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['digitoconta'],
                        'digitoagenciaconta'       => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['digitoagenciaconta'],
                        'nomeempresa'              => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['nomeempresa'],
                        'nomebanco'                => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['nomebanco'],
                        'filler2'                  => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['filler2'],
                        'codremessa'               => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['codremessa'],
                        'dataarquivo'              => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['dataarquivo'],
                        'horaarquivo'              => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['horaarquivo'],
                        'numseqarquivo'            => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['numseqarquivo'],
                        'numversaolayout'          => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['numversaolayout'],
                        'densidadegravacaoarquivo' => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['densidadegravacaoarquivo'],
                        'reservadobanco'           => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['reservadobanco'],
                        'usobanco'                 => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['usobanco'],
                        'usoempresa'               => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['usoempresa'],
                        'filler3'                  => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['filler3'],
                        'ocorrenciasretorno'       => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['ocorrenciasretorno'],
                    ])->id,
                    'cnabtrailerarquivo_id' => Cnabtrailerarquivo::create([
                        'codigobanco'            => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240TrailerArquivo']['codigobanco'],
                        'loteservico'            => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240TrailerArquivo']['loteservico'],
                        'tiporegistro'           => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240TrailerArquivo']['tiporegistro'],
                        'filler'                 => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240TrailerArquivo']['filler'],
                        'quantidadelotesarquivo' => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240TrailerArquivo']['quantidadelotesarquivo'],
                        'quantidaderegarquivo'   => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240TrailerArquivo']['quantidaderegarquivo'],
                        'filler2'                => $request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240TrailerArquivo']['filler2']
                    ])->id
                ]);

                foreach ($request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['cnabSantanderFornecedores240HeaderLote'] as $key => $cnabHeaderLote) {
                    // dd($cnabHeaderLote['ocorrenciasretorno']);
                    $cnabheaderarquivoheaderlote = Cnabheaderarquivoheaderlote::create([
                        'cnabheaderarquivo_id' => $cnabsantander->cnabheaderarquivo_id,
                        'cnabheaderlote_id'    => Cnabheaderlote::create([
                            'codigobanco'        => $cnabHeaderLote['codigobanco'],
                            'loteservico'        => $cnabHeaderLote['loteservico'],
                            'tiporegistro'       => $cnabHeaderLote['tiporegistro'],
                            'tipooperacao'       => $cnabHeaderLote['tipooperacao'],
                            'tiposervico'        => $cnabHeaderLote['tiposervico'],
                            'formalancamento'    => $cnabHeaderLote['formalancamento'],
                            'numerolote'         => $cnabHeaderLote['numerolote'],
                            'filler'             => $cnabHeaderLote['filler'],
                            'tipoinscemp'        => $cnabHeaderLote['tipoinscemp'],
                            'numinscemp'         => $cnabHeaderLote['numinscemp'],
                            'codigoconvbanco'    => $cnabHeaderLote['codigoconvbanco'],
                            'agenciaconta'       => $cnabHeaderLote['agenciaconta'],
                            'digitoagencia'      => $cnabHeaderLote['digitoagencia'],
                            'numcontacorrente'   => $cnabHeaderLote['numcontacorrente'],
                            'digitoconta'        => $cnabHeaderLote['digitoconta'],
                            'digitoagenciaconta' => $cnabHeaderLote['digitoagenciaconta'],
                            'nomeempresa'        => $cnabHeaderLote['nomeempresa'],
                            'filler2'            => $cnabHeaderLote['filler2'],
                            'endereco'           => $cnabHeaderLote['endereco'],
                            'numero'             => $cnabHeaderLote['numero'],
                            'compendereco'       => $cnabHeaderLote['compendereco'],
                            'cidade'             => $cnabHeaderLote['cidade'],
                            'cep'                => $cnabHeaderLote['cep'],
                            'complcep'           => $cnabHeaderLote['complcep'],
                            'uf'                 => $cnabHeaderLote['uf'],
                            'filler3'            => $cnabHeaderLote['filler3'],
                            'ocorrenciasretorno' => $cnabHeaderLote['ocorrenciasretorno'],
                        ])->id,
                    ]);

                    foreach ($cnabHeaderLote['cnabSantanderFornecedores240DetalheA'] as $key => $cnabDetalheA) {
                        $cnabheaderlotedetalhea = Cnabheaderlotedetalhea::create([
                            'cnabheaderlote_id' => $cnabheaderarquivoheaderlote->cnabheaderlote_id,
                            'cnabdetalhea_id'   => Cnabdetalhea::create([
                                'codigobanco'              => $cnabDetalheA['codigobanco'],
                                'loteservico'              => $cnabDetalheA['loteservico'],
                                'tiporegistro'             => $cnabDetalheA['tiporegistro'],
                                'numeroseqregistrolote'    => $cnabDetalheA['numeroseqregistrolote'],
                                'codigosegregistrodetalhe' => $cnabDetalheA['codigosegregistrodetalhe'],
                                'tipomovimento'            => $cnabDetalheA['tipomovimento'],
                                'codigoinstmovimento'      => $cnabDetalheA['codigoinstmovimento'],
                                'codigocamaracomp'         => $cnabDetalheA['codigocamaracomp'],
                                'codigobancofavo'          => $cnabDetalheA['codigobancofavo'],
                                'codigoagenciafavo'        => $cnabDetalheA['codigoagenciafavo'],
                                'digitoagenciafavo'        => $cnabDetalheA['digitoagenciafavo'],
                                'ccfavorecido'             => $cnabDetalheA['ccfavorecido'],
                                'digitoconta'              => $cnabDetalheA['digitoconta'],
                                'digitoagenciaconta'       => $cnabDetalheA['digitoagenciaconta'],
                                'nome'                     => $cnabDetalheA['nome'],
                                'numerocliente'            => $cnabDetalheA['numerocliente'],
                                'datapagamento'            => $cnabDetalheA['datapagamento'],
                                'tipomoeda'                => $cnabDetalheA['tipomoeda'],
                                'quantidademoeda'          => $cnabDetalheA['quantidademoeda'],
                                'valorpagamento'           => $cnabDetalheA['valorpagamento'],
                                'numerodocbanco'           => $cnabDetalheA['numerodocbanco'],
                                'datarealpag'              => $cnabDetalheA['datarealpag'],
                                'valorrealpag'             => $cnabDetalheA['valorrealpag'],
                                'outrasinfo'               => $cnabDetalheA['outrasinfo'],
                                'finalidadedoc'            => $cnabDetalheA['finalidadedoc'],
                                'finalidadeted'            => $cnabDetalheA['finalidadeted'],
                                'codigocomplementar'       => $cnabDetalheA['codigocomplementar'],
                                'filler'                   => $cnabDetalheA['filler'],
                                'emissaofavorecido'        => $cnabDetalheA['emissaofavorecido'],
                                'ocorrenciasretorno'       => $cnabDetalheA['ocorrenciasretorno'],
                            ])->id
                        ]);
                    }
                    foreach ($cnabHeaderLote['cnabSantanderFornecedores240DetalheB'] as $key => $cnabDetalheB) {
                        $cnabheaderlotedetalheb = Cnabheaderlotedetalheb::create([
                            'cnabheaderlote_id' => $cnabheaderarquivoheaderlote->cnabheaderlote_id,
                            'cnabdetalheb_id'   => Cnabdetalheb::create([
                                'codigobanco'              => $cnabDetalheB['codigobanco'],
                                'loteservico'              => $cnabDetalheB['loteservico'],
                                'tiporegistro'             => $cnabDetalheB['tiporegistro'],
                                'numeroseqregistrolote'    => $cnabDetalheB['numeroseqregistrolote'],
                                'codigosegregistrodetalhe' => $cnabDetalheB['codigosegregistrodetalhe'],
                                'filler'                   => $cnabDetalheB['filler'],
                                'tipoinscfavorecido'       => $cnabDetalheB['tipoinscfavorecido'],
                                'cpfcnpjfavorecido'        => $cnabDetalheB['cpfcnpjfavorecido'],
                                'logradourofavorecido'     => $cnabDetalheB['logradourofavorecido'],
                                'numerolocalfavorecido'    => $cnabDetalheB['numerolocalfavorecido'],
                                'complocalfavorecido'      => $cnabDetalheB['complocalfavorecido'],
                                'bairrofavorecido'         => $cnabDetalheB['bairrofavorecido'],
                                'cidadefavorecido'         => $cnabDetalheB['cidadefavorecido'],
                                'cepfavorecido'            => $cnabDetalheB['cepfavorecido'],
                                'estadofavorecido'         => $cnabDetalheB['estadofavorecido'],
                                'datavencimento'           => $cnabDetalheB['datavencimento'],
                                'valordocumento'           => $cnabDetalheB['valordocumento'],
                                'valorabatimento'          => $cnabDetalheB['valorabatimento'],
                                'valordesconto'            => $cnabDetalheB['valordesconto'],
                                'valormora'                => $cnabDetalheB['valormora'],
                                'valormulta'               => $cnabDetalheB['valormulta'],
                                'horarioenvio'             => $cnabDetalheB['horarioenvio'],
                                'filler2'                  => $cnabDetalheB['filler2'],
                                'codigohistcredito'        => $cnabDetalheB['codigohistcredito'],
                                'ocorrenciasretorno'       => $cnabDetalheB['ocorrenciasretorno'],
                                'filler3'                  => $cnabDetalheB['filler3'],
                                'tedfinanceira'            => $cnabDetalheB['tedfinanceira'],
                                'identificacaospb'         => $cnabDetalheB['identificacaospb'],
                            ])->id
                        ]);
                    }
                }
                foreach ($request['cnabSantanderFornecedores240']['cnabSantanderFornecedores240HeaderArquivo']['cnabSantanderFornecedores240TrailerLote'] as $key => $cnabTrailerLote) {
                    $cnabheaderarquivotrailerlote = Cnabheaderarquivotrailerlote::create([
                        'cnabheaderarquivo_id' => $cnabsantander->cnabheaderarquivo_id,
                        'cnabtrailerlote_id'   => Cnabtrailerlote::create([
                            'codigobanco'         => $cnabTrailerLote['codigobanco'],
                            'loteservico'         => $cnabTrailerLote['loteservico'],
                            'tiporegistro'        => $cnabTrailerLote['tiporegistro'],
                            'filler'              => $cnabTrailerLote['filler'],
                            'quantidadereglote'   => $cnabTrailerLote['quantidadereglote'],
                            'somatoriavalores'    => $cnabTrailerLote['somatoriavalores'],
                            'somatoriaquantmoeda' => $cnabTrailerLote['somatoriaquantmoeda'],
                            'numeroavisodebito'   => $cnabTrailerLote['numeroavisodebito'],
                            'filler2'             => $cnabTrailerLote['filler2'],
                            'ocorrenciasretorno'  => $cnabTrailerLote['ocorrenciasretorno'],
                        ])->id
                    ]);
                }
            }
            if ($request['pagementopessoas']) {
                foreach ($request['pagementopessoas'] as $key => $pagamento) {
                    $pagamentopessoas = Pagamentopessoa::find($pagamento);
                    $pagamentopessoas->status = true;
                    $pagamentopessoas->save();
                }
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $tipo
     * @param  \App\Cnab  $cnab
     * @return \Illuminate\Http\Response
     */
    public function show(Cnab $cnab, string $tipo)
    {
        // $cnabsantander = Cnabsantander::firstWhere('tipo', $tipo);
        // $cnabsantander->cnabheaderarquivo->cnabtrailerlotes;
        // $cnabsantander->cnabtrailerarquivo;
        // foreach ($cnabsantander->cnabheaderarquivo->cnabheaderlotes as $key => $cnabheaderlote) {
        //     $cnabheaderlote->cnabdetalheas;
        //     $cnabheaderlote->cnabdetalhebs;
        // }

        // $texto = '';
        // $cnabsantander->cnabheaderarquivo;
        // $cnabsantander->cnabheaderarquivo;
        // $cnabsantander->cnabheaderarquivo;
        // $cnabsantander->cnabheaderarquivo;
        // $cnabsantander->cnabheaderarquivo;

        // Storage::disk('public')->put('\\cnabs\\' . $cnab->id . '\\' . $tipo . ".txt", 'Contents');

        // dd('Pronto');

        // $myfile = fopen(storage_path('app\\public') . '\\cnabs\\' . $cnab->id . '\\' . $tipo . ".txt", "w") or die("Unable to open file!");
        // $myfile = fopen(storage_path('app\\public') . '\\' . $tipo . ".txt", "w") or die("Unable to open file!");
        // dd($myfile);
        // $txt = "John Doe\n";
        // fwrite($myfile, $txt);
        // $txt = "Jane Doe\n";
        // fwrite($myfile, $txt);
        // fclose($myfile);

        // dd($myfile);

        return $cnabsantander;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cnab  $cnab
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cnab $cnab)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cnab  $cnab
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cnab $cnab)
    {
        //
    }
}
