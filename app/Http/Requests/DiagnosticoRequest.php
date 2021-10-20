<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiagnosticoRequest extends FormRequest
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

    public function messages()
    {
        return [
            'nome.required' => 'O campo Nome é obrigatório',
            'codigo.required' => 'O campo Código é obrigatório',
            // 'descricao.required' => 'O campo Descrição é obrigatório',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch (strtolower($this->route()->getActionMethod())):
            case 'store_diagnostico':
                return [
                    'nome' => 'required',
                    'codigo' => 'required',
                    // 'descricao' => 'required',
                    // 'referencias' => 'sometimes',
                ];
                break;
            case 'update_diagnostico':
                return [
                    'nome' => 'required',
                    'codigo' => 'required',
                    // 'descricao' => 'required',
                    'diagnostico_id' => 'required',
                    // 'referencias' => 'sometimes',
                ];
                break;
            default:
                return [];
                break;
        endswitch;
    }
}
