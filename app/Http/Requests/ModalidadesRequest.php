<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ModalidadesRequest extends FormRequest
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
            'message' => $validator->errors(),
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
            'foto_modalidade' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nome_modalidade' => 'required|string',
            'descricao_modalidade' => 'required|string|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'nome_modalidade.required' => 'Campo nome da modalidade é obrigatorio',
            'descricao_modalidade.required' => 'Campo descrição da modalidade é obrigatorio',
        ];

    }
}
