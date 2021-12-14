<?php

namespace App\Http\Controllers\Web\Protocolos;

use App\Http\Controllers\Controller;
use App\Models\ProtocoloMedicacao;
use App\Models\ProtocoloSkin;
use App\Models\ProtocoloAvaliacaoMedicamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProtocoloMedicacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        return ProtocoloMedicacao::with('protocolo' ,'protocolo.cliente.pessoa', 'protocolo.paciente.pessoa' ,'protocolo_avaliacao_medicamento')
        ->where('empresa_id', $empresa_id)
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
        $empresa_id = Auth::user()->pessoa->profissional->empresa_id;

        DB::transaction(function () use ($request, $empresa_id){
            $protocoloMedicacao = ProtocoloMedicacao::create([
                'empresa_id' => $empresa_id,
                'protocolo_id' => ProtocoloSkin::firstOrCreate([
                    'empresa_id'             => $request['empresa_id'],
                    'paciente_id'            => $request['protocolo']['paciente_id'],
                    'cliente_id'             => $request['protocolo']['cliente_id'],
                    'medico'                 => $request['protocolo']['medico'],
                    'diagnostico'            => $request['protocolo']['diagnostico'],
                    'afastado'               => $request['protocolo']['afastado'],
                    'aposentado'             => $request['protocolo']['aposentado'],
                    'nivel_consciencia'      => $request['protocolo']['nivel_consciencia'],
                    'obs_nivel_consciencia'  => $request['protocolo']['obs_nivel_consciencia'],
                    'hipertensao_arterial'   => $request['protocolo']['hipertensao_arterial'],
                    'pa'                     => $request['protocolo']['pa'],
                    'data'                   => $request['protocolo']['data'],
                    'diabetes'               => $request['protocolo']['diabetes'],
                    'glicemia'               => $request['protocolo']['glicemia'],
                    'hematocrito'            => $request['protocolo']['hematocrito'],
                    'hemoglobina'            => $request['protocolo']['hemoglobina'],
                    'proteinas_totais'       => $request['protocolo']['proteinas_totais'],
                    'pcr'                    => $request['protocolo']['pcr'],
                    'albumina'               => $request['protocolo']['albumina'],
                    'outros'                 => $request['protocolo']['outros'],
                    'peso'                   => $request['protocolo']['peso'],
                    'altura'                 => $request['protocolo']['altura'],
                    'acamado'                => $request['protocolo']['acamado'],
                    'deambula'               => $request['protocolo']['deambula'],
                    'cadeira_rodas'          => $request['protocolo']['cadeira_rodas'],
                    'muleta'                 => $request['protocolo']['muleta'],
                    'andador'                => $request['protocolo']['andador'],
                    'c_ajuda'                => $request['protocolo']['c_ajuda'],
                    'destreza_manual'        => $request['protocolo']['destreza_manual'],
                    'auto_cuidado'           => $request['protocolo']['auto_cuidado'],
                    'cafe_manha'             => $request['protocolo']['cafe_manha'],
                    'almoco'                 => $request['protocolo']['almoco'],
                    'cafe_tarde'             => $request['protocolo']['cafe_tarde'],
                    'jantar'                 => $request['protocolo']['jantar'],
                    'ceia'                   => $request['protocolo']['ceia'],
                    'rica'                   => $request['protocolo']['rica'],
                    'urinaria'               => $request['protocolo']['urinaria'],
                    'fecal'                  => $request['protocolo']['fecal'],
                    'medicamento'            => $request['protocolo']['medicamento'],
                    'av_central'             => $request['protocolo']['av_central'],
                    'av_periferico'          => $request['protocolo']['av_periferico'],
                    'av_jelco'               => $request['protocolo']['av_jelco'],
                    'av_scalp'               => $request['protocolo']['av_scalp'],
                    'av_intracath'           => $request['protocolo']['av_intracath'],
                    'av_portocath'           => $request['protocolo']['av_portocath'],
                    'av_piv'                 => $request['protocolo']['av_piv'],
                    'av_data'                => $request['protocolo']['av_data'],
                    'tbg_fuma'               => $request['protocolo']['tbg_fuma'],
                    'tbg_tempo'              => $request['protocolo']['tbg_tempo'],
                    'tbg_cigarros_dia'       => $request['protocolo']['tbg_cigarros_dia'],
                    'tbg_atual'              => $request['protocolo']['tbg_atual'],
                    'tbg_tempo_parou'        => $request['protocolo']['tbg_tempo_parou'],
                    'alergia'                => $request['protocolo']['alergia'],
                    'alergia_qual'           => $request['protocolo']['alergia_qual'],
                    'oxigenioterapia'        => $request['protocolo']['oxigenioterapia'],
                    'ox_cateter_nasal'       => $request['protocolo']['ox_cateter_nasal'],
                    'ox_mascara_nebulizacao' => $request['protocolo']['ox_mascara_nebulizacao'],
                    'ox_cateter_venturi'     => $request['protocolo']['ox_cateter_venturi'],
                    'ox_bipap'               => $request['protocolo']['ox_bipap'],
                    'ox_cpap'                => $request['protocolo']['ox_cpap'],
                    'al_vo'                  => $request['protocolo']['al_vo'],
                    'al_sng'                 => $request['protocolo']['al_sng'],
                    'al_sne'                 => $request['protocolo']['al_sne'],
                    'al_gastrostomia'        => $request['protocolo']['al_gastrostomia'],
                    'al_jejunostomia'        => $request['protocolo']['al_jejunostomia'],
                    'al_parenteral'          => $request['protocolo']['al_parenteral'],
                    'as_sozinho'             => $request['protocolo']['as_sozinho'],
                    'as_familiares'          => $request['protocolo']['as_familiares'],
                    'as_cuidador'            => $request['protocolo']['as_cuidador'],
                    'as_casa_terrea'         => $request['protocolo']['as_casa_terrea'],
                    'as_apartamento'         => $request['protocolo']['as_apartamento'],
                    'as_casa_escadas'        => $request['protocolo']['as_casa_escadas'],
                    'as_outros'              => $request['protocolo']['as_outros'],
                    'cp_normal'              => $request['protocolo']['cp_normal'],
                    'cp_seca'                => $request['protocolo']['cp_seca'],
                    'cp_oleosa'              => $request['protocolo']['cp_oleosa'],
                    'cp_mista'               => $request['protocolo']['cp_mista'],
                    'cp_ressecada'           => $request['protocolo']['cp_ressecada'],
                    'cp_outra'               => $request['protocolo']['cp_outra'],
                    'ds_visao'               => $request['protocolo']['ds_visao'],
                    'ds_audicao'             => $request['protocolo']['ds_audicao'],
                    'cpa_pele_oleosa'        => $request['protocolo']['cpa_pele_oleosa'],
                    'cpa_cicatriz'           => $request['protocolo']['cpa_cicatriz'],
                    'cpa_dobras_gordura'     => $request['protocolo']['cpa_dobras_gordura'],
                    'cpa_dermatologica'      => $request['protocolo']['cpa_dermatologica'],
                    'cpa_atual'              => $request['protocolo']['cpa_atual'],
                    'cpa_qual'               => $request['protocolo']['cpa_qual'],
                    'crd_lesoes'             => $request['protocolo']['crd_lesoes'],
                    'crd_fistulas'           => $request['protocolo']['crd_fistulas'],
                    'crd_fisuras'            => $request['protocolo']['crd_fisuras'],
                    'crd_incontinencia'      => $request['protocolo']['crd_incontinencia'],
                    'crd_outros'             => $request['protocolo']['crd_outros'],
                ])->id,
                    'curativo_cateter_picc'  => $request['curativo_cateter_picc'],
                    'curativo_portacath'     => $request['curativo_portacath'],
                    'cateter_periferico'     => $request['cateter_periferico'],
                    'protocolo_avaliacao_medicacao_id' => ProtocoloAvaliacaoMedicamento::create([
                        'medicamento'   => $request['medicamento']['medicamento'],
                        'dosagem'       => $request['medicamento']['dosagem'],
                        'frequencia'    => $request['medicamento']['frequencia'],
                        'data_inicio'   => $request['medicamento']['data_inicio'],
                        'data_fim'      => $request['medicamento']['data_fim'],
                        'data1'         => $request['medicamento']['data1'],
                        'cm1'           => $request['medicamento']['cm1'],
                        'data2'         => $request['medicamento']['data2'],
                        'cm2'           => $request['medicamento']['cm2'],
                        'data3'         => $request['medicamento']['data3'],
                        'cm3'           => $request['medicamento']['cm3'],
                        'data4'         => $request['medicamento']['data4'],
                        'cm4'           => $request['medicamento']['cm4'],
                    ])->id
            ]);
        });
        return response()->json([
            'toast' => [
                'text'  => 'Protocolo cadastrado com sucesso!',
                'color' => 'success',
                'duration' => 2000
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProtocoloMedicacao  $protocoloMedicacao
     * @return \Illuminate\Http\Response
     */
    public function show(ProtocoloMedicacao $protocoloMedicacao)
    {
        $protocoloMedicacao->protocolo;
        $protocoloMedicacao->protocolo->cliente;
        $protocoloMedicacao->protocolo->paciente;
        $protocoloMedicacao->medicamento;

        return $protocoloMedicacao;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProtocoloMedicacao  $protocoloMedicacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProtocoloMedicacao $protocoloMedicacao)
    {
        DB::transaction(function () use($request){
            $protocoloMedicacao = ProtocoloMedicacao::updateOrCreate(
            [
                'id'  => $request['id'],
            ],
            [
            'empresa_id'   => $request['empresa_id'],
            'protocolo_id' => ProtocoloSkin::updateOrCreate(
                [
                    'id'   => $request['protocolo_id']
                ],
                [
                    'paciente_id'            => $request['paciente_id'],
                    'cliente_id'             => $request['cliente_id'],
                    'medico'                 => $request['medico'],
                    'diagnostico'            => $request['diagnostico'],
                    'afastado'               => $request['afastado'],
                    'aposentado'             => $request['aposentado'],
                    'nivel_consciencia'      => $request['nivel_consciencia'],
                    'obs_nivel_consciencia'  => $request['obs_nivel_consciencia'],
                    'hipertensao_arterial'   => $request['hipertensao_arterial'],
                    'pa'                     => $request['pa'],
                    'diabetes'               => $request['diabetes'],
                    'glicemia'               => $request['glicemia'],
                    'hematocrito'            => $request['hematocrito'],
                    'hemoglobina'            => $request['hemoglobina'],
                    'proteinas_totais'       => $request['proteinas_totais'],
                    'pcr'                    => $request['pcr'],
                    'albumina'               => $request['albumina'],
                    'outros'                 => $request['outros'],
                    'peso'                   => $request['peso'],
                    'altura'                 => $request['altura'],
                    'acamado'                => $request['acamado'],
                    'deambula'               => $request['deambula'],
                    'cadeira_rodas'          => $request['cadeira_rodas'],
                    'muleta'                 => $request['muleta'],
                    'andador'                => $request['andador'],
                    'c_ajuda'                => $request['c_ajuda'],
                    'destreza_manual'        => $request['destreza_manual'],
                    'auto_cuidado'           => $request['auto_cuidado'],
                    'cafe_manha'             => $request['cafe_manha'],
                    'almoco'                 => $request['almoco'],
                    'cafe_tarde'             => $request['cafe_tarde'],
                    'jantar'                 => $request['jantar'],
                    'ceia'                   => $request['ceia'],
                    'rica'                   => $request['rica'],
                    'urinaria'               => $request['urinaria'],
                    'fecal'                  => $request['fecal'],
                    'medicamento'            => $request['medicamento'],
                    'av_central'             => $request['av_central'],
                    'av_periferico'          => $request['av_periferico'],
                    'av_jelco'               => $request['av_jelco'],
                    'av_scalp'               => $request['av_scalp'],
                    'av_intracath'           => $request['av_intracath'],
                    'av_portocath'           => $request['av_portocath'],
                    'av_piv'                 => $request['av_piv'],
                    'av_data'                => $request['av_data'],
                    'tbg_fuma'               => $request['tbg_fuma'],
                    'tbg_tempo'              => $request['tbg_tempo'],
                    'tbg_cigarros_dia'       => $request['tbg_cigarros_dia'],
                    'tbg_atual'              => $request['tbg_atual'],
                    'tbg_tempo_parou'        => $request['tbg_tempo_parou'],
                    'alergia'                => $request['alergia'],
                    'alergia_qual'           => $request['alergia_qual'],
                    'oxigenioterapia'        => $request['oxigenioterapia'],
                    'ox_cateter_nasal'       => $request['ox_cateter_nasal'],
                    'ox_mascara_nebulizacao' => $request['ox_mascara_nebulizacao'],
                    'ox_cateter_venturi'     => $request['ox_cateter_venturi'],
                    'ox_bipap'               => $request['ox_bipap'],
                    'ox_cpap'                => $request['ox_cpap'],
                    'al_vo'                  => $request['al_vo'],
                    'al_sng'                 => $request['al_sng'],
                    'al_sne'                 => $request['al_sne'],
                    'al_gastrostomia'        => $request['al_gastrostomia'],
                    'al_jejunostomia'        => $request['al_jejunostomia'],
                    'al_parenteral'          => $request['al_parenteral'],
                    'as_sozinho'             => $request['as_sozinho'],
                    'as_familiares'          => $request['as_familiares'],
                    'as_cuidador'            => $request['as_cuidador'],
                    'as_casa_terrea'         => $request['as_casa_terrea'],
                    'as_apartamento'         => $request['as_apartamento'],
                    'as_casa_escadas'        => $request['as_casa_escadas'],
                    'as_outros'              => $request['as_outros'],
                    'cp_normal'              => $request['cp_normal'],
                    'cp_seca'                => $request['cp_seca'],
                    'cp_oleosa'              => $request['cp_oleosa'],
                    'cp_mista'               => $request['cp_mista'],
                    'cp_ressecada'           => $request['cp_ressecada'],
                    'cp_outra'               => $request['cp_outra'],
                    'ds_visao'               => $request['ds_visao'],
                    'ds_audicao'             => $request['ds_audicao'],
                    'cpa_pele_oleosa'        => $request['cpa_pele_oleosa'],
                    'cpa_cicatriz'           => $request['cpa_cicatriz'],
                    'cpa_dobras_gordura'     => $request['cpa_dobras_gordura'],
                    'cpa_dermatologica'      => $request['cpa_dermatologica'],
                    'cpa_atual'              => $request['cpa_atual'],
                    'cpa_qual'               => $request['cpa_qual'],
                    'crd_lesoes'             => $request['crd_lesoes'],
                    'crd_fistulas'           => $request['crd_fistulas'],
                    'crd_fisuras'            => $request['crd_fisuras'],
                    'crd_incontinencia'      => $request['crd_incontinencia'],
                    'crd_outros'             => $request['crd_outros'],
                ])->id,
                'curativo_cateter_picc'  => $request['curativo_cateter_picc'],
                'curativo_portacath'     => $request['curativo_portacath'],
                'cateter_periferico'     => $request['cateter_periferico'],
                'protocolo_avaliacao_medicacao_id' => ProtocoloMedicacao::updateOrCreate([
                    'medicamento'   => $request['medicamento']['medicamento'],
                    'dosagem'       => $request['medicamento']['dosagem'],
                    'frequencia'    => $request['medicamento']['frequencia'],
                    'data_inicio'   => $request['medicamento']['data_inicio'],
                    'data_fim'      => $request['medicamento']['data_fim'],
                    'data1'         => $request['medicamento']['data1'],
                    'cm1'           => $request['medicamento']['cm1'],
                    'data2'         => $request['medicamento']['data2'],
                    'cm2'           => $request['medicamento']['cm2'],
                    'data3'         => $request['medicamento']['data3'],
                    'cm3'           => $request['medicamento']['cm3'],
                    'data4'         => $request['medicamento']['data4'],
                    'cm4'           => $request['medicamento']['cm4'],
                ])->id
            ]);
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProtocoloMedicacao  $protocoloMedicacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProtocoloMedicacao $protocoloMedicacao)
    {
        $protocoloMedicacao->delete();
    }
}
