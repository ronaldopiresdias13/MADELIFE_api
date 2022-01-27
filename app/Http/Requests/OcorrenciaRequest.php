<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OcorrenciaRequest extends FormRequest
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
            
            case 'resolver_ocorrencia':
                return [
                    'justificativa' => 'required|string',
                    'ocorrencia_id' => 'required|numeric',
                ];
                break;
            default:
                return [];
                break;
        endswitch;
    }
}
