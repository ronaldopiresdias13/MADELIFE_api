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
                    'diagnostico_principal' => 'required',
                    'paciente' => 'required',
                    'diagnosticos_secundarios' => 'required',
                    'prescricoes_a' => 'required',
                    'prescricoes_b' => 'required',
                    'medicamentos' => 'required',

                    'prognostico' => 'present',
                    'avaliacao_prescricoes' => 'present',
                    'justificativa_revisao' => 'present',
                    'evolucao_base' => 'present',
                    'revisao' => 'required',

                ];
                break;
            case 'update_pil':
                return [
                    'diagnostico_principal' => 'required',
                    'paciente' => 'required',
                    'diagnosticos_secundarios' => 'required',
                    'prescricoes_a' => 'required',
                    'prescricoes_b' => 'required',
                    'medicamentos' => 'required',

                    'prognostico' => 'present',
                    'avaliacao_prescricoes' => 'present',
                    'justificativa_revisao' => 'present',
                    'evolucao_base' => 'present',
                    'revisao' => 'required',

                    'pil_id' => 'required',
                ];
                break;
            default:
                return [];
                break;
        endswitch;
    }
}
