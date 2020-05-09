<?php

namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SistemaStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'descricao' => 'required|max:100',
            'sigla' => 'required|max:10',
            'email' => 'email:filter|max:100',
            'url' => 'max:50',
            'status' => 'max:50',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */

    public function messages()
    {
        return [
            'descricao.required' => 'O campo descricao é obrigatório.',
            'descricao.max' => 'A descricao não pode ter mais que :max caracteres.',
            'sigla.required' => 'O campo sigla é obrigatório.',
            'sigla.max' => 'A sigla não pode ter mais que :max caracteres.',
            'email.email' => 'Por favor digite um e-mail válido.',
            'email.max' => 'O e-mail não pode ter mais que :max caracteres.',
            'url.max' => 'A url não pode ter mais que :max caracteres.',
            'status.max' => 'O status não pode ter mais que :max caracteres.',
        ];
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            [
                "success" => false,
                "code" => 422,
                "error" => $validator->errors(),
                "message" => "Um ou mais campos são requiridos."
            ], 422));
    }

}
