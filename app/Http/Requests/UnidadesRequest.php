<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UnidadesRequest extends FormRequest
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
            'imagem_unidade' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nome_unidade' => 'required|string',
            'endereco' => 'required|string',
            'grade' => 'nullable|mimes:pdf|max:10000',
            'descricao' => 'required|string'
        ];
    }

    public function messages()
    {
       return[
           'nome_unidade.required' => 'Campo nome da unidade é obrigatorio',
           'endereco.required' => 'Campo endereço é obrigatorio',
           'descricao.required' => 'Campo descrição é obrigatorio'
       ];

    }
}
