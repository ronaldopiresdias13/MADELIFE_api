<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AbmidRequest extends FormRequest
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
            case 'store_abmid':
                return [
                    'diagnosticos_principais' => 'required',
                    'paciente_id' => 'required',
                    'diagnostico_secundarios_id' => 'required',
                    'classificacao' => 'required',
                    'cuidados' => 'required',
                ];
                break;
            case 'update_abmid':
                return [
                    'diagnosticos_principais' => 'required',
                    'paciente_id' => 'required',
                    'diagnostico_secundarios_id' => 'required',
                    'classificacao' => 'required',
                    'cuidados' => 'required',
                    'abmid_id' => 'required',
                ];
                break;
            default:
                return [];
                break;
        endswitch;
    }
}
