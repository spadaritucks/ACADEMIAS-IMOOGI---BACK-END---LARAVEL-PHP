<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AulasRequest extends FormRequest
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
            'modalidade_id' => 'required|exists:modalidades,id',
            'horario' => 'required|string',
            'dia_semana' => 'required',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after:data_inicio',
            'limite_alunos' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'modalidade_id.required' => 'O campo modalidade é obrigatório.',
            'modalidade_id.exists' => 'A modalidade selecionada não existe.',
            'horario.required' => 'O campo horário é obrigatório.',
            'dia_semana.required' => 'O campo dia da semana é obrigatório.',
            'data_inicio.required' => 'O campo data de início é obrigatório.',
            'data_inicio.date' => 'O campo data de início deve ser uma data válida.',
            'data_fim.required' => 'O campo data de fim é obrigatório.',
            'data_fim.date' => 'O campo data de fim deve ser uma data válida.',
            'data_fim.after' => 'A data de fim deve ser posterior à data de início.',
            'limite_alunos.required' => 'O campo limite de alunos é obrigatório.',
            'limite_alunos.integer' => 'O campo limite de alunos deve ser um número inteiro.',
            'limite_alunos.min' => 'O limite de alunos deve ser pelo menos 1.',
        ];
    }
}
