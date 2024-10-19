<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UsuarioRequest extends FormRequest
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
            'foto_usuario' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'tipo_usuario' => 'required|string|max:255',
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'data_nascimento' => 'required|date',
            'cpf' => 'required|string|unique:usuarios,cpf',
            'rg' => 'required|string',
            'telefone' => 'required|string',
            'cep' => 'required|string',
            'logradouro' => 'required|string',
            'numero' => 'required|integer',
            'complemento' => 'nullable|string',
            'password' => 'nullable|string|min:6',

            // 'planos_id' => 'required',
            // 'modalidade_id' => 'required',
            // 'data_inicio' => 'required|date',
            // 'data_renovacao' => 'required|date',
            // 'data_vencimento' => 'required|date',
            // 'valor_plano' => 'required',
            // 'desconto' => 'required',
            // 'parcelas' => 'required',
            // 'observacoes' => 'required|string',

           

        ];
    }

    /**
     * Retorna as mensagens de erro personalizadas para as regras de validação.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'tipo_usuario.required' => 'Campo do tipo do usuario é obrigatorio!',
            'nome.required' => 'Campo nome é obrigatorio!',
            'email.required' => 'Esse campo é obrigatorio!',
            'email.email' => 'Insira um email valido',
            'data_nascimento.required' => 'Campo data de nascimento é obrigatorio',
            'data_nascimento.date' => 'Insira uma data valida!',
            'cpf.required' => 'Campo CPF é obrigatorio',
            'cpf.unique' => 'CPF já cadastrado!',
            'rg.required' => 'Campo RG é obrigatorio',
            'telefone.required' => 'Campo telefone é obrigatorio',
            'cep.required' => 'Campo CEP é obrigatorio',
            'logradouro.required' => 'Campo Logradouro é obrigatorio',
            'numero.required' => 'Campo Numero é obrigatorio',
            'password.required' => 'Campo Senha é obrigatorio',
            'password.min' => 'Senha com no mínimo :min caracteres!',

            // 'planos_id.required' => "Campo plano é obrigatorio!",
            // 'modalidade_id.required' => "Campo modalidade é obrigatorio!",
            // 'data_inicio.required' => "Campo data de inicio é Obrigatorio!",
            // 'data_renovacao.required' => "Campo data de renovação é obrigatorio!",
            // 'data_vencimento.required' => "Campo data de vencimento é Obrigatorio!",
            // 'valor_plano.required' => "Campo valor do plano é obrigatorio",
            // 'valor_plano.integer' => "Valor invalido!",
            // 'parcelas.required' => "Campo parcelas é obrigatorio",
            // 'parcelas.integer' => "Valor invalido",
            // 'desconto.required' => "Campo desconto é obrigatorio",
            // 'desconto.integer' => "Valor invalido",
            // 'observacoes.required' => "Campo observações é obrigatorio",

            // 'tipo_funcionario.required' => "Campo tipo do funcionario é obrigatorio",
            // 'cargo.required' => "Campo cargo é obrigatorio",
            // 'atividades.required' => "Campo atividades é obrigatorio",
        ];
    }
}
