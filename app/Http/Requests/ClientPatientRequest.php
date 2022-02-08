<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientPatientRequest extends FormRequest
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
            'nome.required'=>'O campo Nome do paciente é obrigatório',
            'sexo.required'=>'O campo Gênero do paciente é obrigatório',
            'cpf.required'=>'O campo CPF do paciente é obrigatório',
            'rg.required'=>'O campo RG do paciente é obrigatório',
            'nome_responsavel.required'=>'O campo Nome do responsável é obrigatório',
            'parentesco_responsavel.required'=>'O campo Parentesco do responsável é obrigatório',
            'cpf_responsavel.required'=>'O campo CPF do responsável é obrigatório',
            'telefone_responsavel.required'=>'O campo Telefone do responsável é obrigatório',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // 'nome', 'sexo','cpf','rg','rua','numero','complemento','bairro','cidade', 'estado','latitude','longitude','nome_responsavel',
        // 'parentesco_responsavel','cpf_responsavel','telefone_responsavel','empresa_id'
        switch (strtolower($this->route()->getActionMethod())):
            case 'store':
                return [
                    'nome' => 'required',
                    'sexo' => ['required'],
                    'cpf' => ['required'],
                    'rg' => ['required'],
                    'cep' => ['sometimes'],
                    'rua' => ['sometimes'],
                    'numero' => ['sometimes'],
                    'complemento' => ['sometimes'],
                    'bairro' => ['sometimes'],
                    'cidade' => ['sometimes'],
                    'estado' => ['sometimes'],
                    'nome_responsavel' => ['required'],
                    'parentesco_responsavel' => ['required'],
                    'cpf_responsavel' => ['required'],
                    'telefone_responsavel' => ['required'],
                ];
                break;
            default:
                return [];
                break;
        endswitch;
    }
}
