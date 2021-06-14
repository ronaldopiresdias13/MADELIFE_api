<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChamadoAtendenteRequest extends FormRequest
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
            case 'criarchamado_atendente_enfermagem':
                return [
                    'assunto' => 'required|string',
                    'mensagem' => 'required|string',
                    'prestador_id' => 'required|numeric',
                    'ocorrencia' => 'sometimes|nullable|numeric',
                ];
                break;
            case 'finalizarchamado_enfermagem':
                return [
                    'justificativa' => 'required|string',
                    'chamado_id' => 'required|numeric',
                ];
                break;

            case 'criarchamado_atendente_ti':
                return [
                    'assunto' => 'required|string',
                    'mensagem' => 'required|string',
                    'prestador_id' => 'required|numeric',

                ];
                break;
            case 'finalizarchamado_ti':
                return [
                    'justificativa' => 'required|string',
                    'chamado_id' => 'required|numeric',
                ];
                break;
            default:
                return [];
                break;
        endswitch;
    }
}
