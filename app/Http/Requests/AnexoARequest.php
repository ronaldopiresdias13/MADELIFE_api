<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnexoARequest extends FormRequest
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
            case 'store_anexoa':
                return [
                    'diagnostico_principal_id' => 'required',
                    'paciente_id' => 'required',
                    'diagnostico_secundarios_id' => 'required',
                    'classificacao_coma_glasbow' => 'required',
                    'classificacao_braden' => 'required',
                    'dados_fisicos' => 'required',
                    'intensidade_dor' => 'required',
                    'diametros_pupilas' => 'required',

                    'escala_braden' => 'required',
                    'escala_coma_glasgow' => 'required',

                ];
                break;
            case 'update_anexoa':
                return [
                    'diagnostico_principal_id' => 'required',
                    'paciente_id' => 'required',
                    'diagnostico_secundarios_id' => 'required',
                    'classificacao_coma_glasbow' => 'required',
                    'classificacao_braden' => 'required',
                    'dados_fisicos' => 'required',
                    'intensidade_dor' => 'required',
                    'diametros_pupilas' => 'required',

                    'escala_braden' => 'required',
                    'escala_coma_glasgow' => 'required',
                    'anexo_a_id' => 'required',
                ];
                break;
            default:
                return [];
                break;
        endswitch;
    }
}
