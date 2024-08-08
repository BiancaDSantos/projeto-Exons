<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ConsultorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome_completo' => 'required|string|regex:/^[\pL\s\-]+$/u|min:2|max:255',
            'valor_por_hora' => 'required|numeric|regex:/^\d{1,10}(\.\d{1,2})?$/',
        ];
    }

    public function messages(){
        return [
            'nome_completo.required' => 'O nome completo do consultor é obrigatória.',
            'nome_completo.string' => 'O nome completo do consultor deve ser uma string.',
            'nome_completo.regex' => 'O nome completo do consultor deve conter apenas letras.',
            'valor_por_hora.required' => 'O valor por hora do consultor é obrigatória.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
}
