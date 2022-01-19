<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnexoBRequest extends FormRequest
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
            case 'store_anexob':
                return [
                    'paciente' => 'required',
                    'dados' => 'required',
                    'informacoes_complementares' => 'required',
                ];
                break;
            case 'update_anexob':
                return [
                    'paciente' => 'required',
                    'dados' => 'required',
                    'informacoes_complementares' => 'required',
                    'anexo_b_id' => 'required',
                ];
                break;
            default:
                return [];
                break;
        endswitch;
    }
}
