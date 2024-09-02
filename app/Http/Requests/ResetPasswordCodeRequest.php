<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetPasswordCodeRequest extends FormRequest
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
        ], 422));  // O código de status HTTP 422 significa "Unprocessable Entity" (Entidade Não Processável). Esse código é usado quando o servidor entende a requisição do cliente, mas não pode processá-la devido a erros de validação no lado do servidor.
    }

    public function rules(): array
    {

        return [
            'email' => 'required|email',
            'code' => 'required',
            'password' => 'required|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Campo e-mail é obrigatório!',
            'email.email' => 'Necessário enviar e-mail válido!',
            'code.required' => 'Campo código é obrigatório!',
            'password.required' => 'Campo senha é obrigatório!',
            'password.min' => 'Senha com no mínimo :min caracteres!',
        ];
    }
}
