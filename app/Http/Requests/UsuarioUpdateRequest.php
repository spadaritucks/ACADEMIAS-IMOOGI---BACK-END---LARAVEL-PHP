<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UsuarioUpdateRequest extends FormRequest
{
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
            'foto_usuario' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tipo_usuario' => 'nullable|string|max:255',
            'nome' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|',
            'data_nascimento' => 'nullable|date',
            'cpf' => 'nullable|string|',
            'rg' => 'nullable|string',
            'telefone' => 'nullable|string',
            'cep' => 'nullable|string',
            'logradouro' => 'nullable|string',
            'numero' => 'nullable|integer',
            'complemento' => 'nullable|string',
            'password' => 'nullable|string|min:6',
        ];
    }

    public function messages() : array
    {
       return [
            'email.email' => 'Insira um email valido',
            'email.unique' => 'E-mail já está cadastrado',
            'data_nascimento.date' => 'Insira uma data valida!',
            'cpf.unique' => 'CPF já cadastrado!',
            'password.min' => 'Senha com no mínimo :min caracteres!',


        ];
    }

}
