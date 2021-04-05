<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\JsonResponse;

class TransactionRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
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
            'amount' => [
                'required'
            ],
            'payer' => [
                'required'
            ],
            'payee' => [
                'required'
            ]
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'amount.required' => 'O campo "Valor" é obrigatório',
            'payer.required' => 'O campo "Pagador" é obrigatório',
            'payee.required' => 'O campo "Recebedor" é obrigatório'
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
