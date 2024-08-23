<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PlanosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'erros' => $validator->errors(),
        ], 422)); // O código de status HTTP 422 significa "Unprocessable Entity" (Entidade Não Processável). Esse código é usado quando o servidor entende a requisição do cliente, mas não pode processá-la devido a erros de validação no lado do servidor.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome_plano' => 'required|string|max:255',
            'duracao'=> 'required|integer',
            'valor_matricula' => 'required',
            'valor_mensal'=> 'required' ,
            'valor_total'=> 'required',
            'num_modalidades'=> 'required',
            'status'=> 'required|string'
        ];
    }

    public function messages(): array{
        return [
            'nome_plano.required' => 'Campo nome do plano é obrigatorio',
            'duracao.required' => 'Campo de duração do plano é obrigatorio',
            'valor_matricula.required' => 'Campo do valor da matricula do plano é obrigatorio',
            'valor_mensal.required' => 'Campo do valor mensal do plano é obrigatorio',
            'valor_total.required' => 'Campo do valor total do plano é obrigatorio',
            'num_modalidades.requires' => 'Campo de quantidade de modalidades é obrigatorio',
            'status.required' => 'Campo status é obrigatorio',
        ];
    }
    
}

    


