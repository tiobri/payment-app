<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required'
            ],
            'cpf_cnpj' => [
                'required',
                'unique:users'
            ],
            'email' => [
                'required',
                'email',
                'unique:users'
            ],
            'password' => [
                'required',
                'min:8'
            ]
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O campo "Nome" é obrigatório',
            'cpf_cnpj.required' => 'O campo "CPF/CNPJ" é obrigatório',
            'cpf_cnpj.unique' => 'O "CPF/CNPJ" informado já está cadastrado',
            'email.required' => 'O campo "E-mail" é obrigatório',
            'email.email' => 'O "E-mail" informado é inválido',
            'email.unique' => 'O "E-mail" informado já está cadastrado',
            'password.required' => 'O campo "Senha" é obrigatório',
            'password.min' => 'O campo "Senha" deve conter no mínimo 8 dígitos',
        ];
    }

    /**
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json(
                $validator->errors(),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }
}
