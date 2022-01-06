<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrescricaoARequest extends FormRequest
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
            case 'store_prescricao_a':
                return [
                    'nome' => 'required',
                    // 'descricao' => 'required',
                    // 'referencias' => 'sometimes',
                ];
                break;
            case 'update_prescricao_a':
                return [
                    'nome' => 'required',
                    // 'descricao' => 'required',
                    'prescricao_id' => 'required',
                    // 'referencias' => 'sometimes',
                ];
                break;
            default:
                return [];
                break;
        endswitch;
    }
}
