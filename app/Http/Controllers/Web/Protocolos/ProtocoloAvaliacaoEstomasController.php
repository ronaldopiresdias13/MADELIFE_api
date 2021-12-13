<?php

namespace App\Http\Controllers\Web\Protocolos;

use App\Http\Controllers\Controller;
use App\Models\ProtocoloAvaliacaoEstoma;
use App\Models\ProtocoloSkin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProtocoloAvaliacaoEstomasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return ProtocoloAvaliacaoEstoma::all();
         // return ProtocoloAvaliacaoEstoma::all();
        // $user = $request->user();
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;
        // $empresa_id = 2;
        return ProtocoloAvaliacaoEstoma::with(['protocolo', 'protocolo.cliente.pessoa', 'protocolo.paciente.pessoa'])
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
        $empresa_id = $request->user()->pessoa->profissional->empresa_id;

        DB::transaction(function () use ($request, $empresa_id){
            $protocoloAvaliacaoEstoma = ProtocoloAvaliacaoEstoma::create([
                'empresa_id' => $empresa_id,
                'protocolo_id' => ProtocoloSkin::firstOrCreate([
                    'empresa_id'             => $empresa_id,
                    'paciente_id'            => $request['protocolo']['paciente_id'],
                    'cliente_id'             => $request['protocolo']['cliente_id'],
                    'medico'                 => $request['protocolo']['medico'],
                    'data'                   => $request['protocolo']['data'],
                    'diagnostico'            => $request['protocolo']['diagnostico'],
                    'afastado'               => $request['protocolo']['afastado'],
                    'aposentado'             => $request['protocolo']['aposentado'],
                    'nivel_consciencia'      => $request['protocolo']['nivel_consciencia'],
                    'obs_nivel_consciencia'  => $request['protocolo']['obs_nivel_consciencia'],
                    'hipertensao_arterial'   => $request['protocolo']['hipertensao_arterial'],
                    'pa'                     => $request['protocolo']['pa'],
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
                    'cl_traqueostomia'             => $request['cl_traqueostomia'],
                    'cl_gastrostomia'              => $request['cl_gastrostomia'],
                    'cl_jejunostomia'              => $request['cl_jejunostomia'],
                    'cl_ileostomia'                => $request['cl_ileostomia'],
                    'cl_colostomiad'               => $request['cl_colostomiad'],
                    'cl_colostomiae'               => $request['cl_colostomiae'],
                    'cl_urostomia'                 => $request['cl_urostomia'],
                    'cl_cistostomia'               => $request['cl_cistostomia'],
                    'cl_outros'                    => $request['cl_outros'],
                    'mensuracao_anterior'         => $request ['mensuracao_anterior'],
                    'mensuracao_atual'            => $request ['mensuracao_atual'],
                    'coloracao_vermelho'           => $request['coloracao_vermelho'],
                    'clr_vermelho_palido'          => $request['clr_vermelho_palido'],
                    'clr_vermelho_vinhoso'         => $request['clr_vermelho_vinhoso'],
                    'clr_outro'                    => $request['clr_outro'],
                    'pr_integra'                   => $request['pr_integra'],
                    'pr_edema'                     => $request['pr_edema'],
                    'pr_eritema'                   => $request['pr_eritema'],
                    'pr_endurecimento'             => $request['pr_endurecimento'],
                    'pr_prurido'                   => $request['pr_prurido'],
                    'pr_descamacao'                => $request['pr_descamacao'],
                    'pr_hiperpigmentacao'          => $request['pr_hiperpigmentacao'],
                    'pr_dermatites'                => $request['pr_dermatites'],
                    'pr_outros'                    => $request['pr_outros'],
                    'el_liquida'                   => $request['el_liquida'],
                    'el_pastosa'                   => $request['el_pastosa'],
                    'el_solida'                    => $request['el_solida'],
                    'el_outro'                     => $request['el_outro'],
                    'cmp_sangramento'              => $request['cmp_sangramento'],
                    'cmp_isquemia'                 => $request['cmp_isquemia'],
                    'cmp_edema'                    => $request['cmp_edema'],
                    'cmp_retracao'                 => $request['cmp_retracao'],
                    'cmp_estenose'                 => $request['cmp_estenose'],
                    'cmp_prolapso'                 => $request['cmp_prolapso'],
                    'cmp_hernia'                   => $request['cmp_hernia'],
                    'cmp_dermatite'                => $request['cmp_dermatite'],
                    'cmp_varizes'                  => $request['cmp_varizes'],
                    'cmp_fistula'                  => $request['cmp_fistula'],
                    'cmp_deiscencia'               => $request['cmp_deiscencia'],
                    'cmp_outro'                    => $request['cmp_outro'],
                    'permanencia'                  => $request['permanencia'],
                    'orientacao_previa'            => $request['orientacao_previa'],
                    'opr_qual'                     => $request['opr_qual'],
                    'tipo_bolsa'                   => $request['tipo_bolsa'],
                    'tipo_placa'                   => $request['tipo_placa'],
                    'acessorios'                   => $request['acessorios'],
                    'outro'                        => $request['outro'],
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
     * @param  \App\Models\ProtocoloAvaliacaoEstoma  $protocoloAvaliacaoEstoma
     * @return \Illuminate\Http\Response
     */
    public function show(ProtocoloAvaliacaoEstoma $protocoloAvaliacaoEstoma)
    {
        $protocoloAvaliacaoEstoma->protocolo;
        $protocoloAvaliacaoEstoma->protocolo->cliente;
        $protocoloAvaliacaoEstoma->protocolo->paciente;
        return $protocoloAvaliacaoEstoma;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProtocoloAvaliacaoEstoma  $protocoloAvaliacaoEstoma
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProtocoloAvaliacaoEstoma $protocoloAvaliacaoEstoma)
    {
        
        DB::transaction(function () use($request, $protocoloAvaliacaoEstoma){
            $protocoloAvaliacaoEstoma = ProtocoloAvaliacaoEstoma::updateOrCreate(
            [
                'id'  => $request['id'],
            ],
            [
            'protocolo_id' => ProtocoloSkin::updateOrCreate(
                [
                    'id'   => $request['protocolo']['protocolo_id']
                ],
                [
                    'paciente_id'            => $request['protocolo']['paciente_id'],
                    'cliente_id'             => $request['protocolo']['cliente_id'],
                    'medico'                 => $request['protocolo']['medico'],
                    'data'                   => $request['protocolo']['data'],
                    'diagnostico'            => $request['protocolo']['diagnostico'],
                    'afastado'               => $request['protocolo']['afastado'],
                    'aposentado'             => $request['protocolo']['aposentado'],
                    'nivel_consciencia'      => $request['protocolo']['nivel_consciencia'],
                    'obs_nivel_consciencia'  => $request['protocolo']['obs_nivel_consciencia'],
                    'hipertensao_arterial'   => $request['protocolo']['hipertensao_arterial'],
                    'pa'                     => $request['protocolo']['pa'],
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
                'cl_traqueostomia'             => $request['cl_traqueostomia'],
                'cl_gastrostomia'              => $request['cl_gastrostomia'],
                'cl_jejunostomia'              => $request['cl_jejunostomia'],
                'cl_ileostomia'                => $request['cl_ileostomia'],
                'cl_colostomiad'               => $request['cl_colostomiad'],
                'cl_colostomiae'               => $request['cl_colostomiae'],
                'cl_urostomia'                 => $request['cl_urostomia'],
                'cl_cistostomia'               => $request['cl_cistostomia'],
                'cl_outros'                    => $request['cl_outros'],
                'mensuracao_anterior'         => $request['mensuracao_anterior'],
                'mensuracao_atual'            => $request['mensuracao_atual'],
                'coloracao_vermelho'           => $request['coloracao_vermelho'],
                'clr_vermelho_palido'          => $request['clr_vermelho_palido'],
                'clr_vermelho_vinhoso'         => $request['clr_vermelho_vinhoso'],
                'clr_outro'                    => $request['clr_outro'],
                'pr_integra'                   => $request['pr_integra'],
                'pr_edema'                     => $request['pr_edema'],
                'pr_eritema'                   => $request['pr_eritema'],
                'pr_endurecimento'             => $request['pr_endurecimento'],
                'pr_prurido'                   => $request['pr_prurido'],
                'pr_descamacao'                => $request['pr_descamacao'],
                'pr_hiperpigmentacao'          => $request['pr_hiperpigmentacao'],
                'pr_dermatites'                => $request['pr_dermatites'],
                'pr_outros'                    => $request['pr_outros'],
                'el_liquida'                   => $request['el_liquida'],
                'el_pastosa'                   => $request['el_pastosa'],
                'el_solida'                    => $request['el_solida'],
                'el_outro'                     => $request['el_outro'],
                'cmp_sangramento'              => $request['cmp_sangramento'],
                'cmp_isquemia'                 => $request['cmp_isquemia'],
                'cmp_edema'                    => $request['cmp_edema'],
                'cmp_retracao'                 => $request['cmp_retracao'],
                'cmp_estenose'                 => $request['cmp_estenose'],
                'cmp_prolapso'                 => $request['cmp_prolapso'],
                'cmp_hernia'                   => $request['cmp_hernia'],
                'cmp_dermatite'                => $request['cmp_dermatite'],
                'cmp_varizes'                  => $request['cmp_varizes'],
                'cmp_fistula'                  => $request['cmp_fistula'],
                'cmp_deiscencia'               => $request['cmp_deiscencia'],
                'cmp_outro'                    => $request['cmp_outro'],
                'permanencia'                  => $request['permanencia'],
                'orientacao_previa'            => $request['orientacao_previa'],
                'opr_qual'                     => $request['opr_qual'],
                'tipo_bolsa'                   => $request['tipo_bolsa'],
                'tipo_placa'                   => $request['tipo_placa'],
                'acessorios'                   => $request['acessorios'],
                'outro'                        => $request['outro'],
            ]);
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProtocoloAvaliacaoEstoma  $protocoloAvaliacaoEstoma
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProtocoloAvaliacaoEstoma $protocoloAvaliacaoEstoma)
    {
         $protocoloAvaliacaoEstoma->delete();
    }
}
