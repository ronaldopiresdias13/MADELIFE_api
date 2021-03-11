<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CnabRequest extends FormRequest
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
            'dados.*.codigo.required' => 'O código de banco do usuário é obrigatório',
            'dados.*.agencia.required' => 'A agência do banco do usuário é obrigatório',
            'dados.*.digito.required' => 'O dígito da conta do banco do profissional é obrigatório',
            'dados.*.conta.required' => 'O campo da conta do banco do profissional é obrigatório',

            'data.required' => 'O campo Data do Pagamento é obrigatório',
            'data.date_format' => 'O campo Data do Pagamento deve estar no formado YYYY-MM-DD',
            'data.after' => 'O campo Data do Pagamento deve ser uma data maior ou igual o dia de hoje',

            'observacao.required' => 'O campo Observação é obrigatório',
            'banco.required' => 'O campo Banco é obrigatório',
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
            case 'gerarcnab':
                return [
                    'dados' => 'required|array',
                    'dados.*.codigo' => ['required', 'max:3'],
                    'dados.*.conta' => ['required', 'max:12'],
                    'dados.*.agencia' => ['required', 'max:5'],
                    'dados.*.digito' => ['required', 'size:1'],
                    'dados.*.profissional_id' => ['required'],
                    'mes' => 'required|date_format:Y-m',
                    'data' => 'required|date_format:Y-m-d|after:' . Carbon::now()->subDay(),
                    'banco' => 'required',
                    'observacao' => 'required',
                ];
        break;

        default:
                return [];
        break;
        endswitch;
    }
}
