<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NeadRequest extends FormRequest
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
            case 'store_nead':
                return [
                    'diagnosticos_principais' => 'required',
                    'paciente_id' => 'required',
                    'diagnostico_secundarios_id' => 'required',
                    'classificacao_katz' => 'required',
                    'classificacao_pacient' => 'required',
                    'grupo1' => 'required',
                    'grupo2' => 'required',
                    'grupo3' => 'required',
                    'escore_katz' => 'required',
                ];
                break;
            case 'update_nead':
                return [
                    'diagnosticos_principais' => 'required',
                    'paciente_id' => 'required',
                    'diagnostico_secundarios_id' => 'required',
                    'classificacao_katz' => 'required',
                    'classificacao_pacient' => 'required',
                    'grupo1' => 'required',
                    'grupo2' => 'required',
                    'grupo3' => 'required',
                    'escore_katz' => 'required',
                    'nead_id' => 'required',
                ];
                break;
            default:
                return [];
                break;
        endswitch;
    }
}
