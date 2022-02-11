<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PilRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch (strtolower($this->route()->getActionMethod())):
            case 'store_pil':
                return [
                    'diagnosticos_principais' => 'required',
                    'paciente' => 'required',
                    'diagnosticos_secundarios' => 'required',
                    'prescricoes_a' => 'required',
                    'prescricoes_b' => 'required',
                    'medicamentos' => 'required',

                    'prognostico' => 'present',
                    'avaliacao_prescricoes' => 'present',
                    'justificativa_revisao' => 'present',
                    'referencia' => 'present',

                    'evolucao_base' => 'present',
                    'revisao' => 'required',

                    'desenvolvido_por' => 'present',
                    'desenvolvido_por_data' => 'present|nullable|date_format:Y-m-d',

                    'atualizado_por' => 'present',
                    'atualizado_por_data' => 'present|nullable|date_format:Y-m-d',

                    'aprovado_por' => 'present',
                    'aprovado_por_data' => 'present|nullable|date_format:Y-m-d',

                ];
                break;
            case 'update_pil':
                return [
                    'diagnosticos_principais' => 'required',
                    'paciente' => 'required',
                    'diagnosticos_secundarios' => 'required',
                    'prescricoes_a' => 'required',
                    'prescricoes_b' => 'required',
                    'medicamentos' => 'required',

                    'prognostico' => 'present',
                    'avaliacao_prescricoes' => 'present',
                    'justificativa_revisao' => 'present',
                    'referencia' => 'present',

                    'evolucao_base' => 'present',
                    'revisao' => 'required',

                    'pil_id' => 'required',

                    'desenvolvido_por' => 'present',
                    'desenvolvido_por_data' => 'present|nullable|date_format:Y-m-d',

                    'atualizado_por' => 'present',
                    'atualizado_por_data' => 'present|nullable|date_format:Y-m-d',

                    'aprovado_por' => 'present',
                    'aprovado_por_data' => 'present|nullable|date_format:Y-m-d',
                ];
                break;
            default:
                return [];
                break;
        endswitch;
    }
}
