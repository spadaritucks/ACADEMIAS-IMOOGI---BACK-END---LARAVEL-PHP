<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $validator->errors(),
        ], 422));
    }

    public function rules(): array
    {
        return [
            'foto_usuario' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tipo_usuario' => 'required|string|in:aluno,funcionario',
            'nome' => 'required|string|max:255',
            'email' => 'required|email|',
            'data_nascimento' => 'required|date',
            'cpf' => 'required|unique:usuarios',
            'rg' => 'required|string|max:20',
            'telefone' => 'required|string|max:15',
            'cep' => 'required|string|max:10',
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|integer',
            'complemento' => 'required|string|max:255',
            'password' => 'required|string|min:8',

            // Validações específicas para `aluno`
            'planos_id' => 'required_if:tipo_usuario,aluno|integer|exists:planos,id',
            'packs_id' => 'nullable|integer|exists:packs,id',
            'data_inicio' => 'required_if:tipo_usuario,aluno|date',
            'data_renovacao' => 'nullable|date',
            'data_vencimento' => 'nullable|date',
            'valor_plano' => 'nullable',
            'desconto' => 'nullable',
            'parcelas' => 'nullable',
            'observacoes' => 'required|string|max:500',
            'modalidade_id' => 'required_if:tipo_usuario,aluno|exists:modalidades,id',

            // Validações específicas para `funcionario`
            'tipo_funcionario' => 'required_if:tipo_usuario,funcionario|string',
            'cargo' => 'required_if:tipo_usuario,funcionario|string|max:255',
            'atividades' => 'required_if:tipo_usuario,funcionario|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            // Mensagens gerais
            'tipo_usuario.required' => 'Campo do tipo do usuário é obrigatório!',
            'nome.required' => 'Campo nome é obrigatório!',
            'email.required' => 'O campo email é obrigatório!',
            'email.email' => 'Insira um email válido!',
            'data_nascimento.required' => 'Campo data de nascimento é obrigatório!',
            'data_nascimento.date' => 'Insira uma data válida!',
            'cpf.required' => 'Campo CPF é obrigatório!',
            'cpf.unique' => 'Este CPF já está cadastrado!',
            'rg.required' => 'Campo RG é obrigatório!',
            'telefone.required' => 'Campo telefone é obrigatório!',
            'cep.required' => 'Campo CEP é obrigatório!',
            'logradouro.required' => 'Campo logradouro é obrigatório!',
            'numero.required' => 'Campo número é obrigatório!',
            'password.required' => 'Campo senha é obrigatório!',
            'password.min' => 'Senha deve ter no mínimo :min caracteres!',
            'observacoes.required' => 'Campo observações é obrigatório!',

            // Mensagens para o tipo `aluno`
            'planos_id.required_if' => 'Campo plano é obrigatório para alunos!',
            'packs_id.integer' => 'Valor de packs inválido!',
            'data_inicio.required_if' => 'Campo data de início é obrigatório para alunos!',
            'data_renovacao.date' => 'Data de renovação deve ser válida!',
            'data_vencimento.date' => 'Data de vencimento deve ser válida!',
            'valor_plano.numeric' => 'O valor do plano deve ser numérico!',
            'desconto.numeric' => 'O desconto deve ser um número!',
            'parcelas.integer' => 'Parcelas devem ser um número inteiro!',
            'modalidade_id.required_if' => 'Campo modalidade é obrigatório para alunos!',
           

            // Mensagens para o tipo `funcionario`
            'tipo_funcionario.required_if' => 'Campo tipo do funcionário é obrigatório para funcionários!',
            'cargo.required_if' => 'Campo cargo é obrigatório para funcionários!',
            'atividades.required_if' => 'Campo atividades é obrigatório para funcionários!',
        ];
    }
}