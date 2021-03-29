<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChamadoRequest extends FormRequest
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
            case 'criarchamado':
                return [
                    'assunto' => 'required|string',
                    'mensagem' => 'required|string',
                    'area' => 'required|string',
                ];
                break;
            case 'enviararquivos':
                return [
                    'image' => 'required',
                ];
                break;
            default:
                return [];
                break;
        endswitch;
    }
}
