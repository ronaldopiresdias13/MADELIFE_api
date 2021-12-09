<?php

namespace App\Http\Controllers\Web\Protocolos;

use App\Http\Controllers\Controller;
use App\Models\ProtocoloMedicacao;
use App\Models\ProtocoloSkin;
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
    public function index()
    {
        return ProtocoloMedicacao::all();
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
        //
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
